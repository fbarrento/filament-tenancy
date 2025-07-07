<?php

declare(strict_types=1);

namespace App\Jobs;

use Illuminate\Support\Facades\Storage;
use TenantForge\Tenancy\Models\Tenant;

use function info;
use function storage_path;

final readonly class DeleteFrameworkDirectoriesForTenant
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
            if (Storage::directoryExists($storagePath)) {
                info('Tenant directories deleted', [
                    'folder' => $storagePath,
                ]);
                Storage::deleteDirectory($storagePath);
            }

        });
    }
}
