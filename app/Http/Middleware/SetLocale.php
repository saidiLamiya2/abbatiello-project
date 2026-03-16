<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        $locale = config('app.locale', 'fr');

        // Try session first (set after user switches locale)
        try {
            if ($request->hasSession() && $request->session()->has('locale')) {
                $sessionLocale = $request->session()->get('locale');
                if (in_array($sessionLocale, ['fr', 'en'])) {
                    $locale = $sessionLocale;
                }
            }
        } catch (\RuntimeException $e) {
            // Session not yet available — fall through to DB
        }

        // DB value overrides session (source of truth)
        if (Auth::check()) {
            $userLocale = Auth::user()->locale;
            if (in_array($userLocale, ['fr', 'en'])) {
                $locale = $userLocale;
            }
        }

        App::setLocale($locale);

        return $next($request);
    }
}