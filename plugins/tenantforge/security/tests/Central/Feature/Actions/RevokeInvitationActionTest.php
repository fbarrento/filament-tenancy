<?php

use Spatie\Permission\Models\Permission;
use TenantForge\Security\Actions\Invitation\RevokeInvitationAction;
use TenantForge\Security\Enums\InvitationStatus;
use TenantForge\Security\Enums\SecurityPermission;
use TenantForge\Security\Models\CentralUser;
use TenantForge\Security\Models\Invitation;
use TenantForge\Tenancy\Models\Tenant;

test('revoke invitation', function (): void {

    // Arrange
    $tenant = Tenant::factory()->createQuietly();
    $user = CentralUser::factory()->createQuietly();
    Permission::findOrCreate(SecurityPermission::RevokeInvites->value);
    $user->givePermissionTo(SecurityPermission::RevokeInvites);

    Auth::login($user);

    /** @var Invitation $invitation */
    $invitation = Invitation::factory()
        ->forTenant($tenant)
        ->from($user)
        ->createQuietly()
        ->fresh();

    // Act
    /** @var RevokeInvitationAction $action */
    $action = app(RevokeInvitationAction::class);
    $action->handle(invitation: $invitation, user: $user);

    // Assert
    $invitation->refresh();
    expect($invitation->status)
        ->toBe(InvitationStatus::REVOKED);

});
