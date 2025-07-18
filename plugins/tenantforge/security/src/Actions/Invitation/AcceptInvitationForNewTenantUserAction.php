<?php

declare(strict_types=1);

namespace TenantForge\Security\Actions\Invitation;

use Illuminate\Support\Facades\DB;
use TenantForge\Security\Actions\User\CreateCentraUserAction;
use TenantForge\Security\Actions\User\CreateTenantUserAction;
use TenantForge\Security\DataObjects\NewUserData;
use TenantForge\Security\Enums\SecurityRole;
use TenantForge\Security\Models\Invitation;
use TenantForge\Security\Models\User;
use Throwable;

final readonly class AcceptInvitationForNewTenantUserAction
{
    public function __construct(
        private CreateCentraUserAction $createCentraUserAction,
        private CreateTenantUserAction $createTenantUserAction,
    ) {}

    /**
     * @throws Throwable
     */
    public function handle(
        NewUserData $data,
        Invitation $invitation,
        string|SecurityRole $tenantRole,
        string|SecurityRole $role = SecurityRole::Guest
    ): ?User {

        return DB::transaction(function () use ($data, $invitation, $role, $tenantRole): ?User {

            $centralUser = $this->createCentraUserAction->handle(
                name: $data->name,
                email: $data->email,
                password: $data->password,
                role: $role,
                isVerified: $data->emailVerifiedAt ?? false
            );
            $user = $this->createTenantUserAction->handle(
                user: $centralUser,
                tenant: $invitation->tenant,
                role: $tenantRole
            );

            $invitation->markAsAccepted();

            return $user;

        });

    }
}
