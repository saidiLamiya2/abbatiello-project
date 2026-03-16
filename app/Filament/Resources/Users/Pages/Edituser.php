<?php

namespace App\Filament\Resources\Users\Pages;

use App\Actions\Users\AssignUserRole;
use App\Filament\Resources\Users\UserResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make()
                ->visible(fn () => ! $this->record->trashed() && auth()->user()->hasAnyRole(['super_admin', 'admin'])),
            RestoreAction::make()
                ->visible(fn () => $this->record->trashed() && auth()->user()->hasAnyRole(['super_admin', 'admin'])),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function afterSave(): void
    {
        app(AssignUserRole::class)->execute(
            $this->record,
            $this->data['role'] ?? null
        );
    }
}