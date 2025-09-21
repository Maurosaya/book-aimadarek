<?php

namespace App\Policies;

use App\Models\WebhookEndpoint;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class WebhookEndpointPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any webhook endpoints.
     */
    public function viewAny(User $user): bool
    {
        return $this->belongsToCurrentTenant($user);
    }

    /**
     * Determine whether the user can view the webhook endpoint.
     */
    public function view(User $user, WebhookEndpoint $webhookEndpoint): bool
    {
        return $this->belongsToCurrentTenant($user) && 
               $this->endpointBelongsToCurrentTenant($webhookEndpoint);
    }

    /**
     * Determine whether the user can create webhook endpoints.
     */
    public function create(User $user): bool
    {
        return $this->belongsToCurrentTenant($user) && 
               $user->hasRole(['owner', 'manager']);
    }

    /**
     * Determine whether the user can update the webhook endpoint.
     */
    public function update(User $user, WebhookEndpoint $webhookEndpoint): bool
    {
        return $this->belongsToCurrentTenant($user) && 
               $this->endpointBelongsToCurrentTenant($webhookEndpoint) &&
               $user->hasRole(['owner', 'manager']);
    }

    /**
     * Determine whether the user can delete the webhook endpoint.
     */
    public function delete(User $user, WebhookEndpoint $webhookEndpoint): bool
    {
        return $this->belongsToCurrentTenant($user) && 
               $this->endpointBelongsToCurrentTenant($webhookEndpoint) &&
               $user->hasRole(['owner', 'manager']);
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

        return $user->tenant_id === $currentTenant->id;
    }

    /**
     * Check if webhook endpoint belongs to the current tenant
     */
    private function endpointBelongsToCurrentTenant(WebhookEndpoint $webhookEndpoint): bool
    {
        if (!tenancy()->initialized) {
            return false;
        }

        $currentTenant = tenancy()->tenant;
        
        if (!$currentTenant) {
            return false;
        }

        return $webhookEndpoint->tenant_id === $currentTenant->id;
    }
}