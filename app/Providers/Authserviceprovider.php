<?php

namespace App\Providers;

use App\Models\Brand;
use App\Models\Store;
use App\Models\Theme;
use App\Models\User;
use App\Policies\BrandPolicy;
use App\Policies\StorePolicy;
use App\Policies\ThemePolicy;
use App\Policies\UserPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Brand::class => BrandPolicy::class,
        Store::class => StorePolicy::class,
        Theme::class => ThemePolicy::class,
        User::class  => UserPolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();

        // super_admin bypasses ALL Gate checks — no permissions needed.
        Gate::before(function (User $user, string $ability): ?bool {
            if ($user->hasRole('super_admin')) {
                return true;
            }

            return null; // Fall through to policy
        });
    }
}