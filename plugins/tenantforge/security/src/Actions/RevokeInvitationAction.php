<?php

declare(strict_types=1);

namespace TenantForge\Security\Actions;

use InvalidArgumentException;
use TenantForge\Security\Enums\InvitationStatus;
use TenantForge\Security\Enums\SecurityPermission;
use TenantForge\Security\Models\CentralUser;
use TenantForge\Security\Models\Invitation;
use TenantForge\Security\Models\User;

class RevokeInvitationAction
{
    /**
     * Revoke an invitation.
     *
     * @param  Invitation  $invitation  The invitation to revoke
     * @param  CentralUser|User  $user  The user performing the revocation
     * @return Invitation The revoked invitation
     *
     * @throws InvalidArgumentException If the invitation cannot be revoked
     */
    public function handle(Invitation $invitation, CentralUser|User $user): Invitation
    {
        // Ensure the invitation is in a state that can be revoked
        if ($invitation->status !== InvitationStatus::PENDING) {
            throw new InvalidArgumentException('Only pending invitations can be revoked.');
        }

        if ($user->cannot(SecurityPermission::RevokeInvites)) {
            throw new InvalidArgumentException('You do not have permission to revoke this invitation.');
        }

        return $invitation->markAsRevoked();

    }
}
