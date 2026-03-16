<?php

namespace App\Filament\Resources\Themes\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\RestoreAction;
use Filament\Tables\Columns\ColorColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class ThemesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label(__('app.themes.name'))
                    ->searchable()
                    ->sortable(),

                ColorColumn::make('primary_color')
                    ->label(__('app.themes.primary_label')),

                ColorColumn::make('secondary_color')
                    ->label(__('app.themes.secondary_label')),

                TextColumn::make('font_family')
                    ->label(__('app.themes.font_family')),

                TextColumn::make('filament_color')
                    ->label(__('app.themes.palette_label'))
                    ->badge(),

                TextColumn::make('brands_count')
                    ->label(__('app.themes.brands_count'))
                    ->counts('brands')
                    ->sortable(),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make()
                    ->visible(fn ($record) => ! $record->trashed()),
                RestoreAction::make()
                    ->visible(fn ($record) => $record->trashed()),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}