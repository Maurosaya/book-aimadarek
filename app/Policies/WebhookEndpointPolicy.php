<?php

namespace App\Policies;

use App\Models\WebhookEndpoint;
use App\Models\User;

/**
 * Webhook Endpoint Policy
 * 
 * Manages access control for webhook endpoint resources within tenant context
 * Only managers and owners can manage webhooks
 */
class WebhookEndpointPolicy extends TenantPolicy
{
    /**
     * Determine whether the user can view any webhook endpoints.
     */
    public function viewAny(User $user): bool
    {
        // All staff can view webhooks
        return $this->canAccessCurrentTenant($user);
    }

    /**
     * Determine whether the user can view the webhook endpoint.
     */
    public function view(User $user, WebhookEndpoint $webhookEndpoint): bool
    {
        // All staff can view webhooks
        return $this->canAccessCurrentTenant($user) && 
               $this->resourceBelongsToCurrentTenant($webhookEndpoint);
    }

    /**
     * Determine whether the user can create webhook endpoints.
     */
    public function create(User $user): bool
    {
        // Only managers and owners can create webhooks
        return $this->isAdminOfCurrentTenant($user);
    }

    /**
     * Determine whether the user can update the webhook endpoint.
     */
    public function update(User $user, $webhookEndpoint): bool
    {
        // Only managers and owners can update webhooks
        return $this->isAdminOfCurrentTenant($user) && 
               $this->resourceBelongsToCurrentTenant($webhookEndpoint);
    }

    /**
     * Determine whether the user can delete the webhook endpoint.
     */
    public function delete(User $user, $webhookEndpoint): bool
    {
        // Only owners can delete webhooks
        return $this->isOwnerOfCurrentTenant($user) && 
               $this->resourceBelongsToCurrentTenant($webhookEndpoint);
    }

    /**
     * Determine whether the user can test the webhook endpoint.
     */
    public function test(User $user, WebhookEndpoint $webhookEndpoint): bool
    {
        // Managers and owners can test webhooks
        return $this->isAdminOfCurrentTenant($user) && 
               $this->resourceBelongsToCurrentTenant($webhookEndpoint);
    }
}