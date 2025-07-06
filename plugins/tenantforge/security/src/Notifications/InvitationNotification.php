<?php

declare(strict_types=1);

namespace TenantForge\Security\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use TenantForge\Security\Models\Invitation;

class InvitationNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        protected Invitation $invitation,
        protected string $acceptUrl
    ) {}

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $inviterName = $this->invitation->inviter?->name ?? 'Someone';
        $organizationName = $this->invitation->type->isTenant()
            ? $this->invitation->tenant?->name ?? 'an organization'
            : config('app.name');

        $roleName = $this->invitation->role
            ? " as a {$this->invitation->role}"
            : '';

        return (new MailMessage)
            ->subject("Invitation to join {$organizationName}")
            ->greeting('Hello!')
            ->line("{$inviterName} has invited you to join {$organizationName}{$roleName}.")
            ->line("This invitation will expire on {$this->invitation->expires_at->format('F j, Y')}.")
            ->action('Accept Invitation', $this->acceptUrl)
            ->line('If you do not have an account yet, you will be able to create one.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'invitation_id' => $this->invitation->id,
            'type' => $this->invitation->type->value,
            'tenant_id' => $this->invitation->tenant_id,
            'inviter_id' => $this->invitation->inviter_id,
            'role' => $this->invitation->role,
        ];
    }
}
