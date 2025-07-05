<?php

declare(strict_types=1);

namespace TenantForge\Security;

use ReflectionClass;
use Exception;
use Filament\Contracts\Plugin;
use Filament\Panel;
use TenantForge\Support\Enums\TenantForgePanel;

final class SecurityPlugin implements Plugin
{
    public function getId(): string
    {
        return 'security';
    }

    public static function make(): self
    {
        return app(self::class);
    }

    /**
     * @throws Exception
     */
    public function register(Panel $panel): void
    {
        $panel->when($panel->getId() === TenantForgePanel::Admin->value, function (Panel $panel) {

            $panel
                ->discoverResources(in: $this->getPluginBatePath('/Filament/Admin/Resources'), for: 'TenantForge\\Security\\Filament\\Admin\\Resources')
                ->discoverPages(in: $this->getPluginBatePath('/Filament/Admin/Pages'), for: 'TenantForge\\Security\\Filament\\Admin\\Pages')
                ->discoverPages(in: $this->getPluginBatePath('/Filament/Pages'), for: 'TenantForge\\Security\\Filament\\Pages')
                ->discoverClusters(in: $this->getPluginBatePath('/Filament/Admin/Clusters'), for: 'TenantForge\\Security\\Filament\\Admin\\Clusters')
                ->discoverWidgets(in: $this->getPluginBatePath('/Filament/Admin/Widgets'), for: 'TenantForge\\Security\\Filament\\Admin\\Widgets');

        });
    }

    public function boot(Panel $panel): void
    {
        // TODO: Implement boot() method.
    }

    protected function getPluginBatePath(?string $path = null): string
    {
        $reflector = new ReflectionClass(get_class($this));

        return dirname($reflector->getFileName()).($path ?? '');
    }
}
