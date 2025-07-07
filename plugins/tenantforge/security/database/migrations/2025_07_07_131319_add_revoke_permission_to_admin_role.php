<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use TenantForge\Security\Enums\AuthGuard;
use TenantForge\Security\Enums\SecurityPermission;
use TenantForge\Security\Enums\SecurityRole as RoleEnum;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {

        $admin = Role::findOrCreate(
            name: RoleEnum::Admin->value,
            guardName: AuthGuard::Central->value,
        );

        Permission::findOrCreate(
            name: SecurityPermission::RevokeInvites->value,
            guardName: AuthGuard::Central->value,
        );

        $admin->givePermissionTo(SecurityPermission::RevokeInvites);
    }
};
