<?php

namespace App\Providers;

use App\Events\BookingCreated;
use App\Events\BookingCancelled;
use App\Events\BookingNoShow;
use App\Listeners\SendBookingCreatedWebhook;
use App\Listeners\SendBookingCancelledWebhook;
use App\Listeners\SendBookingNoShowWebhook;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        
        // Booking webhook events
        BookingCreated::class => [
            SendBookingCreatedWebhook::class,
        ],
        
        BookingCancelled::class => [
            SendBookingCancelledWebhook::class,
        ],
        
        BookingNoShow::class => [
            SendBookingNoShowWebhook::class,
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}