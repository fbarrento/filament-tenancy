<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use TenantForge\Security\Enums\AuthGuard;
use TenantForge\Security\Enums\Role as RoleEnum;
use TenantForge\Security\Enums\SecurityPermission;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {

        $superAdmin = Role::findOrCreate(
            name: RoleEnum::SuperAdmin->value,
            guardName: AuthGuard::Central->value,
        );
        $admin = Role::findOrCreate(
            name: RoleEnum::Admin->value,
            guardName: AuthGuard::Central->value,
        );
        $guest = Role::findOrCreate(
            name: RoleEnum::Guest->value,
            guardName: AuthGuard::Central->value,
        );

        $accessAdminPanel = Permission::findOrCreate(
            name: SecurityPermission::AccessAdminPanel->value,
            guardName: AuthGuard::Central->value,
        );

        $accessAdminPanel->assignRole($admin);

    }
};
