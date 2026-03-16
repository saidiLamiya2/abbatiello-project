<?php

namespace App\Filament\Resources\Stores\Schemas;

use App\Enums\ProjectType;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class StoreForm
{
    public static function configure(Schema $form): Schema
    {
        return $form
            ->columns(2)
            ->schema([

                Section::make(__('app.stores.identity'))
                    ->columnSpan(1)
                    ->extraAttributes(['class' => 'h-full'])
                    ->columns(2)
                    ->schema([
                        Select::make('brand_id')
                            ->label(__('app.stores.brand'))
                            ->relationship('brand', 'name')
                            ->required()
                            ->searchable()
                            ->preload()
                            ->columnSpan(1)
                            ->disabled(fn () => auth()->user()->hasRole('manager')),

                        TextInput::make('franchise_number')
                            ->label(__('app.stores.franchise_number'))
                            ->nullable()
                            ->maxLength(50)
                            ->columnSpan(1),

                        TextInput::make('name')
                            ->label(__('app.stores.name_label'))
                            ->required()
                            ->maxLength(255)
                            ->columnSpan(1)
                            ->live(debounce: 500)
                            ->afterStateUpdated(function ($state, callable $set, $record) {
                                if (! $record) {
                                    $set('slug', \Illuminate\Support\Str::slug($state));
                                }
                            }),

                        TextInput::make('slug')
                            ->label('Slug')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true)
                            ->columnSpan(1)
                            ->helperText(__('app.stores.slug_helper')),
                    ]),

                Section::make(__('app.stores.location'))
                    ->columnSpan(1)
                    ->extraAttributes(['class' => 'h-full'])
                    ->columns(2)
                    ->schema([
                        TextInput::make('address')
                            ->label(__('app.stores.address'))
                            ->nullable()
                            ->columnSpan(2),

                        TextInput::make('city')
                            ->label(__('app.stores.city'))
                            ->required()
                            ->columnSpan(1)
                            ->helperText(__('app.stores.city_helper')),

                        TextInput::make('province')
                            ->label(__('app.stores.province'))
                            ->nullable()
                            ->maxLength(10)
                            ->columnSpan(1),

                        TextInput::make('postal_code')
                            ->label(__('app.stores.postal_code'))
                            ->nullable()
                            ->maxLength(10)
                            ->columnSpan(1),

                        TextInput::make('phone')
                            ->label(__('app.stores.phone'))
                            ->tel()
                            ->nullable()
                            ->maxLength(20)
                            ->columnSpan(1),

                        TextInput::make('email')
                            ->label(__('app.stores.email'))
                            ->email()
                            ->nullable()
                            ->maxLength(255)
                            ->columnSpan(2),
                    ]),

                Section::make(__('app.stores.local_branding'))
                    ->columnSpan(1)
                    ->extraAttributes(['class' => 'h-full'])
                    ->description(__('app.stores.local_description'))
                    ->columns(3)
                    ->schema([
                        ColorPicker::make('primary_color')
                            ->label(__('app.stores.primary_color')),

                        ColorPicker::make('secondary_color')
                            ->label(__('app.stores.secondary_color')),

                        FileUpload::make('logo')
                            ->label(__('app.stores.local_logo'))
                            ->image()
                            ->disk('public')
                            ->directory('stores/logos')
                            ->visibility('public')
                            ->maxSize(2048)
                            ->helperText(__('app.stores.local_logo_helper')),
                    ]),

                Section::make(__('app.stores.hierarchy'))
                    ->columnSpan(1)
                    ->extraAttributes(['class' => 'h-full'])
                    ->schema([
                        Select::make('core_store_id')
                            ->label(__('app.stores.parent_store'))
                            ->relationship('parent', 'name')
                            ->searchable()
                            ->preload()
                            ->nullable()
                            ->helperText(__('app.stores.parent_helper')),
                    ]),

                Section::make(__('app.stores.project_status'))
                    ->columnSpan(1)
                    ->extraAttributes(['class' => 'h-full'])
                    ->columns(2)
                    ->schema([
                        Select::make('project_type')
                            ->label(__('app.stores.project_type'))
                            ->options(ProjectType::options())
                            ->nullable()
                            ->columnSpan(1),

                        Toggle::make('is_active')
                            ->label(__('app.stores.is_active'))
                            ->default(false)
                            ->inline(false)
                            ->columnSpan(1)
                            ->helperText(__('app.stores.is_active_helper')),

                        DatePicker::make('start_date')
                            ->label(__('app.stores.start_date'))
                            ->nullable()
                            ->displayFormat('d/m/Y')
                            ->columnSpan(1),

                        DatePicker::make('expected_opening_date')
                            ->label(__('app.stores.opening_date'))
                            ->nullable()
                            ->displayFormat('d/m/Y')
                            ->afterOrEqual('start_date')
                            ->columnSpan(1),
                    ]),
            ]);
    }
}