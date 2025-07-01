<?php

use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Support\Facades\Route;
use TenantForge\Security\Filament\Pages\Auth\EmailVerification\EmailVerificationPrompt;
use TenantForge\Security\Filament\Pages\Auth\Register;

Route::get('/sign-up', Register::class)
    ->name('register');

Route::get('/email-verification/prompt', EmailVerificationPrompt::class)
    ->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {

    $request->fulfill();

    return redirect('/admin');
})
    ->middleware(['auth:central', 'signed'])
    ->name('verification.verify');
