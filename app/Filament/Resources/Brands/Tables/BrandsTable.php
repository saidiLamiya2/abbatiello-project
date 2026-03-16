<?php

namespace App\Filament\Resources\Brands\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class BrandsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('logo')
                    ->label('')
                    ->circular()
                    ->disk('public')
                    ->defaultImageUrl(fn () => asset('images/placeholder-brand.png')),

                TextColumn::make('name')
                    ->label(__('app.brands.name'))
                    ->searchable()
                    ->sortable(),

                TextColumn::make('tag')
                    ->label(__('app.brands.tag'))
                    ->badge()
                    ->color('primary'),

                TextColumn::make('theme.name')
                    ->label(__('app.brands.theme'))
                    ->placeholder('—')
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('stores_count')
                    ->label(__('app.brands.restaurants'))
                    ->counts('stores')
                    ->sortable(),

                TextColumn::make('users_count')
                    ->label(__('app.brands.users_count'))
                    ->counts('users')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('email_from_address')
                    ->label(__('app.brands.email_from_address'))
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('created_at')
                    ->label(__('app.brands.created_at'))
                    ->dateTime('d/m/Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}