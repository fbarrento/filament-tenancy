<?php

declare(strict_types=1);

namespace TenantForge\Security\Enums;

use Filament\Support\Contracts\HasLabel;

enum InvitationType: string implements HasLabel
{
    case CENTRAL = 'central';
    case TENANT = 'tenant';

    /**
     * Get the label for the enum value.
     */
    public function getLabel(): ?string
    {
        return match ($this) {
            self::CENTRAL => 'Central Application',
            self::TENANT => 'Tenant Organization',
        };
    }

    /**
     * Check if the invitation is for the central application.
     */
    public function isCentral(): bool
    {
        return $this === self::CENTRAL;
    }

    /**
     * Check if the invitation is for a tenant.
     */
    public function isTenant(): bool
    {
        return $this === self::TENANT;
    }
}
