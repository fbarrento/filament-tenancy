<?php

namespace App\Filament\Admin\Resources\TenantResource\Pages;

use App\Actions\Tenant\CreateTenantDomainAction;
use App\Actions\Tenant\CreateTenantUserAction;
use App\Filament\Admin\Resources\TenantResource;
use App\Models\Tenant;
use Filament\Resources\Pages\CreateRecord;
use Throwable;

use function auth;

class CreateTenant extends CreateRecord
{
    protected static string $resource = TenantResource::class;

    /**
     * @throws Throwable
     */
    protected function afterCreate(): void
    {

        /** @var CreateTenantDomainAction $createTenantDomainAction */
        $createTenantDomainAction = app(CreateTenantDomainAction::class);
        /** @var CreateTenantUserAction $createTenantUserAction */
        $createTenantUserAction = app(CreateTenantUserAction::class);

        /** @var Tenant $tenant */
        $tenant = $this->getRecord();

        $createTenantDomainAction->handle($tenant, $this->data['domain']);
        $createTenantUserAction->handle($tenant, auth()->user());

    }
}
