<?php

namespace App\Filament\Resources\Users;

use App\Filament\Resources\Users\Pages\CreateUser;
use App\Filament\Resources\Users\Pages\EditUser;
use App\Filament\Resources\Users\Pages\ListUsers;
use App\Models\User;
use Filament\Forms\Components\DatePicker;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Actions\RestoreAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Spatie\Permission\Models\Role;

class UserResource extends Resource
{
    protected static ?string $model = User::class;
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-users';
    protected static ?int $navigationSort = 3;
    protected static ?string $recordTitleAttribute = 'name';

    public static function getModelLabel(): string { return __('app.users.singular'); }
    public static function getPluralModelLabel(): string { return __('app.users.title'); }
    public static function getNavigationGroup(): ?string { return __('app.nav.franchise'); }

    public static function canAccess(): bool
    {
        return auth()->user()->hasAnyRole(['super_admin', 'admin', 'manager']);
    }

    public static function canDelete(\Illuminate\Database\Eloquent\Model $record): bool
    {
        return auth()->user()->hasAnyRole(['super_admin', 'admin']);
    }

    public static function canDeleteAny(): bool
    {
        return auth()->user()->hasAnyRole(['super_admin', 'admin']);
    }

    public static function form(Schema $form): Schema
    {
        return $form->schema([

            Section::make(__('app.users.assignment'))
                ->columns(2)
                ->schema([
                    Select::make('brand_id')
                        ->label(__('app.users.brand'))
                        ->required()
                        ->live()
                        ->default(fn () => auth()->user()->hasRole('manager')
                            ? auth()->user()->brand_id
                            : null
                        )
                        ->dehydrated(true)
                        ->options(function () {
                            if (auth()->user()->hasRole('manager')) {
                                return \App\Models\Brand::where('id', auth()->user()->brand_id)
                                    ->pluck('name', 'id');
                            }
                            return \App\Models\Brand::pluck('name', 'id');
                        })
                        ->afterStateUpdated(fn (callable $set) => $set('store_id', null)),

                    Select::make('store_id')
                        ->label(__('app.users.store'))
                        ->required()
                        ->default(fn () => auth()->user()->hasRole('manager')
                            ? auth()->user()->store_id
                            : null
                        )
                        ->options(function (callable $get) {
                            if (auth()->user()->hasRole('manager')) {
                                return \App\Models\Store::where('id', auth()->user()->store_id)
                                    ->pluck('name', 'id');
                            }
                            $brandId = $get('brand_id');
                            return \App\Models\Store::when($brandId, fn ($q) => $q->where('brand_id', $brandId))
                                ->pluck('name', 'id');
                        })
                        ->searchable()
                        ->preload(),
                ]),

            Section::make(__('app.users.identity'))
                ->columns(2)
                ->schema([
                    TextInput::make('first_name')
                        ->label(__('app.users.first_name'))
                        ->required()
                        ->maxLength(100),

                    TextInput::make('last_name')
                        ->label(__('app.users.last_name'))
                        ->required()
                        ->maxLength(100),

                    TextInput::make('email')
                        ->label(__('app.users.email'))
                        ->email()
                        ->required()
                        ->unique(ignoreRecord: true)
                        ->maxLength(255),

                    TextInput::make('user_code')
                        ->label(__('app.users.user_code'))
                        ->nullable()
                        ->maxLength(50),

                    Select::make('locale')
                        ->label(__('app.users.locale'))
                        ->options([
                            'fr' => 'Français',
                            'en' => 'English',
                        ])
                        ->default('fr')
                        ->required(),

                    DatePicker::make('birth_date')
                        ->label(__('app.users.birth_date'))
                        ->nullable()
                        ->displayFormat('d/m/Y')
                        ->maxDate(now()->subYears(16)),
                ]),

            Section::make(__('app.users.role_section'))
                ->schema([
                    Select::make('role')
                        ->label(__('app.users.role'))
                        ->options(function () {
                            $user = auth()->user();
                            if ($user->hasRole('super_admin')) {
                                return Role::pluck('name', 'name');
                            }
                            if ($user->hasRole('admin')) {
                                return Role::whereNotIn('name', ['super_admin'])->pluck('name', 'name');
                            }
                            return Role::where('name', 'employee')->pluck('name', 'name');
                        })
                        ->required()
                        ->dehydrated(false)
                        ->helperText(__('app.users.role_helper'))
                        ->afterStateHydrated(function ($state, $record, callable $set) {
                            if ($record) {
                                $set('role', $record->roles->first()?->name);
                            }
                        }),
                ]),

            Section::make(__('app.users.employment'))
                ->columns(2)
                ->schema([
                    Toggle::make('is_active')
                        ->label(__('app.users.is_active'))
                        ->default(true)
                        ->inline(false)
                        ->helperText(__('app.users.is_active_helper')),

                    DatePicker::make('hired_at')
                        ->label(__('app.users.hired_at'))
                        ->nullable()
                        ->displayFormat('d/m/Y'),

                    DatePicker::make('terminated_at')
                        ->label(__('app.users.terminated_at'))
                        ->nullable()
                        ->displayFormat('d/m/Y')
                        ->live()
                        ->afterOrEqual('hired_at'),

                    TextInput::make('termination_reason')
                        ->label(__('app.users.termination_reason'))
                        ->nullable()
                        ->maxLength(255)
                        ->visible(fn (callable $get) => filled($get('terminated_at')))
                        ->helperText(__('app.users.termination_helper')),
                ]),

            Section::make(__('app.users.stoppage_section'))
                ->schema([
                    Toggle::make('is_work_stoppage')
                        ->label(__('app.users.work_stoppage'))
                        ->default(false)
                        ->live()
                        ->inline(false),

                    DatePicker::make('work_stoppage_start_date')
                        ->label(__('app.users.stoppage_start'))
                        ->nullable()
                        ->displayFormat('d/m/Y')
                        ->visible(fn (callable $get) => $get('is_work_stoppage')),

                    DatePicker::make('work_stoppage_end_date')
                        ->label(__('app.users.stoppage_end'))
                        ->nullable()
                        ->displayFormat('d/m/Y')
                        ->afterOrEqual('work_stoppage_start_date')
                        ->visible(fn (callable $get) => $get('is_work_stoppage'))
                        ->helperText(__('app.users.stoppage_helper')),
                ]),

            Section::make(__('app.users.access'))
                ->schema([
                    TextInput::make('password')
                        ->label(__('app.users.password'))
                        ->password()
                        ->dehydrateStateUsing(fn ($state) => filled($state) ? bcrypt($state) : null)
                        ->dehydrated(fn ($state) => filled($state))
                        ->required(fn (string $operation) => $operation === 'create')
                        ->maxLength(255)
                        ->helperText(__('app.users.password_helper')),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(function (Builder $query) {
                $user = auth()->user();
                if ($user->hasRole('manager')) {
                    $query->where('store_id', $user->store_id);
                }
                if ($user->hasRole('admin')) {
                    $query->where('brand_id', $user->brand_id);
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
                    ->color(fn (string $state): string => match ($state) {
                        'super_admin' => 'danger',
                        'admin'       => 'warning',
                        'manager'     => 'info',
                        'employee'    => 'gray',
                        default       => 'gray',
                    }),

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
                EditAction::make(),
                DeleteAction::make()
                    ->visible(fn ($record) => auth()->user()->hasAnyRole(['super_admin', 'admin']) && ! $record->trashed()),
                RestoreAction::make()
                    ->visible(fn ($record) => auth()->user()->hasAnyRole(['super_admin', 'admin']) && $record->trashed()),
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
            'index'  => ListUsers::route('/'),
            'create' => CreateUser::route('/create'),
            'edit'   => EditUser::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                \Illuminate\Database\Eloquent\SoftDeletingScope::class,
            ]);
    }
}