<x-tenantforge:security::layouts.auth>
    <x-slot:heading>{{__('Verify your email')}}</x-slot:heading>
    <p class="text-sm text-gray-500 dark:text-gray-400">
        {{
            __('filament-panels::pages/auth/email-verification/email-verification-prompt.messages.notification_sent', [
                'email' => filament()->auth()->user()->getEmailForVerification(),
            ])
        }}
    </p>

    <p class="text-sm text-gray-500 dark:text-gray-400">
        {{ __('filament-panels::pages/auth/email-verification/email-verification-prompt.messages.notification_not_received') }}

        {{ $this->resendNotificationAction }}
    </p>
</x-tenantforge:security::layouts.auth>
