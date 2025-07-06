<?php

use TenantForge\Security\Models\CentralUser;
use TenantForge\Security\Models\Invitation;

test('to array', function (): void {

    // Arrange
    $user = CentralUser::factory()->create();

    $invitation = Invitation::factory()
        ->central()
        ->from($user)
        ->createQuietly();

    // Assert
    expect($invitation)
        ->toBeInstanceOf(Invitation::class);

});
