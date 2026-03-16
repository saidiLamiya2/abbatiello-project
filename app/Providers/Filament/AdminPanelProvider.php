<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages\Dashboard;
use Filament\View\PanelsRenderHook;
use App\Filament\Pages\Auth\Login;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Illuminate\Support\Facades\Blade;
use Filament\Widgets\AccountWidget;
use Filament\Widgets\FilamentInfoWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;


class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->viteTheme('resources/css/filament/admin/theme.css')
            ->login(Login::class)
            ->font('Montserrat', provider: \Filament\FontProviders\GoogleFontProvider::class)
            ->brandLogo(asset('storage/brands/logos/logo_abbatiello_black.png'))          // light mode
            ->darkModeBrandLogo(asset('storage/brands/logos/groupe_abbatiello_logo.png')) // dark mode
            ->brandLogoHeight('2.5rem')
            ->colors([
                'primary' => Color::Amber,
            ])
            ->renderHook(
                PanelsRenderHook::HEAD_END,
                fn () => '
                <style>
                    .fi-sc-component {
                        display: flex !important;
                        flex-direction: column !important;
                    }
                    .fi-sc-section {
                        flex: 1 !important;
                        height: 100% !important;
                    }
                </style>
                ',
            )
            ->renderHook(
                PanelsRenderHook::USER_MENU_BEFORE,
                fn () => Blade::render('@livewire(\'locale-switcher\')'),
            )
            ->renderHook(
                PanelsRenderHook::USER_MENU_BEFORE,
                fn () => Blade::render('
                    <form method="POST" action="' . route('filament.admin.auth.logout') . '" style="display:inline;">
                        <input type="hidden" name="_token" value="' . csrf_token() . '">
                        <button type="submit" style="
                            display: inline-flex;
                            align-items: center;
                            gap: 0.4rem;
                            padding: 0.4rem 0.85rem;
                            background: transparent;
                            border: 1px solid color-mix(in srgb, currentColor 30%, transparent);
                            border-radius: 6px;
                            color: inherit;
                            font-size: 0.7rem;
                            font-weight: 500;
                            letter-spacing: 0.08em;
                            cursor: pointer;
                            font-family: Montserrat, sans-serif;
                            opacity: 0.7;
                            transition: opacity 0.2s;
                        "
                        onmouseover="this.style.opacity=\'1\'"
                        onmouseout="this.style.opacity=\'0.7\'">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                                <path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4"/>
                                <polyline points="16 17 21 12 16 7"/>
                                <line x1="21" y1="12" x2="9" y2="12"/>
                            </svg>
                            ' . __('app.nav.logout') . '
                        </button>
                    </form>
                '),
            )
            ->sidebarCollapsibleOnDesktop()
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->pages([
                \App\Filament\Pages\Dashboard::class,
                \App\Filament\Pages\MyInformations::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->widgets([
                AccountWidget::class,
                FilamentInfoWidget::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
                \App\Http\Middleware\SetLocale::class,
            ]);
    }
}
