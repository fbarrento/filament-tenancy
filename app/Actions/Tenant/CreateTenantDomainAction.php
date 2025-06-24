<?php

declare(strict_types=1);

namespace App\Actions\Tenant;

use App\Models\Tenant;
use Illuminate\Support\Facades\DB;
use Throwable;

final class CreateTenantDomainAction
{
    /**
     * @throws Throwable
     */
    public function handle(Tenant $tenant, string $subdomain): void
    {

        DB::transaction(function () use ($tenant, $subdomain) {
            $tenant->domains()->create([
                'domain' => $subdomain,
            ]);
        });

    }
}
