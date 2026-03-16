<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('ViewAny:User');
    }

    public function view(User $user, User $model): bool
    {
        if (! $user->hasPermissionTo('View:User')) {
            return false;
        }

        // manager scoped to own store
        if ($user->hasRole('manager')) {
            return $user->store_id === $model->store_id;
        }

        // admin scoped to own brand
        if ($user->hasRole('admin')) {
            return $user->brand_id === $model->brand_id;
        }

        return true;
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('Create:User');
    }

    public function update(User $user, User $model): bool
    {
        if (! $user->hasPermissionTo('Update:User')) {
            return false;
        }

        if ($user->hasRole('manager')) {
            return $user->store_id === $model->store_id;
        }

        if ($user->hasRole('admin')) {
            return $user->brand_id === $model->brand_id;
        }

        return true;
    }

    public function delete(User $user, User $model): bool
    {
        // Cannot delete yourself
        if ($user->id === $model->id) {
            return false;
        }

        if (! $user->hasPermissionTo('Delete:User')) {
            return false;
        }

        // Cannot delete a user with a higher role
        if ($model->hasRole('super_admin')) {
            return false;
        }

        if ($user->hasRole('admin') && $model->hasRole('admin')) {
            return false;
        }

        if ($user->hasRole('admin')) {
            return $user->brand_id === $model->brand_id;
        }

        return true;
    }

    public function restore(User $user, User $model): bool
    {
        return $this->delete($user, $model);
    }

    public function forceDelete(User $user, User $model): bool
    {
        return false;
    }
}