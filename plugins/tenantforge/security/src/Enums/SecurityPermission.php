<?php

declare(strict_types=1);

namespace TenantForge\Security\Enums;

enum SecurityPermission: string
{
    case AccessAdminPanel = 'access admin panel';
    case CreateUsers = 'create users';
    case ViewUsers = 'view users';
    case EditUsers = 'edit users';
    case DeleteUsers = 'delete users';
    case ViewUser = 'view user';
    case EditUser = 'edit user';
    case DeleteUser = 'delete user';
    case InviteUser = 'invite user';
    case ViewInvites = 'view invites';
    case DeleteInvites = 'delete invites';
    case ResendInvites = 'resend invites';

    case RevokeInvites = 'revoke invites';

    case CreateTenants = 'create tenants';
    case ViewTenants = 'view tenants';
    case EditTenants = 'edit tenants';

}
