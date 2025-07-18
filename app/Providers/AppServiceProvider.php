<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use function class_exists;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {

        $this->configureTelescope();

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void {}

    protected function configureTelescope(): void
    {
        if ($this->app->environment('local') && class_exists(TelescopeServiceProvider::class)) {
            $this->app->register(\Laravel\Telescope\TelescopeServiceProvider::class);
            $this->app->register(TelescopeServiceProvider::class);
        }
    }
}
