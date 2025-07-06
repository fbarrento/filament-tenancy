<?php

declare(strict_types=1);

namespace TenantForge\Security\Actions;

use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\URL;
use TenantForge\Security\Models\Invitation;
use TenantForge\Security\Notifications\InvitationNotification;

class SendInvitationNotificationAction
{
    /**
     * Send an invitation notification.
     *
     * @param  Invitation  $invitation  The invitation to send
     */
    public function handle(Invitation $invitation): void
    {
        // Generate the acceptance URL based on the invitation type
        $acceptUrl = $this->generateAcceptanceUrl($invitation);

        // Send the notification to the invited email
        Notification::route('mail', $invitation->email)
            ->notify(new InvitationNotification($invitation, $acceptUrl));
    }

    /**
     * Generate the URL for accepting an invitation.
     *
     * @param  Invitation  $invitation  The invitation
     * @return string The URL
     */
    protected function generateAcceptanceUrl(Invitation $invitation): string
    {
        // We'll generate a signed URL to prevent tampering
        return URL::signedRoute(
            'invitations.accept',
            ['token' => $invitation->token],
            $invitation->expires_at
        );
    }
}
