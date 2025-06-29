<?php

declare(strict_types=1);

namespace TenantForge\Security\Filament\Admin\Resources\UserResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use TenantForge\Security\Filament\Admin\Resources\UserResource;

final class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;
}
