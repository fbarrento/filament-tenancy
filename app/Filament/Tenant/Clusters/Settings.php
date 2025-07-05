<?php

namespace App\Filament\Tenant\Clusters;

use Filament\Clusters\Cluster;

class Settings extends Cluster
{
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-squares-2x2';

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }
}
