<?php

namespace App\Filament\Resources\Themes;

use App\Filament\Resources\Themes\Pages\CreateTheme;
use App\Filament\Resources\Themes\Pages\EditTheme;
use App\Filament\Resources\Themes\Pages\ListThemes;
use App\Models\Theme;
use Filament\Forms\Components\ColorPicker;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\RestoreAction;
use Filament\Tables\Columns\ColorColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ThemeResource extends Resource
{
    protected static ?string $model = Theme::class;
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-swatch';
    protected static ?int $navigationSort = 1;

    public static function getModelLabel(): string { return __('app.themes.singular'); }
    public static function getPluralModelLabel(): string { return __('app.themes.title'); }
    public static function getNavigationGroup(): ?string { return __('app.nav.system'); }

    public static function canAccess(): bool
    {
        return auth()->user()->hasRole('super_admin');
    }

    public static function form(Schema $form): Schema
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

    public static function table(Table $table): Table
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
                DeleteAction::make(),
                RestoreAction::make()
                    ->visible(fn ($record) => $record->trashed()),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListThemes::route('/'),
            'create' => CreateTheme::route('/create'),
            'edit'   => EditTheme::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([SoftDeletingScope::class]);
    }
}