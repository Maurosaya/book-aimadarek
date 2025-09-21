<?php

namespace App\Listeners;

use App\Events\BookingNoShow;
use App\Jobs\WebhookDispatchJob;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

/**
 * Send Booking No Show Webhook Listener
 * 
 * Dispatches webhook when a booking is marked as no-show
 * This is a stub implementation ready for future use
 */
class SendBookingNoShowWebhook implements ShouldQueue
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
    public function handle(BookingNoShow $event): void
    {
        $booking = $event->booking;
        
        // Build webhook payload
        $payload = [
            'event' => 'booking.no_show',
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
            'no_show_at' => now()->toISOString(),
            'original_created_at' => $booking->created_at->toISOString(),
        ];

        // Dispatch webhook job
        WebhookDispatchJob::dispatch(
            $booking->tenant_id,
            'booking.no_show',
            $payload,
            app()->getLocale()
        );

        Log::info('Booking no-show webhook dispatched', [
            'booking_id' => $booking->id,
            'tenant_id' => $booking->tenant_id,
            'locale' => app()->getLocale(),
        ]);
    }

    /**
     * Handle a job failure.
     */
    public function failed(BookingNoShow $event, $exception): void
    {
        Log::error('Failed to dispatch booking no-show webhook', [
            'booking_id' => $event->booking->id,
            'tenant_id' => $event->booking->tenant_id,
            'error' => $exception->getMessage(),
        ]);
    }
}