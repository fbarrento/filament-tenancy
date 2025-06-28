<?php

namespace App\Filament\Guest\Pages;

use Filament\Pages\Page;

class Home extends Page
{
    protected static string $routePath = '/dashboard';

    protected static string $view = 'filament.public.pages.home';

    protected static bool $shouldRegisterNavigation = false;

    public static function getRoutePath(): string
    {
        return static::$routePath;
    }
}
