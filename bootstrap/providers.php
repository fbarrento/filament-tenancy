<?php

return [
    App\Providers\AppServiceProvider::class,
    App\Providers\TenancyServiceProvider::class,
    TenantForge\Support\SupportServiceProvider::class,
    TenantForge\Security\SecurityServiceProvider::class,
    App\Providers\Filament\AdminPanelProvider::class,
    App\Providers\Filament\AppPanelProvider::class,

];
