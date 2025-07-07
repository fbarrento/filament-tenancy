<?php

namespace TenantForge\Security\Policies;

use TenantForge\Security\Enums\InvitationStatus;
use TenantForge\Security\Enums\SecurityPermission;
use TenantForge\Security\Models\CentralUser;
use TenantForge\Security\Models\Invitation;
use TenantForge\Security\Models\User;

class InvitationPolicy
{
    public function viewAny(CentralUser|User $user): bool
    {
        return $user->can(SecurityPermission::ViewInvites);
    }

    public function create(CentralUser|User $user): bool
    {
        return $user->can(SecurityPermission::InviteUser);
    }

    public function delete(CentralUser|User $user): bool
    {
        return $user->can(SecurityPermission::DeleteInvites);
    }

    public function revoke(CentralUser|User $user, Invitation $invitation): bool
    {
        return $user->can(SecurityPermission::RevokeInvites);
    }

    public function resend(CentralUser|User $user, Invitation $invitation): bool
    {
        return $user->can(SecurityPermission::ResendInvites) && $invitation->status !== InvitationStatus::ACCEPTED;
    }
}
