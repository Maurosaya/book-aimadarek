<?php

namespace App\Policies;

use App\Models\Resource;
use App\Models\User;

/**
 * Resource Policy
 * 
 * Manages access control for resource resources within tenant context
 * Only managers and owners can manage resources
 */
class ResourcePolicy extends TenantPolicy
{
    /**
     * Determine whether the user can view any resources.
     */
    public function viewAny(User $user): bool
    {
        // All staff can view resources
        return $this->canAccessCurrentTenant($user);
    }

    /**
     * Determine whether the user can view the resource.
     */
    public function view(User $user, Resource $resource): bool
    {
        // All staff can view resources
        return $this->canAccessCurrentTenant($user) && 
               $this->resourceBelongsToCurrentTenant($resource);
    }

    /**
     * Determine whether the user can create resources.
     */
    public function create(User $user): bool
    {
        // Only managers and owners can create resources
        return $this->isAdminOfCurrentTenant($user);
    }

    /**
     * Determine whether the user can update the resource.
     */
    public function update(User $user, $resource): bool
    {
        // Only managers and owners can update resources
        return $this->isAdminOfCurrentTenant($user) && 
               $this->resourceBelongsToCurrentTenant($resource);
    }

    /**
     * Determine whether the user can delete the resource.
     */
    public function delete(User $user, $resource): bool
    {
        // Only owners can delete resources
        return $this->isOwnerOfCurrentTenant($user) && 
               $this->resourceBelongsToCurrentTenant($resource);
    }
}