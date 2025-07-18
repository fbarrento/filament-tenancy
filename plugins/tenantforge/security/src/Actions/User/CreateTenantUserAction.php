<?php

declare(strict_types=1);

namespace TenantForge\Security\Actions\User;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use TenantForge\Security\Models\CentralUser;
use TenantForge\Security\Models\User;
use TenantForge\Tenancy\Models\Tenant;
use Throwable;

use function now;

final class CreateTenantUserAction
{
    public function handle(CentralUser $user, Tenant $tenant, string $role, ?bool $isVerified = null): ?User
    {

        $tenant->users()->save($user);

        return $tenant->run(/**
         * @throws Throwable
         */ function () use ($user, $role, $isVerified): ?User {
            return DB::transaction(function () use ($user, $role, $isVerified): ?User {

                $user = User::query()->createQuietly([
                    'global_id' => $user->global_id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'email_verified_at' => $isVerified ? now() : null,
                    'password' => Hash::make($user->password),
                ]);

                $user->assignRole($role);

                return $user;

            });

        });

    }
}
