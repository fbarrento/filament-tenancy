<?php

declare(strict_types=1);

namespace TenantForge\Security\Actions\Invitation;

use InvalidArgumentException;
use TenantForge\Security\Models\CentralUser;
use TenantForge\Security\Models\Invitation;
use TenantForge\Tenancy\Models\Tenant;

final class CreateInvitationAction
{
    public function __construct(
        private SendInvitationNotificationAction $sendInvitationNotificationAction,
    ) {}

    /**
     * Create a new invitation.
     *
     * @param  string  $email  The email address to invite
     * @param  CentralUser  $inviter  The user creating the invitation
     * @param  Tenant|null  $tenant  The tenant to invite the user to (null for central invitations)
     * @param  string|null  $role  Optional role to assign
     * @param  array|null  $metadata  Optional additional data
     * @param  int  $expiresInDays  Number of days until the invitation expires
     * @return Invitation The created invitation
     *
     * @throws InvalidArgumentException If an invitation already exists
     */
    public function handle(
        string $email,
        CentralUser $inviter,
        ?Tenant $tenant = null,
        ?string $role = null,
        ?array $metadata = null,
        int $expiresInDays = 7
    ): Invitation {
        // Validate if there's an existing pending invitation
        if ($tenant) {
            if (Invitation::hasPendingInvitationForTenant($email, $tenant)) {
                throw new \InvalidArgumentException("A pending invitation for {$email} to this tenant already exists.");
            }

            $invitation = Invitation::createForTenant($email, $tenant, $inviter, $role, $metadata, $expiresInDays);
        } else {
            if (Invitation::hasPendingInvitationForCentral($email)) {
                throw new \InvalidArgumentException("A pending invitation for {$email} to the central application already exists.");
            }

            $invitation = Invitation::createForCentral($email, $inviter, $role, $metadata, $expiresInDays);
        }

        $this->sendInvitationNotificationAction->handle($invitation);

        return $invitation;
    }
}
