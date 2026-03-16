<?php

namespace App\Filament\Resources\Themes;

use App\Filament\Resources\Themes\Pages\CreateTheme;
use App\Filament\Resources\Themes\Pages\EditTheme;
use App\Filament\Resources\Themes\Pages\ListThemes;
use App\Filament\Resources\Themes\Schemas\ThemeForm;
use App\Filament\Resources\Themes\Tables\ThemesTable;
use App\Models\Theme;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Gate;

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
        return Gate::allows('viewAny', static::$model);
    }

    public static function form(Schema $form): Schema
    {
        return ThemeForm::configure($form);
    }

    public static function table(Table $table): Table
    {
        return ThemesTable::configure($table);
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