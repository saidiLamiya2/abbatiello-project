<?php

namespace App\Filament\Resources\Users\Pages;

use App\Filament\Resources\Users\UserResource;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    /**
     * After the user record is created, assign the selected role via Spatie.
     * We use syncRoles() — not assignRole() — to enforce the one-role-at-a-time rule.
     * syncRoles() detaches all existing roles first, then assigns the new one.
     */
    protected function afterCreate(): void
    {
        $role = $this->data['role'] ?? null;

        if ($role) {
            $this->record->syncRoles([$role]);
        }
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}