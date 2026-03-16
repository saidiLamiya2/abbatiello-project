<?php

namespace App\Actions\Users;

use App\Models\User;

class AssignUserRole
{

    public function execute(User $user, ?string $role): void
    {
        if (blank($role)) {
            return;
        }

        $user->syncRoles([$role]);
    }
}