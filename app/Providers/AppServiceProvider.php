<?php

namespace App\Providers;

use Filament\Support\Facades\FilamentColor;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        FilamentColor::register([
            'cinza' => [
                '50' => '#fafafa',
                '100' => '#f5f5f5',
                '200' => '#eeeeee',
                '300' => '#e0e0e0',
                '400' => '#bdbdbd',
                '500' => '#9e9e9e',
                '600' => '#757575',
                '700' => '#616161',
                '800' => '#424242',
                '900' => '#212121',
            ],
        ]);
    }
}
