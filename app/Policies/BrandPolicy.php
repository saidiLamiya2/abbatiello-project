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
        return $user->hasPermissionTo('View:Brand');
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('Create:Brand');
    }

    public function update(User $user, Brand $brand): bool
    {
        return $user->hasPermissionTo('Update:Brand');
    }

    public function delete(User $user, Brand $brand): bool
    {
        return $user->hasPermissionTo('Delete:Brand');
    }

    public function restore(User $user, Brand $brand): bool
    {
        return $user->hasPermissionTo('Delete:Brand');
    }

    public function forceDelete(User $user, Brand $brand): bool
    {
        return false;
    }
}