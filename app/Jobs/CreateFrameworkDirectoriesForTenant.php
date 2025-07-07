<?php

declare(strict_types=1);

namespace App\Jobs;

use TenantForge\Tenancy\Models\Tenant;

use function is_dir;
use function mkdir;
use function public_path;
use function storage_path;
use function symlink;

final readonly class CreateFrameworkDirectoriesForTenant
{
    public function __construct(
        protected Tenant $tenant
    ) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->tenant->run(function (Tenant $tenant): void {
            $storagePath = storage_path();

            $suffixBase = config()->string('tenancy.filesystem.suffix_base');

            if (! is_dir(public_path($suffixBase))) {
                @mkdir(public_path($suffixBase), 0777, true);
            }

            if (! is_dir($storagePath)) {
                @mkdir("$storagePath/app/public", 0777, true);
                @mkdir("$storagePath/framework/cache", 0777, true);

                symlink("$storagePath/app/public", public_path("$suffixBase{$tenant->id}"));
            }

        });
    }
}
