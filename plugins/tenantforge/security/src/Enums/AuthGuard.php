<?php

declare(strict_types=1);

namespace TenantForge\Security\Enums;

enum AuthGuard: string
{
    case Web = 'web';
    case Api = 'api';
    case Central = 'central';

}
