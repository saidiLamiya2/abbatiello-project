<?php

namespace App\Policies;

use App\Models\Brand;
use App\Models\User;

class BrandPolicy
{

    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('ViewAny:Brand');
    }

    public function view(User $user, Brand $brand): bool
    {
        if (! $user->hasPermissionTo('View:Brand')) {
            return false;
        }

        // admin scoped to own brand
        if ($user->hasRole('admin')) {
            return $user->brand_id === $brand->id;
        }

        return true;
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('Create:Brand');
    }

    public function update(User $user, Brand $brand): bool
    {
        if (! $user->hasPermissionTo('Update:Brand')) {
            return false;
        }

        if ($user->hasRole('admin')) {
            return $user->brand_id === $brand->id;
        }

        return true;
    }

    public function delete(User $user, Brand $brand): bool
    {
        if (! $user->hasPermissionTo('Delete:Brand')) {
            return false;
        }

        if ($user->hasRole('admin')) {
            return $user->brand_id === $brand->id;
        }

        return true;
    }

    public function restore(User $user, Brand $brand): bool
    {
        return $this->delete($user, $brand);
    }

    public function forceDelete(User $user, Brand $brand): bool
    {
        return false; // Never allow permanent deletion
    }
}