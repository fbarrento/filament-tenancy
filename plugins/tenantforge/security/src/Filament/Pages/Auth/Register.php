<?php

declare(strict_types=1);

namespace TenantForge\Security\Filament\Pages\Auth;

use Exception;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Model;
use Livewire\Attributes\Layout;

use function method_exists;

#[Layout('components.layouts.auth')]
final class Register extends \Filament\Auth\Pages\Register
{
    protected string $view = 'tenantforge:security::filament.auth.register';

    protected function sendEmailVerificationNotification(Model $user): void
    {
        if (! $user instanceof MustVerifyEmail) {
            return;
        }

        if ($user->hasVerifiedEmail()) {
            return;
        }

        if (! method_exists($user, 'notify')) {
            $userClass = $user::class;

            throw new Exception("Model [{$userClass}] does not have a [notify()] method.");
        }

        $user->sendEmailVerificationNotification();

    }
}
