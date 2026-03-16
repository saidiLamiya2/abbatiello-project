<?php

namespace App\Filament\Resources\Brands\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class BrandForm
{
    public static function configure(Schema $form): Schema
    {
        return $form
            ->columns(2)
            ->schema([

                Section::make(__('app.brands.identity'))
                    ->columnSpan(1)
                    ->schema([
                        TextInput::make('name')
                            ->label(__('app.brands.name_label'))
                            ->required()
                            ->maxLength(255),

                        TextInput::make('tag')
                            ->label(__('app.brands.tag'))
                            ->required()
                            ->maxLength(10)
                            ->unique(ignoreRecord: true)
                            ->helperText(__('app.brands.tag_helper'))
                            ->extraAttributes(['style' => 'text-transform: uppercase']),
                    ]),

                Section::make(__('app.brands.visual_assets'))
                    ->columnSpan(1)
                    ->columns(2)
                    ->schema([
                        FileUpload::make('logo')
                            ->label(__('app.brands.logo'))
                            ->image()
                            ->disk('public')
                            ->directory('brands/logos')
                            ->visibility('public')
                            ->maxSize(2048)
                            ->acceptedFileTypes(['image/png', 'image/jpeg', 'image/svg+xml'])
                            ->helperText(__('app.brands.logo_helper')),

                        FileUpload::make('favicon')
                            ->label(__('app.brands.favicon'))
                            ->image()
                            ->disk('public')
                            ->directory('brands/favicons')
                            ->visibility('public')
                            ->maxSize(512)
                            ->acceptedFileTypes(['image/png', 'image/x-icon', 'image/svg+xml'])
                            ->helperText(__('app.brands.favicon_helper')),
                    ]),

                Section::make(__('app.brands.design_section'))
                    ->columnSpan(1)
                    ->description(__('app.brands.design_description'))
                    ->schema([
                        KeyValue::make('design_config')
                            ->label(__('app.brands.design_props'))
                            ->keyLabel(__('app.brands.design_key'))
                            ->valueLabel(__('app.brands.design_value'))
                            ->addButtonLabel(__('app.brands.design_add'))
                            ->reorderable()
                            ->helperText(__('app.brands.design_helper')),
                    ]),

                Section::make(__('app.brands.theme_section'))
                    ->columnSpan(1)
                    ->description(__('app.brands.theme_description'))
                    ->schema([
                        Select::make('theme_id')
                            ->label(__('app.brands.theme'))
                            ->relationship('theme', 'name')
                            ->searchable()
                            ->preload()
                            ->nullable(),
                    ]),

                Section::make(__('app.brands.communication'))
                    ->columnSpan(1)
                    ->description(__('app.brands.comm_description'))
                    ->schema([
                        TextInput::make('email_from_address')
                            ->label(__('app.brands.email_from_address'))
                            ->email()
                            ->maxLength(255)
                            ->placeholder('noreply@mabrand.com'),

                        TextInput::make('email_from_name')
                            ->label(__('app.brands.email_from_name'))
                            ->maxLength(255),

                        TextInput::make('sms_phone_number')
                            ->label(__('app.brands.sms_phone'))
                            ->tel()
                            ->maxLength(20)
                            ->placeholder('+15141234567'),
                    ]),

                Section::make(__('app.brands.links'))
                    ->columnSpan(1)
                    ->schema([
                        KeyValue::make('links')
                            ->label(__('app.brands.links_label'))
                            ->keyLabel(__('app.brands.links_key'))
                            ->valueLabel(__('app.brands.links_value'))
                            ->addButtonLabel(__('app.brands.links_add'))
                            ->helperText(__('app.brands.links_helper')),
                    ]),
            ]);
    }
}