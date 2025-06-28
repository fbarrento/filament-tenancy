<?php

namespace App\Filament\Admin\Resources\CentralUserResource\Pages;

use App\Filament\Admin\Resources\CentralUserResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCentralUser extends EditRecord
{
    protected static string $resource = CentralUserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
