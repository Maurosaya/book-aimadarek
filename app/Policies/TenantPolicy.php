<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

/**
 * Base Tenant Policy
 * 
 * Provides common tenant isolation logic for all policies
 * Ensures users can only access resources from their own tenant
 */
abstract class TenantPolicy
{
    use HandlesAuthorization;

    /**
     * Check if user belongs to the current tenant
     */
    protected function belongsToCurrentTenant(User $user): bool
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
     * Check if user is owner of the current tenant
     */
    protected function isOwnerOfCurrentTenant(User $user): bool
    {
        return $this->belongsToCurrentTenant($user) && $user->isOwner();
    }

    /**
     * Check if user is admin (owner or manager) of the current tenant
     */
    protected function isAdminOfCurrentTenant(User $user): bool
    {
        return $this->belongsToCurrentTenant($user) && $user->isAdmin();
    }

    /**
     * Check if user can access the current tenant
     */
    protected function canAccessCurrentTenant(User $user): bool
    {
        return $this->belongsToCurrentTenant($user) && $user->active;
    }

    /**
     * Check if resource belongs to current tenant
     */
    protected function resourceBelongsToCurrentTenant($resource): bool
    {
        if (!tenancy()->initialized) {
            return false;
        }

        $currentTenant = tenancy()->tenant;
        
        if (!$currentTenant) {
            return false;
        }

        // Check if resource has tenant_id and matches current tenant
        return isset($resource->tenant_id) && $resource->tenant_id === $currentTenant->id;
    }

    /**
     * Check if user can view any resource (basic tenant access)
     */
    public function viewAny(User $user): bool
    {
        return $this->canAccessCurrentTenant($user);
    }

    /**
     * Check if user can create resources (admin access required)
     */
    public function create(User $user): bool
    {
        return $this->isAdminOfCurrentTenant($user);
    }

    /**
     * Check if user can update resources (admin access required)
     */
    public function update(User $user, $resource): bool
    {
        return $this->isAdminOfCurrentTenant($user) && 
               $this->resourceBelongsToCurrentTenant($resource);
    }

    /**
     * Check if user can delete resources (owner access required)
     */
    public function delete(User $user, $resource): bool
    {
        return $this->isOwnerOfCurrentTenant($user) && 
               $this->resourceBelongsToCurrentTenant($resource);
    }
}
