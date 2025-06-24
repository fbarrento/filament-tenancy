<?php

namespace App\Livewire;

use App\Models\CentralUser;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Illuminate\View\View;
use Livewire\Component;

use function auth;
use function tenancy;
use function tenant_route;

class TenantSwitcher extends Component
{
    public $tenants = [];

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

    }

    public function switchTenant(Tenant $tenant): RedirectResponse|Redirector
    {

        $user = $tenant->run(fn () => User::query()->where('global_id', auth()->user()->global_id)->first());
        $redirectUrl = tenant_route($tenant->domains->first()->domain, 'filament.app.pages.dashboard');
        $token = tenancy()->impersonate($tenant, $user->id, $redirectUrl);

        return redirect()->away(tenant_route($tenant->domains->first()->domain, 'impersonate', ['token' => $token]));
    }

    public function render(): View
    {
        return view('livewire.tenant-switcher', [
            'tenants' => $this->tenants,
        ]);
    }
}
