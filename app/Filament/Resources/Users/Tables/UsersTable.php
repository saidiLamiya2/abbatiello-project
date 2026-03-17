<?php

namespace App\Filament\Resources\Users\Tables;

use App\Enums\UserRole;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\RestoreAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(function (Builder $query) {
                $user = auth()->user();
                if ($user->hasRole('manager')) {
                    $query->where('store_id', $user->store_id);
                }

            })
            ->columns([
                TextColumn::make('brand.tag')
                    ->label(__('app.users.brand'))
                    ->badge()
                    ->color('primary')
                    ->sortable()
                    ->toggleable()
                    ->visible(fn () => auth()->user()->hasAnyRole(['super_admin', 'admin'])),

                TextColumn::make('store.name')
                    ->label(__('app.users.store'))
                    ->searchable()
                    ->sortable()
                    ->placeholder('—'),

                TextColumn::make('last_name')
                    ->label(__('app.users.last_name'))
                    ->searchable()
                    ->sortable(),

                TextColumn::make('first_name')
                    ->label(__('app.users.first_name'))
                    ->searchable()
                    ->sortable(),

                TextColumn::make('email')
                    ->label(__('app.users.email'))
                    ->searchable()
                    ->sortable()
                    ->copyable(),

                TextColumn::make('roles.name')
                    ->label(__('app.users.role'))
                    ->badge()
                    ->color(fn (string $state): string =>
                        UserRole::tryFrom($state)?->color() ?? 'gray'
                    ),

                IconColumn::make('is_active')
                    ->label(__('app.users.is_active'))
                    ->boolean(),

                IconColumn::make('is_work_stoppage')
                    ->label(__('app.users.stoppage_label'))
                    ->boolean()
                    ->trueColor('warning')
                    ->falseColor('gray')
                    ->toggleable(),

                TextColumn::make('hired_at')
                    ->label(__('app.users.hired_label'))
                    ->date('d/m/Y')
                    ->sortable()
                    ->placeholder('—')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('terminated_at')
                    ->label(__('app.users.terminated_label'))
                    ->date('d/m/Y')
                    ->sortable()
                    ->placeholder('—')
                    ->color('danger')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('brand_id')
                    ->label(__('app.users.brand'))
                    ->relationship('brand', 'name')
                    ->searchable()
                    ->preload()
                    ->visible(fn () => auth()->user()->hasRole('super_admin')),

                SelectFilter::make('store_id')
                    ->label(__('app.users.store'))
                    ->relationship('store', 'name')
                    ->searchable()
                    ->preload()
                    ->visible(fn () => auth()->user()->hasAnyRole(['super_admin', 'admin'])),

                SelectFilter::make('roles')
                    ->label(__('app.users.role'))
                    ->relationship('roles', 'name'),

                Filter::make('is_active')
                    ->label(__('app.users.active_only'))
                    ->query(fn (Builder $query) => $query->where('is_active', true))
                    ->toggle(),

                Filter::make('is_work_stoppage')
                    ->label(__('app.users.on_stoppage'))
                    ->query(fn (Builder $query) => $query->where('is_work_stoppage', true))
                    ->toggle(),

                Filter::make('terminated')
                    ->label(__('app.users.terminated_filter'))
                    ->query(fn (Builder $query) => $query->whereNotNull('terminated_at'))
                    ->toggle(),

                TrashedFilter::make(),
            ])
            ->actions([
                EditAction::make()
                    ->visible(fn ($record) => auth()->user()->can('update', $record)),
                DeleteAction::make()
                    ->visible(fn ($record) => auth()->user()->can('delete', $record)),
                RestoreAction::make()
                    ->visible(fn ($record) => $record->trashed() && auth()->user()->can('restore', $record)),
                            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->using(function ($records) {
                            $records->each(function ($record) {
                                if (auth()->user()->can('delete', $record)) {
                                    $record->delete();
                                }
                            });
                        }),
                ]),
            ]);
    }
}