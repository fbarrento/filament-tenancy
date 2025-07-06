<?php

declare(strict_types=1);

namespace TenantForge\Security\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum InvitationStatus: string implements HasColor, HasLabel
{
    case PENDING = 'pending';
    case ACCEPTED = 'accepted';
    case EXPIRED = 'expired';
    case REVOKED = 'revoked';

    /**
     * Get the label for the enum value.
     */
    public function getLabel(): ?string
    {
        return match ($this) {
            self::PENDING => 'Pending',
            self::ACCEPTED => 'Accepted',
            self::EXPIRED => 'Expired',
            self::REVOKED => 'Revoked',
        };
    }

    /**
     * Get color for badge or status indicator.
     */
    public function getColor(): ?string
    {
        return match ($this) {
            self::PENDING => 'warning',
            self::ACCEPTED => 'success',
            self::EXPIRED, self::REVOKED => 'danger',
        };
    }

    /**
     * Check if the invitation is in a final state (can't be changed anymore).
     */
    public function isFinal(): bool
    {
        return match ($this) {
            self::ACCEPTED, self::EXPIRED, self::REVOKED => true,
            self::PENDING => false,
        };
    }

    /**
     * Check if the invitation is still pending.
     */
    public function isPending(): bool
    {
        return $this === self::PENDING;
    }

    /**
     * Check if the invitation has been accepted.
     */
    public function isAccepted(): bool
    {
        return $this === self::ACCEPTED;
    }

    /**
     * Check if the invitation has expired.
     */
    public function isExpired(): bool
    {
        return $this === self::EXPIRED;
    }

    /**
     * Check if the invitation has been revoked.
     */
    public function isRevoked(): bool
    {
        return $this === self::REVOKED;
    }
}
