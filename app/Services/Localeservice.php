<?php

namespace App\Services;

use App\Enums\UserLocale;
use App\Models\User;
use Illuminate\Support\Facades\App;

class LocaleService
{
    /**
     * Switch the locale for the given user.
     */
    public function switchFor(User $user, string $locale): void
    {
        if (! UserLocale::tryFrom($locale)) {
            return;
        }

        $user->update(['locale' => $locale]);

        App::setLocale($locale);
    }

    /**
     * Resolve the active locale for the given user.
     */
    public function resolveFor(?User $user): string
    {
        if ($user && UserLocale::tryFrom($user->locale)) {
            return $user->locale;
        }

        return config('app.locale', UserLocale::French->value);
    }
}