<?php

namespace TenantForge\Security\Filament\Pages\Auth;

use DanHarrin\LivewireRateLimiting\Exceptions\TooManyRequestsException;
use Filament\Facades\Filament;
use Filament\Http\Responses\Auth\Contracts\LoginResponse;
use Filament\Models\Contracts\FilamentUser;
use Filament\Pages\Auth\Login as BaseLogin;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use TenantForge\Security\Enums\AuthGuard;
use TenantForge\Security\Http\Responses\LoginTenantResponse;
use TenantForge\Security\Models\CentralUser;
use TenantForge\Tenancy\Actions\CreateTenantSwitcherRouteAction;

use function app;
use function session;

#[Layout('components.layouts.auth')]
class Login extends BaseLogin
{
    protected static string $view = 'tenantforge:security::filament.auth.login';

    private CreateTenantSwitcherRouteAction $createTenantSwitcherAction;

    public function boot(CreateTenantSwitcherRouteAction $createTenantSwitcherAction): void
    {
        $this->createTenantSwitcherAction = $createTenantSwitcherAction;
    }

    public function authenticate(): ?LoginResponse
    {
        /*try {
            $this->rateLimit(5);
        } catch (TooManyRequestsException $exception) {
            $this->getRateLimitedNotification($exception)?->send();

            return null;
        }*/

        $data = $this->form->getState();

        if (! Auth::guard(AuthGuard::Central->value)->attempt($this->getCredentialsFromFormData($data), $data['remember'] ?? false)) {
            $this->throwFailureValidationException();
        }

        /** @var CentralUser $user */
        $user = Auth::guard(AuthGuard::Central->value)->user();

        // If the user belongs to at least 1 tenant, we should redirect the user to the first tenant.

        if ($user->tenants->count() > 1) {
            return app(LoginTenantResponse::class)
                ->user($user)
                ->tenant($user->tenants->first());

        }

        if (
            ($user instanceof FilamentUser) &&
            (! $user->canAccessPanel(Filament::getCurrentPanel()))
        ) {
            Filament::auth()->logout();

            $this->throwFailureValidationException();
        }

        session()->regenerate();

        return app(LoginResponse::class);
    }
}
