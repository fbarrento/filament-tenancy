<?php

declare(strict_types=1);

namespace TenantForge\Security;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use TenantForge\Security\Enums\Role;
use TenantForge\Security\Models\CentralUser;
use TenantForge\Security\Models\User;
use TenantForge\Security\Policies\CentralUserPolicy;

final class SecurityServiceProvider extends ServiceProvider
{
    protected static string $controllerNamespace = '';

    public function boot(): void
    {
        $this->registerMigrations();
        $this->mapCentralRoutes();
        $this->mapUniversalRoutes();
        $this->mapTenantRoutes();
        $this->registerViews();
        $this->configureSuperAdmin();
        $this->configurePolicies();
    }

    protected function configurePolicies(): void
    {
        Gate::policy(CentralUser::class, CentralUserPolicy::class);
    }

    protected function configureSuperAdmin(): void
    {
        Gate::before(function (CentralUser|User $user, $ability) {
            return $user->hasRole(Role::SuperAdmin->value) ? true : null;
        });
    }

    protected function registerViews(): void
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'tenantforge:security');
    }

    protected function registerMigrations(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
    }

    protected function mapTenantRoutes(): void
    {

        $this->app->booted(function () {
            if (file_exists(__DIR__.'/../routes/tenant.php')) {
                Route::namespace(self::$controllerNamespace)
                    ->group(__DIR__.'/../routes/tenant.php');
            }
        });

    }

    protected function mapUniversalRoutes(): void
    {
        $this->app->booted(function () {
            if (file_exists(__DIR__.'/../routes/universal.php')) {
                Route::middleware(['web', 'universal'])
                    ->namespace(self::$controllerNamespace)
                    ->group(__DIR__.'/../routes/universal.php');
            }
        });
    }

    protected function mapCentralRoutes(): void
    {
        foreach ($this->centralDomains() as $domain) {
            Route::middleware('web')
                ->domain($domain)
                ->namespace(self::$controllerNamespace)
                ->group(__DIR__.'/../routes/web.php');
        }
    }

    private function centralDomains(): array
    {
        return config('tenancy.central_domains');
    }
}
