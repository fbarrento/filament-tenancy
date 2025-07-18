<?php

declare(strict_types=1);

namespace TenantForge\Security\Actions\User;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use TenantForge\Security\Enums\SecurityRole;
use TenantForge\Security\Models\CentralUser;
use Throwable;

final readonly class CreateCentraUserAction
{
    public function __construct(
        private CreateTenantUserAction $createTenantUserAction,
    ) {}

    /**
     * @throws Throwable
     */
    public function handle(string $name, string $email, string $password, string|SecurityRole $role, ?bool $isVerified = null): ?CentralUser
    {

        return DB::transaction(function () use ($name, $email, $password, $role, $isVerified): ?CentralUser {
            $user = CentralUser::query()->create([
                'global_id' => Str::uuid(),
                'name' => $name,
                'email' => $email,
                'email_verified_at' => $isVerified ? now() : null,
                'password' => Hash::make($password),
            ]);

            $user->assignRole($role);

            return $user;

        });

    }
}
