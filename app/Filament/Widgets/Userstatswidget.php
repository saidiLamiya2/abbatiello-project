<?php

namespace App\Filament\Widgets;

use App\Enums\UserRole;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Database\Eloquent\Builder;

class UserStatsWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $user = auth()->user();

        // Base query scoped by role
        // - super_admin and admin: all users (no scoping)
        // - manager: only own store
        // - employee: not shown on dashboard (widget hidden)
        $baseQuery = fn () => User::query()
            ->when(
                $user->hasRole(UserRole::Manager->value),
                fn (Builder $q) => $q->where('store_id', $user->store_id)
            );

        $totalActive   = (clone $baseQuery())->where('is_active', true)->count();
        $totalInactive = (clone $baseQuery())->where('is_active', false)->count();

        $stats = [
            Stat::make(__('app.users.stats.total_active'), $totalActive)
                ->color('success'),

            Stat::make(__('app.users.stats.total_inactive'), $totalInactive)
                ->color('gray'),
        ];

        // Active managers card — only for super_admin and admin
        if ($user->hasAnyRole([UserRole::SuperAdmin->value, UserRole::Admin->value])) {
            $activeManagers = (clone $baseQuery())
                ->where('is_active', true)
                ->whereHas('roles', fn ($q) => $q->where('name', UserRole::Manager->value))
                ->count();

            $stats[] = Stat::make(__('app.users.stats.active_managers'), $activeManagers)
                ->color('warning');
        }

        return $stats;
    }
}