<?php

namespace TenantForge\Security\Filament\Pages\Auth;

use Filament\Facades\Filament;
use Filament\Http\Responses\Auth\Contracts\LoginResponse;
use Filament\Pages\Auth\Login as BaseLogin;
use Livewire\Attributes\Layout;

use function tenancy;

#[Layout('components.layouts.auth')]
class Login extends BaseLogin
{
    protected static string $view = 'tenantforge:security::filament.auth.login';

    public function authenticate(): ?LoginResponse
    {
        $panel = tenancy()->initialized ? 'app' : 'admin';
        Filament::setCurrentPanel(Filament::getPanel($panel));

        return parent::authenticate();
    }
}
