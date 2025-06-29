<?php

use Illuminate\Support\Facades\Route;
use TenantForge\Security\Filament\Pages\Auth\EmailVerification\EmailVerificationPrompt;
use TenantForge\Security\Filament\Pages\Auth\Login;
use TenantForge\Security\Filament\Pages\Auth\Register;

Route::get('sign-up', Register::class)
    ->name('register');

Route::get('sign-in', Login::class)
    ->name('login');

Route::get('email-verification/prompt', EmailVerificationPrompt::class)
    ->name('verification.notice');
