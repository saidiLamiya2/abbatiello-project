<?php

namespace App\Filament\Resources\Stores;

use App\Filament\Resources\Stores\Pages\CreateStore;
use App\Filament\Resources\Stores\Pages\EditStore;
use App\Filament\Resources\Stores\Pages\ListStores;
use App\Models\Store;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
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
use Illuminate\Database\Eloquent\SoftDeletingScope;

class StoreResource extends Resource
{
    protected static ?string $model = Store::class;
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-building-storefront';
    protected static ?int $navigationSort = 2;
    protected static ?string $recordTitleAttribute = 'name';

    public static function getModelLabel(): string { return __('app.stores.singular'); }
    public static function getPluralModelLabel(): string { return __('app.stores.title'); }
    public static function getNavigationGroup(): ?string { return __('app.nav.franchise'); }

    public static function canAccess(): bool
    {
        return auth()->user()->hasAnyRole(['super_admin', 'admin', 'manager']);
    }

    public static function form(Schema $form): Schema
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
                        ->options([
                            'Nouveau' => 'Nouveau',
                            'Corpo'   => 'Corpo',
                            'Reprise' => 'Reprise',
                            'Vente'   => 'Vente',
                        ])
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

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(function (Builder $query) {
                $user = auth()->user();
                if ($user->hasRole('manager')) {
                    $query->where('id', $user->store_id);
                }
                if ($user->hasRole('admin')) {
                    $query->where('brand_id', $user->brand_id);
                }
            })
            ->columns([
                TextColumn::make('brand.tag')
                    ->label(__('app.stores.brand'))
                    ->badge()
                    ->color('primary')
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
                    ->color(fn (?string $state): string => match ($state) {
                        'Nouveau' => 'success',
                        'Corpo'   => 'info',
                        'Reprise' => 'warning',
                        'Vente'   => 'danger',
                        default   => 'gray',
                    }),

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
                    ->options([
                        'Nouveau' => 'Nouveau',
                        'Corpo'   => 'Corpo',
                        'Reprise' => 'Reprise',
                        'Vente'   => 'Vente',
                    ]),

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

    public static function getPages(): array
    {
        return [
            'index'  => ListStores::route('/'),
            'create' => CreateStore::route('/create'),
            'edit'   => EditStore::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([SoftDeletingScope::class]);
    }
}