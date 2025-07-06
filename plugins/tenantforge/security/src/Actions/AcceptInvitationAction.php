<?php

declare(strict_types=1);

namespace TenantForge\Security\Actions;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use InvalidArgumentException;
use TenantForge\Security\Enums\InvitationStatus;
use TenantForge\Security\Enums\InvitationType;
use TenantForge\Security\Models\CentralUser;
use TenantForge\Security\Models\Invitation;

class AcceptInvitationAction
{
    /**
     * Accept an invitation.
     *
     * @param  string  $token  The invitation token
     * @param  CentralUser  $user  The user accepting the invitation
     * @return Invitation The accepted invitation
     *
     * @throws InvalidArgumentException If the invitation is invalid or expired
     * @throws ModelNotFoundException If the invitation is not found
     */
    public function handle(string $token, CentralUser $user): Invitation
    {
        $invitation = Invitation::findByToken($token);

        if (! $invitation) {
            throw new ModelNotFoundException('Invitation not found.');
        }

        // Update status based on expiration if needed
        $invitation->updateStatus();

        // Check if the invitation is still valid
        if ($invitation->status !== InvitationStatus::PENDING) {
            throw new InvalidArgumentException('This invitation is no longer valid.');
        }

        // Verify the email matches
        if ($invitation->email !== $user->email) {
            throw new InvalidArgumentException('This invitation is not for your email address.');
        }

        // Handle the invitation based on type
        if ($invitation->type === InvitationType::TENANT) {
            $this->handleTenantInvitation($invitation, $user);
        } else {
            $this->handleCentralInvitation($invitation, $user);
        }

        // Mark the invitation as accepted
        return $invitation->markAsAccepted();
    }

    /**
     * Handle a tenant invitation acceptance.
     */
    protected function handleTenantInvitation(Invitation $invitation, CentralUser $user): void
    {
        // Get the tenant from the invitation
        $tenant = $invitation->tenant;

        if (! $tenant) {
            throw new \RuntimeException('Tenant not found for this invitation.');
        }

        // Associate the user with the tenant
        $tenant->users()->syncWithoutDetaching([
            $user->global_id => ['role' => $invitation->role],
        ]);
    }

    /**
     * Handle a central invitation acceptance.
     */
    protected function handleCentralInvitation(Invitation $invitation, CentralUser $user): void
    {
        // Assign the role to the user if provided
        if ($invitation->role) {
            $user->assignRole($invitation->role);
            $user->save();
        }
    }
}
