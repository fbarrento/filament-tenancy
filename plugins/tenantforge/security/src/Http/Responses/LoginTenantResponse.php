<?php

namespace TenantForge\Security\Http\Responses;

use Filament\Auth\Http\Responses\LoginResponse;
use Illuminate\Http\RedirectResponse;
use Livewire\Features\SupportRedirects\Redirector;
use TenantForge\Security\Models\CentralUser;
use TenantForge\Security\Models\User;
use TenantForge\Tenancy\Actions\CreateTenantSwitcherRouteAction;
use TenantForge\Tenancy\Models\Tenant;

class LoginTenantResponse extends LoginResponse
{
    protected CentralUser|User $user;

    protected Tenant $tenant;

    public function __construct(
        protected CreateTenantSwitcherRouteAction $createTenantSwitcherRoute
    ) {}

    public function tenant(Tenant $tenant): self
    {
        $this->tenant = $tenant;

        return $this;
    }

    public function user(User|CentralUser $user): self
    {
        $this->user = $user;

        return $this;

    }

    public function toResponse($request): RedirectResponse|Redirector
    {

        return redirect()->away(
            $this->createTenantSwitcherRoute->handle($this->tenant, $this->user->global_id)
        );
    }
}
