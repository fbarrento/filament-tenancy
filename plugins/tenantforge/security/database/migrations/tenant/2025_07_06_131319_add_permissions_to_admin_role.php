<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\Models\Role;
use TenantForge\Security\Enums\AuthGuard;
use TenantForge\Security\Enums\Role as RoleEnum;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Role::findOrCreate(
            name: RoleEnum::Owner->value,
            guardName: AuthGuard::Web->value,
        );

        Role::findOrCreate(
            name: RoleEnum::Billing->value,
            guardName: AuthGuard::Web->value,
        );

    }
};
