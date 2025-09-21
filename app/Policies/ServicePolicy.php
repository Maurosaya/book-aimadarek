<?php

namespace App\Policies;

use App\Models\Service;
use App\Models\User;

/**
 * Service Policy
 * 
 * Manages access control for service resources within tenant context
 * Only managers and owners can manage services
 */
class ServicePolicy extends TenantPolicy
{
    /**
     * Determine whether the user can view any services.
     */
    public function viewAny(User $user): bool
    {
        // All staff can view services
        return $this->canAccessCurrentTenant($user);
    }

    /**
     * Determine whether the user can view the service.
     */
    public function view(User $user, Service $service): bool
    {
        // All staff can view services
        return $this->canAccessCurrentTenant($user) && 
               $this->resourceBelongsToCurrentTenant($service);
    }

    /**
     * Determine whether the user can create services.
     */
    public function create(User $user): bool
    {
        // Only managers and owners can create services
        return $this->isAdminOfCurrentTenant($user);
    }

    /**
     * Determine whether the user can update the service.
     */
    public function update(User $user, $service): bool
    {
        // Only managers and owners can update services
        return $this->isAdminOfCurrentTenant($user) && 
               $this->resourceBelongsToCurrentTenant($service);
    }

    /**
     * Determine whether the user can delete the service.
     */
    public function delete(User $user, $service): bool
    {
        // Only owners can delete services
        return $this->isOwnerOfCurrentTenant($user) && 
               $this->resourceBelongsToCurrentTenant($service);
    }
}