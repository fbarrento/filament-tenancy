<?php

declare(strict_types=1);

namespace TenantForge\Security\Filament\Pages\Auth\EmailVerification;

use DanHarrin\LivewireRateLimiting\Exceptions\TooManyRequestsException;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Pages\Auth\EmailVerification\EmailVerificationPrompt as BaseEmailVerificationPrompt;
use Livewire\Attributes\Layout;

use function __;

#[Layout('components.layouts.auth')]
final class EmailVerificationPrompt extends BaseEmailVerificationPrompt
{
    protected static string $view = 'tenantforge:security::filament.auth.email-verification.email-verification-prompt';

    public function resendNotificationAction(): Action
    {
        return Action::make('resendNotification')
            ->link()
            ->label(__('filament-panels::pages/auth/email-verification/email-verification-prompt.actions.resend_notification.label').'.')
            ->action(function (): void {
                try {
                    $this->rateLimit(2);
                } catch (TooManyRequestsException $exception) {
                    $this->getRateLimitedNotification($exception)?->send();

                    return;
                }

                $this->getVerifiable()->sendEmailVerificationNotification();

                Notification::make()
                    ->title(__('filament-panels::pages/auth/email-verification/email-verification-prompt.notifications.notification_resent.title'))
                    ->success()
                    ->send();
            });
    }
}
