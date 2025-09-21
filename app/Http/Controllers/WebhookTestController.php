<?php

namespace App\Http\Controllers;

use App\Services\WebhookSigner;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

/**
 * Webhook Test Controller
 * 
 * Provides endpoints for testing webhook delivery and signature verification
 */
class WebhookTestController extends Controller
{
    public function __construct(
        private WebhookSigner $signer
    ) {}

    /**
     * Test endpoint that always returns 200
     */
    public function success(Request $request): JsonResponse
    {
        Log::info('Webhook test endpoint hit - SUCCESS', [
            'headers' => $request->headers->all(),
            'payload' => $request->all(),
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Webhook received successfully',
            'timestamp' => now()->toISOString(),
            'received_data' => $request->all(),
        ]);
    }

    /**
     * Test endpoint that returns 500 error
     */
    public function error(Request $request): JsonResponse
    {
        Log::info('Webhook test endpoint hit - ERROR', [
            'headers' => $request->headers->all(),
            'payload' => $request->all(),
        ]);

        return response()->json([
            'status' => 'error',
            'message' => 'Simulated server error',
            'timestamp' => now()->toISOString(),
        ], 500);
    }

    /**
     * Test endpoint that verifies HMAC signature
     */
    public function verify(Request $request): JsonResponse
    {
        $signature = $request->header('X-Signature');
        $payload = $request->all();
        $secret = config('app.webhook_fallback_secret', 'test-secret');

        Log::info('Webhook test endpoint hit - VERIFY', [
            'signature' => $signature,
            'payload' => $payload,
        ]);

        if (!$signature) {
            return response()->json([
                'status' => 'error',
                'message' => 'Missing X-Signature header',
                'timestamp' => now()->toISOString(),
            ], 400);
        }

        $isValid = $this->signer->verify($payload, $signature, $secret);

        return response()->json([
            'status' => $isValid ? 'success' : 'error',
            'message' => $isValid ? 'Signature is valid' : 'Signature is invalid',
            'signature_valid' => $isValid,
            'received_signature' => $signature,
            'timestamp' => now()->toISOString(),
        ], $isValid ? 200 : 400);
    }

    /**
     * Test endpoint that simulates timeout
     */
    public function timeout(Request $request): JsonResponse
    {
        Log::info('Webhook test endpoint hit - TIMEOUT', [
            'headers' => $request->headers->all(),
            'payload' => $request->all(),
        ]);

        // Simulate slow response
        sleep(10);

        return response()->json([
            'status' => 'success',
            'message' => 'This should not be reached due to timeout',
            'timestamp' => now()->toISOString(),
        ]);
    }
}
