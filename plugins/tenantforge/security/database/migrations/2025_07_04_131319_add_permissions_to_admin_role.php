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
        // Get the admin role
        $admin = Role::findByName(
            name: RoleEnum::Admin->value,
            guardName: AuthGuard::Central->value,
        );

        // Admin permissions for user management
        $this->assignPermissionsToRole($admin, [
            SecurityPermission::ViewUsers->value,
            SecurityPermission::ViewUser->value,
            SecurityPermission::CreateUsers->value,
            SecurityPermission::EditUsers->value,
            SecurityPermission::EditUser->value,
            SecurityPermission::DeleteUsers->value,
            SecurityPermission::DeleteUser->value,
            SecurityPermission::InviteUser->value,
            SecurityPermission::ViewInvites->value,
            SecurityPermission::DeleteInvites->value,
            SecurityPermission::ResendInvites->value,
        ]);

        // Admin permissions for tenant management
        $this->assignPermissionsToRole($admin, [
            SecurityPermission::ViewTenants->value,
            SecurityPermission::EditTenants->value,
        ]);
    }

    /**
     * Helper method to assign permissions to a role.
     */
    private function assignPermissionsToRole(Role $role, array $permissionNames): void
    {
        foreach ($permissionNames as $permissionName) {
            $permission = Permission::findOrCreate(
                name: $permissionName,
                guardName: AuthGuard::Central->value,
            );

            $role->givePermissionTo($permission);
        }
    }
};
