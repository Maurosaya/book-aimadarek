<?php

namespace App\Jobs;

use App\Models\WebhookEndpoint;
use App\Models\WebhookLog;
use App\Services\WebhookSigner;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

/**
 * Webhook Dispatch Job
 * 
 * Handles webhook delivery with HMAC signing, retries, and logging
 * Implements exponential backoff for failed deliveries
 */
class WebhookDispatchJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of times the job may be attempted.
     */
    public int $tries = 3;

    /**
     * The maximum number of seconds the job can run.
     */
    public int $timeout = 30;

    /**
     * Calculate the number of seconds to wait before retrying the job.
     */
    public function backoff(): array
    {
        return [60, 300, 900]; // 1 min, 5 min, 15 min
    }

    public function __construct(
        public string $tenantId,
        public string $event,
        public array $payload,
        public ?string $locale = null
    ) {}

    /**
     * Execute the job.
     */
    public function handle(WebhookSigner $signer): void
    {
        // Get active webhook endpoints for this tenant and event
        $endpoints = WebhookEndpoint::where('tenant_id', $this->tenantId)
            ->where('active', true)
            ->whereJsonContains('events', $this->event)
            ->get();

        if ($endpoints->isEmpty()) {
            Log::info('No webhook endpoints found for event', [
                'tenant_id' => $this->tenantId,
                'event' => $this->event,
            ]);
            return;
        }

        // Add locale to payload if not already present
        if ($this->locale && !isset($this->payload['locale'])) {
            $this->payload['locale'] = $this->locale;
        }

        // Validate payload size
        if (!$signer->validatePayloadSize($this->payload)) {
            Log::error('Webhook payload too large', [
                'tenant_id' => $this->tenantId,
                'event' => $this->event,
                'payload_size' => strlen(json_encode($this->payload)),
            ]);
            return;
        }

        // Sanitize payload
        $sanitizedPayload = $signer->sanitizePayload($this->payload);

        // Dispatch to each endpoint
        foreach ($endpoints as $endpoint) {
            $this->dispatchToEndpoint($endpoint, $sanitizedPayload, $signer);
        }
    }

    /**
     * Dispatch webhook to a specific endpoint
     */
    private function dispatchToEndpoint(WebhookEndpoint $endpoint, array $payload, WebhookSigner $signer): void
    {
        try {
            // Get webhook secret
            $secret = $endpoint->secret ?? $this->getTenantWebhookSecret();
            
            // Sign the payload
            $signature = $signer->sign($payload, $secret);
            
            // Prepare headers
            $headers = [
                'Content-Type' => 'application/json',
                'X-Signature' => $signature,
                'User-Agent' => 'BookingSystem-Webhook/1.0',
            ];

            // Send HTTP request
            $response = Http::timeout(8)
                ->withHeaders($headers)
                ->post($endpoint->url, $payload);

            // Log the attempt
            $this->logWebhookAttempt($endpoint, $payload, $signature, $response);

            // Handle response
            if ($response->successful()) {
                Log::info('Webhook delivered successfully', [
                    'endpoint_id' => $endpoint->id,
                    'event' => $this->event,
                    'status_code' => $response->status(),
                ]);
            } else {
                $this->handleFailedDelivery($endpoint, $response);
            }

        } catch (Exception $e) {
            Log::error('Webhook delivery failed with exception', [
                'endpoint_id' => $endpoint->id,
                'event' => $this->event,
                'error' => $e->getMessage(),
            ]);

            $this->logWebhookAttempt($endpoint, $payload, null, null, $e->getMessage());
            $this->handleFailedDelivery($endpoint, null, $e);
        }
    }

    /**
     * Log webhook attempt
     */
    private function logWebhookAttempt(
        WebhookEndpoint $endpoint, 
        array $payload, 
        ?string $signature, 
        $response = null, 
        ?string $error = null
    ): void {
        WebhookLog::create([
            'endpoint_id' => $endpoint->id,
            'event' => $this->event,
            'payload' => $payload,
            'response_code' => $response?->status(),
            'response_body' => $response?->body(),
            'signature' => $signature,
            'delivered_at' => $response?->successful() ? now() : null,
            'retries' => $this->attempts() - 1,
        ]);
    }

    /**
     * Handle failed webhook delivery
     */
    private function handleFailedDelivery(WebhookEndpoint $endpoint, $response = null, ?Exception $exception = null): void
    {
        $statusCode = $response?->status();
        $isRetryable = $this->isRetryableError($statusCode, $exception);

        if ($isRetryable && $this->attempts() < $this->tries) {
            Log::warning('Webhook delivery failed, will retry', [
                'endpoint_id' => $endpoint->id,
                'event' => $this->event,
                'attempt' => $this->attempts(),
                'max_attempts' => $this->tries,
                'status_code' => $statusCode,
                'error' => $exception?->getMessage(),
            ]);

            // The job will be automatically retried by Laravel
            throw new Exception('Webhook delivery failed, retrying...');
        } else {
            Log::error('Webhook delivery failed permanently', [
                'endpoint_id' => $endpoint->id,
                'event' => $this->event,
                'attempts' => $this->attempts(),
                'status_code' => $statusCode,
                'error' => $exception?->getMessage(),
            ]);
        }
    }

    /**
     * Determine if an error is retryable
     */
    private function isRetryableError(?int $statusCode, ?Exception $exception): bool
    {
        // Retry on server errors (5xx) or network timeouts
        if ($statusCode && $statusCode >= 500) {
            return true;
        }

        // Retry on network exceptions
        if ($exception && (
            str_contains($exception->getMessage(), 'timeout') ||
            str_contains($exception->getMessage(), 'connection') ||
            str_contains($exception->getMessage(), 'network')
        )) {
            return true;
        }

        return false;
    }

    /**
     * Get tenant webhook secret
     */
    private function getTenantWebhookSecret(): string
    {
        if (!tenancy()->initialized) {
            return config('app.webhook_fallback_secret', 'default-webhook-secret');
        }

        $tenant = tenancy()->tenant;
        return $tenant?->getWebhookSecret() ?? config('app.webhook_fallback_secret', 'default-webhook-secret');
    }

    /**
     * Handle a job failure.
     */
    public function failed(?Exception $exception): void
    {
        Log::error('WebhookDispatchJob failed permanently', [
            'tenant_id' => $this->tenantId,
            'event' => $this->event,
            'attempts' => $this->attempts(),
            'error' => $exception?->getMessage(),
        ]);
    }
}