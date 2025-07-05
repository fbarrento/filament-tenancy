<?php

namespace TenantForge\Security\Policies;

use TenantForge\Security\Enums\SecurityPermission;
use TenantForge\Security\Models\CentralUser;

class CentralUserPolicy
{
    public function viewAny(CentralUser $centralUser): bool
    {
        return $centralUser->can(SecurityPermission::ViewUsers->value);
    }

    public function create(CentralUser $centralUser): bool
    {
        return false;
    }

    public function update(CentralUser $centralUser, CentralUser $user): bool
    {
        return $centralUser->can(SecurityPermission::EditUsers->value) || $centralUser->id === $user->id;
    }
}
