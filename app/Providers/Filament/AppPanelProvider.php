<?php

namespace App\Providers\Filament;

use App\Http\Middleware\SetAppUrlForTenant;
use App\Http\Middleware\TenantFileUrlMiddleware;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\Width;
use Filament\Widgets\AccountWidget;
use Filament\Widgets\FilamentInfoWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Stancl\Tenancy\Middleware\InitializeTenancyBySubdomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;
use TenantForge\Support\Livewire\DatabaseNotifications;

use function app_path;
use function tenancy;
use function tenant;

class AppPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('app')
            ->default(tenancy()->initialized)
            ->path('/app')
            ->colors([
                'primary' => Color::Amber,
            ])
            ->sidebarWidth('16rem')
            ->maxContentWidth(Width::Full)
            ->databaseNotifications()
            ->databaseNotificationsLivewireComponent(DatabaseNotifications::class)
            ->databaseNotificationsPolling('15s')
            ->discoverResources(in: app_path('Filament/Tenant/App/Resources'), for: 'App\\Filament\\Tenant\\App\\Resources')
            ->discoverPages(in: app_path('Filament/Tenant/App/Pages'), for: 'App\\Filament\\Tenant\\App\\Pages')
            ->discoverClusters(in: app_path('Filament/Tenant/Clusters'), for: 'App\\Filament\\Tenant\\Clusters')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Tenant/App/Widgets'), for: 'App\\Filament\\Tenant\\App\\Widgets')
            ->widgets([
                AccountWidget::class,
                FilamentInfoWidget::class,
            ])
            ->middleware([
                InitializeTenancyBySubdomain::class,
                SetAppUrlForTenant::class,
                TenantFileUrlMiddleware::class,
                PreventAccessFromCentralDomains::class,
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
            ])
            ->viteTheme(
                'resources/css/filament/theme.css',
            )
            ->brandName(fn (): string => tenant('short_name') ?? tenant('name'))
            ->favicon(fn (): string => tenant('avatar_url') ?? '');

    }
}
