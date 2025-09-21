<?php

namespace App\Policies;

use App\Models\Customer;
use App\Models\User;

/**
 * Customer Policy
 * 
 * Manages access control for customer resources within tenant context
 * All staff can view customers, managers can edit, owners can delete
 */
class CustomerPolicy extends TenantPolicy
{
    /**
     * Determine whether the user can view any customers.
     */
    public function viewAny(User $user): bool
    {
        // All staff can view customers
        return $this->canAccessCurrentTenant($user);
    }

    /**
     * Determine whether the user can view the customer.
     */
    public function view(User $user, Customer $customer): bool
    {
        // All staff can view customers
        return $this->canAccessCurrentTenant($user) && 
               $this->resourceBelongsToCurrentTenant($customer);
    }

    /**
     * Determine whether the user can create customers.
     */
    public function create(User $user): bool
    {
        // All staff can create customers
        return $this->canAccessCurrentTenant($user);
    }

    /**
     * Determine whether the user can update the customer.
     */
    public function update(User $user, $customer): bool
    {
        // Managers and owners can update customers
        return $this->isAdminOfCurrentTenant($user) && 
               $this->resourceBelongsToCurrentTenant($customer);
    }

    /**
     * Determine whether the user can delete the customer.
     */
    public function delete(User $user, $customer): bool
    {
        // Only owners can delete customers
        return $this->isOwnerOfCurrentTenant($user) && 
               $this->resourceBelongsToCurrentTenant($customer);
    }
}