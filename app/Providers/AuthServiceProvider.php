<?php

namespace App\Providers;

use App\Models\Booking;
use App\Models\WebhookEndpoint;
use App\Policies\BookingPolicy;
use App\Policies\WebhookEndpointPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Booking::class => BookingPolicy::class,
        WebhookEndpoint::class => WebhookEndpointPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        // Define additional gates if needed
        Gate::define('manage-bookings', function ($user) {
            return $user->hasRole(['owner', 'manager']);
        });

        Gate::define('view-all-bookings', function ($user) {
            return $user->hasRole(['owner', 'manager', 'staff']);
        });
    }
}
