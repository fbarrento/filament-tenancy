<?php

declare(strict_types=1);

namespace TenantForge\Security\Filament\Admin\Resources\UserResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use TenantForge\Security\Filament\Admin\Resources\UserResource;

final class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
