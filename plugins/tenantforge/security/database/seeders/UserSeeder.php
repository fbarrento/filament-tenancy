<?php

namespace TenantForge\Security\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use TenantForge\Security\Enums\SecurityRole;
use TenantForge\Security\Models\CentralUser;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = CentralUser::query()->firstOrCreate(
            attributes: [
                'email' => 'admin@example.com',
            ],
            values: [
                'global_id' => Str::uuid()->toString(),
                'name' => 'Admin User',
                'password' => Hash::make('password'),
            ]);

        $superAdmin = CentralUser::query()->firstOrCreate(
            attributes: [
                'email' => 'super_admin@example.com', ],
            values: [
                'global_id' => Str::uuid()->toString(),
                'name' => 'Super Admin User',
                'password' => Hash::make('password'),
            ]);

        $guest = CentralUser::query()->firstOrCreate(
            attributes: ['email' => 'guest@example.com'],
            values: [
                'global_id' => Str::uuid()->toString(),
                'name' => 'Guest User',
                'email' => 'guest@example.com',
                'password' => Hash::make('password'),
            ]);

        $admin->assignRole(SecurityRole::Admin);
        $superAdmin->assignRole(SecurityRole::SuperAdmin);
        $guest->assignRole(SecurityRole::Guest);
    }
}
