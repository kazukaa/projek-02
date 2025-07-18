<?php

namespace App\Providers\Filament;

// Namespace yang benar untuk Widget berdasarkan perbaikan kita sebelumnya
use App\Filament\App\Widgets\MyStatsOverview;
use App\Filament\App\Widgets\MyActivityChart;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AppPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('app')
            ->path('app')
            ->login()
            ->registration()
            ->passwordReset()
            ->emailVerification()
            ->profile()
            ->colors([
                'primary' => Color::Indigo,
            ])
            ->brandLogo(asset('images/terang.png'))
            ->darkModeBrandLogo(asset('images/logo.png'))
            ->brandName('Workout Kost')
            ->brandLogoHeight('4rem')
            ->discoverResources(in: app_path('Filament/App/Resources'), for: 'App\\Filament\\App\\Resources')
            ->discoverPages(in: app_path('Filament/App/Pages'), for: 'App\\Filament\\App\\Pages')
            ->pages([
                Pages\Dashboard::class,
                \App\Filament\App\Pages\MyAchievements::class
            ])
            ->widgets([
                MyStatsOverview::class,
                MyActivityChart::class,
            ])
            ->renderHook(
                'panels::body.end',
                fn() => view('filament.hooks.speak-script')
            )
            ->renderHook(
                'panels::body.end',
                fn() => view('filament.hooks.timer-component')
            )
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
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
