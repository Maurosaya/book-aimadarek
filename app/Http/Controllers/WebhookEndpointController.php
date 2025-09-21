<?php

namespace App\Http\Controllers;

use App\Models\WebhookEndpoint;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Rule;

/**
 * Webhook Endpoint Controller
 * 
 * Handles CRUD operations for webhook endpoints
 * Provides basic management interface for webhook configuration
 */
class WebhookEndpointController extends Controller
{
    /**
     * Display a listing of webhook endpoints.
     */
    public function index(): JsonResponse
    {
        $endpoints = WebhookEndpoint::where('tenant_id', tenancy()->tenant->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'endpoints' => $endpoints,
            'locale' => app()->getLocale(),
        ]);
    }

    /**
     * Store a newly created webhook endpoint.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'url' => [
                'required',
                'url',
                'max:255',
            ],
            'events' => [
                'required',
                'array',
                'min:1',
            ],
            'events.*' => [
                'string',
                Rule::in(['booking.created', 'booking.cancelled', 'booking.no_show']),
            ],
            'secret' => [
                'nullable',
                'string',
                'max:255',
            ],
            'active' => [
                'boolean',
            ],
        ]);

        $endpoint = WebhookEndpoint::create([
            'tenant_id' => tenancy()->tenant->id,
            'url' => $validated['url'],
            'events' => $validated['events'],
            'secret' => $validated['secret'] ?? null,
            'active' => $validated['active'] ?? true,
        ]);

        return response()->json([
            'message' => __('api.webhooks.endpoint_created'),
            'endpoint' => $endpoint,
            'locale' => app()->getLocale(),
        ], 201);
    }

    /**
     * Display the specified webhook endpoint.
     */
    public function show(WebhookEndpoint $webhookEndpoint): JsonResponse
    {
        $this->authorize('view', $webhookEndpoint);

        return response()->json([
            'endpoint' => $webhookEndpoint,
            'locale' => app()->getLocale(),
        ]);
    }

    /**
     * Update the specified webhook endpoint.
     */
    public function update(Request $request, WebhookEndpoint $webhookEndpoint): JsonResponse
    {
        $this->authorize('update', $webhookEndpoint);

        $validated = $request->validate([
            'url' => [
                'sometimes',
                'required',
                'url',
                'max:255',
            ],
            'events' => [
                'sometimes',
                'required',
                'array',
                'min:1',
            ],
            'events.*' => [
                'string',
                Rule::in(['booking.created', 'booking.cancelled', 'booking.no_show']),
            ],
            'secret' => [
                'nullable',
                'string',
                'max:255',
            ],
            'active' => [
                'boolean',
            ],
        ]);

        $webhookEndpoint->update($validated);

        return response()->json([
            'message' => __('api.webhooks.endpoint_updated'),
            'endpoint' => $webhookEndpoint->fresh(),
            'locale' => app()->getLocale(),
        ]);
    }

    /**
     * Remove the specified webhook endpoint.
     */
    public function destroy(WebhookEndpoint $webhookEndpoint): JsonResponse
    {
        $this->authorize('delete', $webhookEndpoint);

        $webhookEndpoint->delete();

        return response()->json([
            'message' => __('api.webhooks.endpoint_deleted'),
            'locale' => app()->getLocale(),
        ]);
    }

    /**
     * Test webhook endpoint by sending a test payload.
     */
    public function test(WebhookEndpoint $webhookEndpoint): JsonResponse
    {
        $this->authorize('update', $webhookEndpoint);

        // Create a test payload
        $testPayload = [
            'event' => 'test',
            'message' => 'This is a test webhook from Booking System',
            'timestamp' => now()->toISOString(),
            'tenant' => tenancy()->tenant->id,
            'locale' => app()->getLocale(),
        ];

        // Dispatch test webhook
        \App\Jobs\WebhookDispatchJob::dispatch(
            tenancy()->tenant->id,
            'test',
            $testPayload,
            app()->getLocale()
        );

        return response()->json([
            'message' => __('api.webhooks.test_sent'),
            'locale' => app()->getLocale(),
        ]);
    }
}