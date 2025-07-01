<?php

use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Middleware\InitializeTenancyBySubdomain;
use TenantForge\Security\Filament\Pages\Auth\Login;

Route::get('/sign-in', Login::class)
    ->middleware([InitializeTenancyBySubdomain::class])
    ->name('login');
