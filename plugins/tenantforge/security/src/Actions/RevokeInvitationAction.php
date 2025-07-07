<?php

declare(strict_types=1);

namespace TenantForge\Security\Actions;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use InvalidArgumentException;
use TenantForge\Security\Enums\InvitationStatus;
use TenantForge\Security\Models\CentralUser;
use TenantForge\Security\Models\Invitation;

class RevokeInvitationAction
{
    /**
     * Revoke an invitation.
     *
     * @param  Invitation  $invitation  The invitation to revoke
     * @param  CentralUser  $user  The user performing the revocation
     * @return Invitation The revoked invitation
     *
     * @throws InvalidArgumentException If the invitation cannot be revoked
     */
    public function handle(Invitation $invitation, CentralUser $user): Invitation
    {
        // Ensure the invitation is in a state that can be revoked
        if ($invitation->status !== InvitationStatus::PENDING) {
            throw new InvalidArgumentException('Only pending invitations can be revoked.');
        }

        // For tenant invitations, ensure the user has permission to the tenant
        if ($invitation->tenant_id) {
            $tenant = $invitation->tenant;

            if (! $tenant) {
                throw new \RuntimeException('Tenant not found for this invitation.');
            }

            // Check if the user is an admin of this tenant
            if (! $user->belongsToTenant($tenant->id) && ! $user->isAdmin()) {
                throw new InvalidArgumentException('You do not have permission to revoke this invitation.');
            }
        } else {
            // For central invitations, ensure the user has admin permissions
            if (! $user->isAdmin()) {
                throw new InvalidArgumentException('You do not have permission to revoke this invitation.');
            }
        }

        // Mark the invitation as revoked
        return $invitation->markAsRevoked();
    }

    /**
     * Revoke an invitation by its ID.
     *
     * @param  string  $invitationId  The ID of the invitation to revoke
     * @param  CentralUser  $user  The user performing the revocation
     * @return Invitation The revoked invitation
     *
     * @throws ModelNotFoundException If the invitation is not found
     * @throws InvalidArgumentException If the invitation cannot be revoked
     */
    public function handleById(string $invitationId, CentralUser $user): Invitation
    {
        $invitation = Invitation::findOrFail($invitationId);

        return $this->handle($invitation, $user);
    }
}
