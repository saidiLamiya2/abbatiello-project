<?php

namespace App\Filament\Resources\Stores\Tables;

use App\Enums\ProjectType;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class StoresTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(function (Builder $query) {
                $user = auth()->user();
                if ($user->hasRole('manager')) {
                    $query->where('id', $user->store_id);
                }
            })
            ->columns([
                TextColumn::make('brand.tag')
                    ->label(__('app.stores.brand'))
                    ->badge()
                    ->color('primary')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('name')
                    ->label(__('app.stores.name'))
                    ->searchable()
                    ->sortable(),

                TextColumn::make('franchise_number')
                    ->label(__('app.stores.franchise_number'))
                    ->searchable()
                    ->placeholder('—')
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('city')
                    ->label(__('app.stores.city'))
                    ->sortable()
                    ->searchable(),

                    TextColumn::make('project_type')
                    ->label(__('app.stores.project_type_short'))
                    ->badge()
                    ->sortable()
                    ->color(fn (string|ProjectType|null $state): string =>
                        ($state instanceof ProjectType ? $state : ProjectType::tryFrom((string) $state))?->color() ?? 'gray'
                    ),

                IconColumn::make('is_active')
                    ->label(__('app.stores.is_active'))
                    ->boolean(),

                TextColumn::make('expected_opening_date')
                    ->label(__('app.stores.opening_date'))
                    ->date('d/m/Y')
                    ->sortable()
                    ->placeholder('—')
                    ->toggleable(),

                TextColumn::make('users_count')
                    ->label(__('app.stores.users_count'))
                    ->counts('users')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('brand_id')
                    ->label(__('app.stores.brand'))
                    ->relationship('brand', 'name')
                    ->searchable()
                    ->preload()
                    ->visible(fn () => auth()->user()->hasAnyRole(['super_admin', 'admin'])),

                SelectFilter::make('project_type')
                    ->label(__('app.stores.project_type'))
                    ->options(ProjectType::options()),

                Filter::make('is_active')
                    ->label(__('app.stores.active_only'))
                    ->query(fn (Builder $query) => $query->where('is_active', true))
                    ->toggle(),

                TrashedFilter::make()
                    ->visible(fn () => auth()->user()->hasAnyRole(['super_admin', 'admin'])),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make()
                    ->visible(fn ($record) => auth()->user()->hasAnyRole(['super_admin', 'admin']) && ! $record->trashed()),
                RestoreAction::make()
                    ->visible(fn ($record) => auth()->user()->hasAnyRole(['super_admin', 'admin']) && $record->trashed()),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->visible(fn () => auth()->user()->hasAnyRole(['super_admin', 'admin'])),
                ]),
            ]);
    }
}