<?php
namespace App\Filament\Resources\Themes\Pages;
use App\Filament\Resources\Themes\ThemeResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditTheme extends EditRecord
{
    protected static string $resource = ThemeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make()
                ->visible(fn () => auth()->user()->hasAnyRole(['super_admin', 'admin']) && ! $this->record->trashed()),
            RestoreAction::make()
                ->visible(fn () => auth()->user()->hasAnyRole(['super_admin', 'admin']) && $this->record->trashed()),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}