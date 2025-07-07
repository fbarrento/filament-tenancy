<?php

use TenantForge\Security\Actions\CreateInvitationAction;
use TenantForge\Security\Models\CentralUser;
use TenantForge\Security\Models\Invitation;
use TenantForge\Tenancy\Models\Tenant;

test('it creates an invitation for a tenant', function (): void {
    // Arrange
    $tenant = Tenant::factory()
        ->createQuietly();

    // Act
    /** @var CreateInvitationAction $action */
    $action = app(CreateInvitationAction::class);
    $invitation = $action->handle(
        email: 'RiF8e@example.com',
        inviter: CentralUser::factory()->createQuietly(),
        tenant: $tenant,
        role: 'admin',
        metadata: ['foo' => 'bar'],
    );

    // Assert
    expect($invitation)
        ->toBeInstanceOf(Invitation::class)
        ->toHaveKey('token')
        ->toHaveKey('email', 'RiF8e@example.com')
        ->and($invitation->tenant)
        ->toBeInstanceOf(Tenant::class)
        ->and($invitation->inviter)
        ->toBeInstanceOf(CentralUser::class);

});
