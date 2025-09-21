<?php

namespace App\Console\Commands;

use App\Jobs\WebhookDispatchJob;
use App\Models\WebhookEndpoint;
use App\Services\WebhookSigner;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

/**
 * Test Webhook Command
 * 
 * Command to test webhook delivery and signature verification
 */
class TestWebhook extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'webhook:test 
                            {--tenant= : Tenant ID to test webhook for}
                            {--endpoint= : Specific endpoint ID to test}
                            {--url= : Test URL (if not using existing endpoint)}
                            {--event=booking.created : Event type to test}
                            {--verify : Verify signature on test endpoint}';

    /**
     * The console command description.
     */
    protected $description = 'Test webhook delivery and signature verification';

    /**
     * Execute the console command.
     */
    public function handle(WebhookSigner $signer): int
    {
        $tenantId = $this->option('tenant');
        $endpointId = $this->option('endpoint');
        $testUrl = $this->option('url');
        $event = $this->option('event');
        $verify = $this->option('verify');

        if (!$tenantId) {
            $this->error('Tenant ID is required. Use --tenant=your-tenant-id');
            return 1;
        }

        // Create test payload
        $payload = [
            'event' => $event,
            'booking_id' => 'test-booking-' . uniqid(),
            'tenant' => $tenantId,
            'service_id' => 'test-service-123',
            'start' => now()->addHour()->toISOString(),
            'end' => now()->addHours(2)->toISOString(),
            'customer' => [
                'name' => 'Test Customer',
                'phone' => '+1234567890',
                'email' => 'test@example.com',
            ],
            'allocated_resources' => [
                [
                    'id' => 1,
                    'type' => 'TABLE',
                    'label' => 'Test Table',
                    'capacity' => 4,
                ]
            ],
            'party_size' => 4,
            'source' => 'test',
            'notes' => 'This is a test webhook',
            'created_at' => now()->toISOString(),
            'locale' => 'en',
        ];

        if ($endpointId) {
            // Test specific endpoint
            $endpoint = WebhookEndpoint::find($endpointId);
            if (!$endpoint) {
                $this->error("Endpoint with ID {$endpointId} not found");
                return 1;
            }

            $this->testEndpoint($endpoint, $payload, $signer, $verify);
        } elseif ($testUrl) {
            // Test custom URL
            $this->testCustomUrl($testUrl, $payload, $signer, $verify);
        } else {
            // Test all endpoints for tenant
            $endpoints = WebhookEndpoint::where('tenant_id', $tenantId)
                ->where('active', true)
                ->whereJsonContains('events', $event)
                ->get();

            if ($endpoints->isEmpty()) {
                $this->error("No active endpoints found for tenant {$tenantId} and event {$event}");
                return 1;
            }

            foreach ($endpoints as $endpoint) {
                $this->testEndpoint($endpoint, $payload, $signer, $verify);
            }
        }

        return 0;
    }

    /**
     * Test a specific endpoint
     */
    private function testEndpoint(WebhookEndpoint $endpoint, array $payload, WebhookSigner $signer, bool $verify): void
    {
        $this->info("Testing endpoint: {$endpoint->url}");

        try {
            // Get secret
            $secret = $endpoint->secret ?? $this->getTenantWebhookSecret($endpoint->tenant_id);
            
            // Sign payload
            $signature = $signer->sign($payload, $secret);
            
            // Send request
            $response = Http::timeout(8)
                ->withHeaders([
                    'Content-Type' => 'application/json',
                    'X-Signature' => $signature,
                    'User-Agent' => 'BookingSystem-Webhook/1.0',
                ])
                ->post($endpoint->url, $payload);

            if ($response->successful()) {
                $this->info("✅ Success: HTTP {$response->status()}");
            } else {
                $this->error("❌ Failed: HTTP {$response->status()}");
                $this->line("Response: {$response->body()}");
            }

            if ($verify) {
                $this->verifySignature($payload, $signature, $secret, $signer);
            }

        } catch (\Exception $e) {
            $this->error("❌ Exception: {$e->getMessage()}");
        }
    }

    /**
     * Test custom URL
     */
    private function testCustomUrl(string $url, array $payload, WebhookSigner $signer, bool $verify): void
    {
        $this->info("Testing custom URL: {$url}");

        try {
            // Use default secret for testing
            $secret = config('app.webhook_fallback_secret', 'test-secret');
            
            // Sign payload
            $signature = $signer->sign($payload, $secret);
            
            // Send request
            $response = Http::timeout(8)
                ->withHeaders([
                    'Content-Type' => 'application/json',
                    'X-Signature' => $signature,
                    'User-Agent' => 'BookingSystem-Webhook/1.0',
                ])
                ->post($url, $payload);

            if ($response->successful()) {
                $this->info("✅ Success: HTTP {$response->status()}");
            } else {
                $this->error("❌ Failed: HTTP {$response->status()}");
                $this->line("Response: {$response->body()}");
            }

            if ($verify) {
                $this->verifySignature($payload, $signature, $secret, $signer);
            }

        } catch (\Exception $e) {
            $this->error("❌ Exception: {$e->getMessage()}");
        }
    }

    /**
     * Verify signature
     */
    private function verifySignature(array $payload, string $signature, string $secret, WebhookSigner $signer): void
    {
        $isValid = $signer->verify($payload, $signature, $secret);
        
        if ($isValid) {
            $this->info("✅ Signature verification: VALID");
        } else {
            $this->error("❌ Signature verification: INVALID");
        }

        $this->line("Signature: {$signature}");
        $this->line("Payload size: " . strlen(json_encode($payload)) . " bytes");
    }

    /**
     * Get tenant webhook secret
     */
    private function getTenantWebhookSecret(string $tenantId): string
    {
        $tenant = \App\Models\Tenant::find($tenantId);
        return $tenant?->getWebhookSecret() ?? config('app.webhook_fallback_secret', 'default-secret');
    }
}