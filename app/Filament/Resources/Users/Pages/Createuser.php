<?php

namespace App\Filament\Resources\Users\Pages;

use App\Actions\Users\AssignUserRole;
use App\Filament\Resources\Users\UserResource;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function afterCreate(): void
    {
        app(AssignUserRole::class)->execute(
            $this->record,
            $this->data['role'] ?? null
        );
    }
}