<?php

declare(strict_types=1);

namespace TenantForge\Tenancy\Actions;

use const PHP_URL_HOST;

use App\Models\Tenant;
use TenantForge\Security\Models\User;

use function config;
use function parse_url;
use function tenancy;
use function tenant_route;

final class CreateTenantSwitcherRouteAction
{
    public function handle(Tenant $tenant, string $userGlobalId): string
    {

        $user = $tenant->run(fn () => User::query()->where('global_id', $userGlobalId)->first());
        $hostname = parse_url(config()->string('app.url'), PHP_URL_HOST);
        $domain = $tenant->domains->first()->domain.'.'.$hostname;

        $redirectUrl = tenant_route($domain, 'filament.app.pages.dashboard');
        $token = tenancy()->impersonate($tenant, $user->id, $redirectUrl);

        return tenant_route($domain, 'impersonate', ['token' => $token]);

    }
}
