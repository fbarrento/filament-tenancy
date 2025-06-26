<?php

namespace App\Filament\Tenant\Clusters\Settings\Pages;

use App\Filament\Tenant\Clusters\Settings;
use Filament\Pages\Page;

use function __;

class TenantMembers extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static string $view = 'filament.tenant.clusters.settings.pages.organization-members';

    protected static ?string $cluster = Settings::class;

    public function getTitle(): string
    {
        return __('Members');
    }

    public static function getNavigationLabel(): string
    {
        return __('Members');
    }
}
