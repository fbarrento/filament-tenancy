<?php

namespace App\Livewire;

use App\Models\Tenant;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Illuminate\View\View;
use Livewire\Component;
use TenantForge\Security\Enums\SecurityPermission;
use TenantForge\Security\Models\CentralUser;
use TenantForge\Tenancy\Actions\CreateTenantSwitcherRouteAction;

use function auth;

class TenantSwitcher extends Component
{
    public $tenants = [];

    public bool $canAccessAdminPanel = false;

    protected CreateTenantSwitcherRouteAction $createTenantSwitcherRoute;

    public function boot(
        CreateTenantSwitcherRouteAction $createTenantSwitcherRoute
    ): void {

        $this->createTenantSwitcherRoute = $createTenantSwitcherRoute;

    }

    public function mount(): void
    {

        $user = CentralUser::query()
            ->where('global_id', auth()->user()->global_id)
            ->with(['tenants' => function (BelongsToMany $query) {
                $query->whereNotIn('tenant_id', [\tenant()->id]);
            }])
            ->first();

        $this->tenants = $user->tenants->map(function (Tenant $tenant) {
            return [
                'name' => $tenant->name,
                'id' => $tenant->id,
            ];
        });

        $this->canAccessAdminPanel = $user->can(SecurityPermission::AccessAdminPanel);

    }

    public function switchTenant(Tenant $tenant): RedirectResponse|Redirector
    {

        return redirect()->away(
            $this->createTenantSwitcherRoute->handle($tenant, auth()->user()->global_id)
        );
    }

    public function render(): View
    {
        return view('livewire.tenant-switcher', [
            'tenants' => $this->tenants,
        ]);
    }
}
