<?php

namespace App\Filament\Resources\Themes\Schemas;

use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ThemeForm
{
    public static function configure(Schema $form): Schema
    {
        return $form->schema([

            Section::make(__('app.themes.identity'))
                ->schema([
                    TextInput::make('name')
                        ->label(__('app.themes.name_label'))
                        ->required()
                        ->maxLength(100)
                        ->placeholder(__('app.themes.name_placeholder')),
                ]),

            Section::make(__('app.themes.colors'))
                ->columns(2)
                ->schema([
                    ColorPicker::make('primary_color')
                        ->label(__('app.themes.primary_color'))
                        ->required()
                        ->helperText(__('app.themes.primary_helper')),

                    ColorPicker::make('secondary_color')
                        ->label(__('app.themes.secondary_color'))
                        ->required(),
                ]),

            Section::make(__('app.themes.typography'))
                ->columns(2)
                ->schema([
                    TextInput::make('font_family')
                        ->label(__('app.themes.font_family'))
                        ->required()
                        ->default('Inter')
                        ->placeholder('Inter, Poppins, Roboto…'),

                    Select::make('filament_color')
                        ->label(__('app.themes.filament_palette'))
                        ->required()
                        ->default('rose')
                        ->options([
                            'slate'  => 'Slate',  'gray'   => 'Gray',   'zinc'   => 'Zinc',
                            'red'    => 'Red',    'orange' => 'Orange', 'amber'  => 'Amber',
                            'yellow' => 'Yellow', 'lime'   => 'Lime',   'green'  => 'Green',
                            'teal'   => 'Teal',   'cyan'   => 'Cyan',   'sky'    => 'Sky',
                            'blue'   => 'Blue',   'indigo' => 'Indigo', 'violet' => 'Violet',
                            'purple' => 'Purple', 'rose'   => 'Rose',
                        ])
                        ->helperText(__('app.themes.palette_helper')),
                ]),
        ]);
    }
}