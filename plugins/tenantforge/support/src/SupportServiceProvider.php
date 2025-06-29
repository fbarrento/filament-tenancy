<?php

declare(strict_types=1);

namespace TenantForge\Support;

use Illuminate\Support\ServiceProvider;
use TenantForge\Support\Console\Commands\PluginsMigrate;

final class SupportServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {

        $this->commands([
            PluginsMigrate::class,
        ]);
    }
}
