<?php

declare(strict_types=1);

namespace TenantForge\Security\Filament\Admin\Resources\UserResource\Pages;

use Filament\Actions\DeleteAction;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use TenantForge\Security\Filament\Admin\Resources\UserResource;

final class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
