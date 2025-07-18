<?php

use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Support\Facades\Route;
use TenantForge\Security\Filament\Pages\Auth\EmailVerification\EmailVerificationPrompt;
use TenantForge\Security\Filament\Pages\Auth\Invitation;
use TenantForge\Security\Filament\Pages\Auth\Login;
use TenantForge\Security\Filament\Pages\Auth\Register;

Route::get('/sign-in', Login::class)
    ->name('login');

Route::get('/sign-up', Register::class)
    ->name('register');

Route::get('/email-verification/prompt', EmailVerificationPrompt::class)
    ->middleware(['auth:central'])
    ->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {

    $request->fulfill();

    return redirect('/admin');
})
    ->middleware(['auth:central', 'signed'])
    ->name('verification.verify');

Route::get('/invitation/{token}', Invitation::class)
    ->middleware(['signed'])
    ->name('invitations.accept');
