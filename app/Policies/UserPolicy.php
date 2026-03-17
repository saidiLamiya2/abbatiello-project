<?php

namespace App\Policies;

use App\Enums\UserRole;
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

        // Manager scoped to own store
        if ($user->hasRole('manager')) {
            return $user->store_id === $model->store_id;
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

        // Nobody can edit a super_admin except another super_admin (handled by Gate::before)
        if ($model->hasRole(UserRole::SuperAdmin->value)) {
            return false;
        }

        // Admin cannot edit another admin
        if ($user->hasRole(UserRole::Admin->value) && $model->hasRole(UserRole::Admin->value)) {
            return false;
        }

        if ($user->hasRole('manager')) {
            return $user->store_id === $model->store_id;
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

        // Nobody can delete a super_admin
        if ($model->hasRole(UserRole::SuperAdmin->value)) {
            return false;
        }

        // Admin cannot delete another admin
        if ($user->hasRole(UserRole::Admin->value) && $model->hasRole(UserRole::Admin->value)) {
            return false;
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