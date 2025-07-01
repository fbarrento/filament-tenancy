<?php

return [
    App\Providers\AppServiceProvider::class,
    App\Providers\TenancyServiceProvider::class,
    App\Providers\Filament\AdminPanelProvider::class,
    App\Providers\Filament\AppPanelProvider::class,
    TenantForge\Support\SupportServiceProvider::class,
    TenantForge\Security\SecurityServiceProvider::class,

];
