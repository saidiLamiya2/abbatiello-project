<?php

namespace App\Policies;

use App\Models\Theme;
use App\Models\User;

class ThemePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('ViewAny:Theme');
    }

    public function view(User $user, Theme $theme): bool
    {
        return $user->hasPermissionTo('View:Theme');
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('Create:Theme');
    }

    public function update(User $user, Theme $theme): bool
    {
        return $user->hasPermissionTo('Update:Theme');
    }

    public function delete(User $user, Theme $theme): bool
    {
        // Cannot delete a theme that is still assigned to a brand
        if ($theme->brands()->exists()) {
            return false;
        }

        return $user->hasPermissionTo('Delete:Theme');
    }

    public function restore(User $user, Theme $theme): bool
    {
        return $user->hasPermissionTo('Delete:Theme');
    }

    public function forceDelete(User $user, Theme $theme): bool
    {
        return false;
    }
}