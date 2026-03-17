<?php

namespace App\Filament\Resources\Users\Schemas;

use App\Enums\UserLocale;
use App\Enums\UserRole;
use Filament\Forms\Components\DatePicker;use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Spatie\Permission\Models\Role;

class UserForm
{
    public static function configure(Schema $form): Schema
    {
        return $form->schema([

            Section::make(__('app.users.assignment'))
                ->columns(2)
                ->schema([
                    Select::make('brand_id')
                        ->label(__('app.users.brand'))
                        ->required(fn (callable $get): bool => in_array(
                            $get('role'),
                            [UserRole::Manager->value, UserRole::Employee->value]
                        ))
                        ->live()
                        ->default(fn () => auth()->user()->hasRole(UserRole::Manager->value)
                            ? auth()->user()->brand_id
                            : null
                        )
                        ->dehydrated(true)
                        ->disabled(fn () => auth()->user()->hasRole(UserRole::Manager->value))
                        ->options(function () {
                            $user = auth()->user();
                            if ($user->hasRole(UserRole::Manager->value)) {
                                return \App\Models\Brand::where('id', $user->brand_id)->pluck('name', 'id');
                            }
                            return \App\Models\Brand::pluck('name', 'id');
                        })
                        ->afterStateUpdated(fn (callable $set) => $set('store_id', null)),

                    Select::make('store_id')
                        ->label(__('app.users.store'))
                        ->required(fn (callable $get): bool => in_array(
                            $get('role'),
                            [UserRole::Manager->value, UserRole::Employee->value]
                        ))
                        ->default(fn () => auth()->user()->hasRole(UserRole::Manager->value)
                            ? auth()->user()->store_id
                            : null
                        )
                        ->disabled(fn () => auth()->user()->hasRole(UserRole::Manager->value))
                        ->options(function (callable $get) {
                            $user = auth()->user();
                            if ($user->hasRole(UserRole::Manager->value)) {
                                return \App\Models\Store::where('id', $user->store_id)->pluck('name', 'id');
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
                        ->options(UserLocale::options())
                        ->default(UserLocale::French->value)
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
                            if ($user->hasRole(UserRole::SuperAdmin->value)) {
                                return UserRole::options();
                            }
                            if ($user->hasRole(UserRole::Admin->value)) {
                                return UserRole::optionsExcluding(UserRole::SuperAdmin);
                            }
                            return UserRole::optionsExcluding(UserRole::SuperAdmin, UserRole::Admin, UserRole::Manager);
                        })
                        ->required()
                        ->live()
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
}