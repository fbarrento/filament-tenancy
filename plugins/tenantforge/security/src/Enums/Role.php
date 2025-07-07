<?php

declare(strict_types=1);

namespace TenantForge\Security\Enums;

enum Role: string
{
    case Admin = 'admin';
    case SuperAdmin = 'super-admin';
    case Guest = 'guest';

    case Owner = 'owner';
    case Billing = 'billing';

}
