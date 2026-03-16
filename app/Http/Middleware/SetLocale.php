<?php

namespace App\Http\Middleware;

use App\Services\LocaleService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    public function __construct(private readonly LocaleService $localeService) {}

    public function handle(Request $request, Closure $next): Response
    {
        App::setLocale(
            $this->localeService->resolveFor(Auth::user())
        );

        return $next($request);
    }
}