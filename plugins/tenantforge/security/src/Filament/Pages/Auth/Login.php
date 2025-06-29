<?php

namespace TenantForge\Security\Filament\Pages\Auth;

use Filament\Pages\Auth\Login as BaseLogin;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.auth')]
class Login extends BaseLogin
{
    protected static string $view = 'tenantforge:security::filament.auth.login';
}
