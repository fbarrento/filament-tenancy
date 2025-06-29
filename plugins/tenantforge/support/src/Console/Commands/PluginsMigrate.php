<?php

declare(strict_types=1);

namespace TenantForge\Support\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;
use Symfony\Component\Finder\Finder;

use function config;

final class PluginsMigrate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'plugins:migrate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run all plugins migrations';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->runMigrations();
        $this->runTenantMigrations();

        return self::SUCCESS;
    }

    private function runTenantMigrations(): void
    {
        $this->info('Running Tenant migrations...');
        $finder = new Finder;
        $finder->directories()->in(__DIR__.'/../../../../*/database');
        $tenantMigrationPaths = [];
        foreach ($finder as $directory) {
            if (Str::contains($directory->getRealPath(), 'migrations/tenant')) {
                $tenantMigrationPaths[] = $directory->getRealPath();
            }
        }
        config()->set('tenancy.migration_parameters.--path', [
            ...config()->array('tenancy.migration_parameters.--path'),
            ...$tenantMigrationPaths,
        ]);
        Artisan::call('tenants:migrate');
        $this->line(Artisan::output());

    }

    private function runMigrations(): void
    {
        $this->info('Running Central migrations...');
        $finder = new Finder;
        $finder->directories()->in(__DIR__.'/../../../../*/database');

        foreach ($finder as $directory) {
            if (Str::contains($directory->getRealPath(), 'migrations')) {
                Artisan::call('migrate', [
                    '--path' => $directory->getRelativePath(),
                ]);
            }
        }

        $this->line(Artisan::output());
    }
}
