<?php

declare(strict_types=1);

namespace TenantForge\Security\Filament\Pages\Auth;

use Filament\Pages\Auth\Register as BaseRegister;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.auth')]
final class Register extends BaseRegister
{
    protected static string $view = 'tenantforge:security::filament.auth.register';
}
