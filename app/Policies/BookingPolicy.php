<?php

namespace App\Policies;

use App\Models\Booking;
use App\Models\User;

/**
 * Booking Policy
 * 
 * Manages access control for booking resources within tenant context
 * Staff can view and create bookings, managers can edit, owners can delete
 */
class BookingPolicy extends TenantPolicy
{

    /**
     * Determine whether the user can view the booking.
     */
    public function view(User $user, Booking $booking): bool
    {
        // All staff can view bookings in their tenant
        return $this->canAccessCurrentTenant($user) && 
               $this->resourceBelongsToCurrentTenant($booking);
    }

    /**
     * Determine whether the user can create bookings.
     */
    public function create(User $user): bool
    {
        // All staff can create bookings
        return $this->canAccessCurrentTenant($user);
    }

    /**
     * Determine whether the user can update the booking.
     */
    public function update(User $user, $booking): bool
    {
        // All staff can edit bookings (more permissive for demo)
        return $this->canAccessCurrentTenant($user) && 
               $this->resourceBelongsToCurrentTenant($booking);
    }

    /**
     * Determine whether the user can delete the booking.
     */
    public function delete(User $user, $booking): bool
    {
        // Only owners can delete bookings
        return $this->isOwnerOfCurrentTenant($user) && 
               $this->resourceBelongsToCurrentTenant($booking);
    }

    /**
     * Determine whether the user can cancel the booking.
     */
    public function cancel(User $user, Booking $booking): bool
    {
        // All staff can cancel bookings (more permissive for demo)
        return $this->canAccessCurrentTenant($user) && 
               $this->resourceBelongsToCurrentTenant($booking);
    }

    /**
     * Determine whether the user can mark booking as no-show.
     */
    public function markNoShow(User $user, Booking $booking): bool
    {
        // All staff can mark no-show
        return $this->canAccessCurrentTenant($user) && 
               $this->resourceBelongsToCurrentTenant($booking);
    }
}
