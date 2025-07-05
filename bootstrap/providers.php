<?php

return [
    App\Providers\AppServiceProvider::class,
    App\Providers\Filament\AdminPanelProvider::class,
    App\Providers\Filament\AppPanelProvider::class,
    App\Providers\TenancyServiceProvider::class,
    TenantForge\Security\SecurityServiceProvider::class,
    TenantForge\Support\SupportServiceProvider::class,
    TenantForge\Tenancy\TenancyServiceProvider::class,
];
