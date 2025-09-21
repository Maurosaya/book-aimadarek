<?php

namespace App\Listeners;

use App\Events\BookingCancelled;
use App\Jobs\WebhookDispatchJob;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

/**
 * Send Booking Cancelled Webhook Listener
 * 
 * Dispatches webhook when a booking is cancelled
 */
class SendBookingCancelledWebhook implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(BookingCancelled $event): void
    {
        $booking = $event->booking;
        
        // Build webhook payload
        $payload = [
            'event' => 'booking.cancelled',
            'booking_id' => $booking->id,
            'tenant' => $booking->tenant_id,
            'service_id' => $booking->service_id,
            'start' => $booking->start_at->toISOString(),
            'end' => $booking->end_at->toISOString(),
            'customer' => [
                'name' => $booking->customer->name,
                'phone' => $booking->customer->phone,
                'email' => $booking->customer->email,
            ],
            'allocated_resources' => $booking->resources->map(function ($resource) {
                return [
                    'id' => $resource->id,
                    'type' => $resource->type,
                    'label' => $resource->label,
                    'capacity' => $resource->capacity,
                ];
            })->toArray(),
            'party_size' => $booking->party_size,
            'source' => $booking->source,
            'notes' => $booking->notes,
            'cancelled_at' => now()->toISOString(),
            'original_created_at' => $booking->created_at->toISOString(),
        ];

        // Dispatch webhook job
        WebhookDispatchJob::dispatch(
            $booking->tenant_id,
            'booking.cancelled',
            $payload,
            app()->getLocale()
        );

        Log::info('Booking cancelled webhook dispatched', [
            'booking_id' => $booking->id,
            'tenant_id' => $booking->tenant_id,
            'locale' => app()->getLocale(),
        ]);
    }

    /**
     * Handle a job failure.
     */
    public function failed(BookingCancelled $event, $exception): void
    {
        Log::error('Failed to dispatch booking cancelled webhook', [
            'booking_id' => $event->booking->id,
            'tenant_id' => $event->booking->tenant_id,
            'error' => $exception->getMessage(),
        ]);
    }
}