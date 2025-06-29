<?php

declare(strict_types=1);

namespace TenantForge\Security\Filament\Pages\Auth\EmailVerification;

use Filament\Pages\Auth\EmailVerification\EmailVerificationPrompt as BaseEmailVerificationPrompt;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.auth')]
final class EmailVerificationPrompt extends BaseEmailVerificationPrompt
{
    protected static string $view = 'tenantforge:security::filament.auth.email-verification.email-verification-prompt';
}
