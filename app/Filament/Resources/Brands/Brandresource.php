<?php

namespace App\Filament\Resources\Brands;

use App\Filament\Resources\Brands\Pages\CreateBrand;
use App\Filament\Resources\Brands\Pages\EditBrand;
use App\Filament\Resources\Brands\Pages\ListBrands;
use App\Models\Brand;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\KeyValue;
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
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BrandResource extends Resource
{
    protected static ?string $model = Brand::class;
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-building-office-2';
    protected static ?int $navigationSort = 1;
    protected static ?string $recordTitleAttribute = 'name';

    public static function getModelLabel(): string { return __('app.brands.singular'); }
    public static function getPluralModelLabel(): string { return __('app.brands.title'); }
    public static function getNavigationGroup(): ?string { return __('app.nav.franchise'); }

    public static function canAccess(): bool
    {
        return auth()->user()->hasAnyRole(['super_admin', 'admin']);
    }

    public static function form(Schema $form): Schema
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

    public static function table(Table $table): Table
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
                    ->sortable()
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
            'index'  => ListBrands::route('/'),
            'create' => CreateBrand::route('/create'),
            'edit'   => EditBrand::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([SoftDeletingScope::class]);
    }
}