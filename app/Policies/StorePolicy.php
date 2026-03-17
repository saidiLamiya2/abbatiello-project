<?php

namespace App\Policies;

use App\Models\Store;
use App\Models\User;

class StorePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('ViewAny:Store');
    }

    public function view(User $user, Store $store): bool
    {
        if (! $user->hasPermissionTo('View:Store')) {
            return false;
        }

        // Manager scoped to own store only
        if ($user->hasRole('manager')) {
            return $user->store_id === $store->id;
        }

        return true;
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('Create:Store');
    }

    public function update(User $user, Store $store): bool
    {
        if (! $user->hasPermissionTo('Update:Store')) {
            return false;
        }

        if ($user->hasRole('manager')) {
            return $user->store_id === $store->id;
        }

        return true;
    }

    public function delete(User $user, Store $store): bool
    {
        if (! $user->hasPermissionTo('Delete:Store')) {
            return false;
        }

        return true;
    }

    public function restore(User $user, Store $store): bool
    {
        return $this->delete($user, $store);
    }

    public function forceDelete(User $user, Store $store): bool
    {
        return false;
    }
}