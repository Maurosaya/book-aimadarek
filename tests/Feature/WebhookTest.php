<?php

use App\Jobs\WebhookDispatchJob;
use App\Models\WebhookEndpoint;
use App\Models\WebhookLog;
use App\Services\WebhookSigner;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Queue;

describe('Webhook System', function () {
    beforeEach(function () {
        // Set up test tenant
        $this->tenant = \App\Models\Tenant::create([
            'id' => 'test-tenant',
            'brand_name' => 'Test Tenant',
            'default_locale' => 'en',
            'supported_locales' => ['en', 'es', 'nl'],
            'timezone' => 'UTC',
            'webhook_secret' => 'test-secret-123',
        ]);

        // Set up test webhook endpoint
        $this->endpoint = WebhookEndpoint::create([
            'tenant_id' => $this->tenant->id,
            'url' => 'https://httpbin.org/post',
            'events' => ['booking.created', 'booking.cancelled'],
            'secret' => 'endpoint-secret-456',
            'active' => true,
        ]);
    });

    it('can sign and verify webhook payloads', function () {
        $signer = new WebhookSigner();
        $payload = [
            'event' => 'booking.created',
            'booking_id' => 'test-123',
            'tenant' => 'test-tenant',
        ];
        $secret = 'test-secret-123';

        $signature = $signer->sign($payload, $secret);
        
        expect($signature)->toStartWith('sha256=');
        expect($signer->verify($payload, $signature, $secret))->toBeTrue();
        expect($signer->verify($payload, 'sha256=invalid', $secret))->toBeFalse();
    });

    it('canonicalizes JSON consistently', function () {
        $signer = new WebhookSigner();
        $payload1 = ['c' => 3, 'a' => 1, 'b' => 2];
        $payload2 = ['a' => 1, 'b' => 2, 'c' => 3];
        $secret = 'test-secret';

        $signature1 = $signer->sign($payload1, $secret);
        $signature2 = $signer->sign($payload2, $secret);

        expect($signature1)->toBe($signature2);
    });

    it('validates payload size limits', function () {
        $signer = new WebhookSigner();
        $smallPayload = ['event' => 'test'];
        $largePayload = array_fill(0, 10000, 'data'); // Large array

        expect($signer->validatePayloadSize($smallPayload))->toBeTrue();
        expect($signer->validatePayloadSize($largePayload, 1000))->toBeFalse();
    });

    it('sanitizes sensitive data from payloads', function () {
        $signer = new WebhookSigner();
        $payload = [
            'event' => 'test',
            'password' => 'secret123',
            'token' => 'abc123',
            'normal_field' => 'value',
        ];

        $sanitized = $signer->sanitizePayload($payload);

        expect($sanitized['password'])->toBe('[REDACTED]');
        expect($sanitized['token'])->toBe('[REDACTED]');
        expect($sanitized['normal_field'])->toBe('value');
    });

    it('dispatches webhook job when booking is created', function () {
        Queue::fake();

        $booking = \App\Models\Booking::factory()->create([
            'tenant_id' => $this->tenant->id,
            'status' => 'confirmed',
        ]);

        event(new \App\Events\BookingCreated($booking));

        Queue::assertPushed(WebhookDispatchJob::class, function ($job) use ($booking) {
            return $job->tenantId === $this->tenant->id &&
                   $job->event === 'booking.created' &&
                   $job->payload['booking_id'] === $booking->id;
        });
    });

    it('logs webhook attempts correctly', function () {
        Http::fake([
            'https://httpbin.org/post' => Http::response(['status' => 'ok'], 200),
        ]);

        $payload = [
            'event' => 'booking.created',
            'booking_id' => 'test-123',
            'tenant' => $this->tenant->id,
        ];

        $job = new WebhookDispatchJob($this->tenant->id, 'booking.created', $payload, 'en');
        $job->handle(new WebhookSigner());

        $log = WebhookLog::where('endpoint_id', $this->endpoint->id)->first();
        
        expect($log)->not->toBeNull();
        expect($log->event)->toBe('booking.created');
        expect($log->response_code)->toBe(200);
        expect($log->delivered_at)->not->toBeNull();
    });

    it('retries failed webhook deliveries', function () {
        Http::fake([
            'https://httpbin.org/post' => Http::response(['error' => 'server error'], 500),
        ]);

        $payload = [
            'event' => 'booking.created',
            'booking_id' => 'test-123',
            'tenant' => $this->tenant->id,
        ];

        $job = new WebhookDispatchJob($this->tenant->id, 'booking.created', $payload, 'en');
        
        // First attempt should fail and be retried
        try {
            $job->handle(new WebhookSigner());
        } catch (\Exception $e) {
            // Expected to throw for retry
        }

        $log = WebhookLog::where('endpoint_id', $this->endpoint->id)->first();
        
        expect($log)->not->toBeNull();
        expect($log->response_code)->toBe(500);
        expect($log->delivered_at)->toBeNull();
        expect($log->retries)->toBe(0);
    });

    it('can create and manage webhook endpoints via API', function () {
        $user = \App\Models\User::factory()->create([
            'tenant_id' => $this->tenant->id,
        ]);

        $response = $this->actingAs($user, 'sanctum')
            ->postJson('/api/v1/webhook-endpoints', [
                'url' => 'https://example.com/webhook',
                'events' => ['booking.created'],
                'active' => true,
            ]);

        $response->assertStatus(201);
        $response->assertJsonStructure([
            'message',
            'endpoint' => [
                'id',
                'url',
                'events',
                'active',
            ],
        ]);

        expect(WebhookEndpoint::where('url', 'https://example.com/webhook')->exists())->toBeTrue();
    });

    it('validates webhook endpoint data', function () {
        $user = \App\Models\User::factory()->create([
            'tenant_id' => $this->tenant->id,
        ]);

        $response = $this->actingAs($user, 'sanctum')
            ->postJson('/api/v1/webhook-endpoints', [
                'url' => 'invalid-url',
                'events' => ['invalid-event'],
            ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['url', 'events.0']);
    });

    it('can test webhook endpoints', function () {
        $user = \App\Models\User::factory()->create([
            'tenant_id' => $this->tenant->id,
        ]);

        $response = $this->actingAs($user, 'sanctum')
            ->postJson("/api/v1/webhook-endpoints/{$this->endpoint->id}/test");

        $response->assertStatus(200);
        $response->assertJson([
            'message' => 'Test webhook sent successfully',
        ]);
    });
});
