<?php

declare(strict_types=1);

namespace TenantForge\Support\Enums;

enum TenantForgePanel: string
{
    case Admin = 'admin';
    case App = 'app';
    case Guest = 'guest';

}
