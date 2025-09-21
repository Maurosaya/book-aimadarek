<?php

namespace App\Policies;

use App\Models\Booking;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Tenancy;

/**
 * Booking Policy
 * 
 * Ensures that only the tenant owner can access their bookings
 * Implements tenant isolation for booking resources
 */
class BookingPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any bookings.
     */
    public function viewAny(User $user): bool
    {
        // Only allow if user belongs to the current tenant
        return $this->belongsToCurrentTenant($user);
    }

    /**
     * Determine whether the user can view the booking.
     */
    public function view(User $user, Booking $booking): bool
    {
        // Check if user belongs to current tenant and booking belongs to same tenant
        return $this->belongsToCurrentTenant($user) && 
               $this->bookingBelongsToCurrentTenant($booking);
    }

    /**
     * Determine whether the user can create bookings.
     */
    public function create(User $user): bool
    {
        // Allow creation if user belongs to current tenant
        return $this->belongsToCurrentTenant($user);
    }

    /**
     * Determine whether the user can update the booking.
     */
    public function update(User $user, Booking $booking): bool
    {
        // Check if user belongs to current tenant and booking belongs to same tenant
        return $this->belongsToCurrentTenant($user) && 
               $this->bookingBelongsToCurrentTenant($booking);
    }

    /**
     * Determine whether the user can delete the booking.
     */
    public function delete(User $user, Booking $booking): bool
    {
        // Check if user belongs to current tenant and booking belongs to same tenant
        return $this->belongsToCurrentTenant($user) && 
               $this->bookingBelongsToCurrentTenant($booking);
    }

    /**
     * Determine whether the user can cancel the booking.
     */
    public function cancel(User $user, Booking $booking): bool
    {
        // Check if user belongs to current tenant and booking belongs to same tenant
        return $this->belongsToCurrentTenant($user) && 
               $this->bookingBelongsToCurrentTenant($booking);
    }

    /**
     * Check if user belongs to the current tenant
     */
    private function belongsToCurrentTenant(User $user): bool
    {
        if (!tenancy()->initialized) {
            return false;
        }

        $currentTenant = tenancy()->tenant;
        
        if (!$currentTenant) {
            return false;
        }

        // Check if user's tenant_id matches current tenant
        return $user->tenant_id === $currentTenant->id;
    }

    /**
     * Check if booking belongs to the current tenant
     */
    private function bookingBelongsToCurrentTenant(Booking $booking): bool
    {
        if (!tenancy()->initialized) {
            return false;
        }

        $currentTenant = tenancy()->tenant;
        
        if (!$currentTenant) {
            return false;
        }

        // Check if booking's tenant_id matches current tenant
        return $booking->tenant_id === $currentTenant->id;
    }
}
