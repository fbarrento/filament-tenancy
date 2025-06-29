<?php

use App\Http\Livewire\Registration;
use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Middleware\InitializeTenancyBySubdomain;

Route::get('/sign-up-2', Registration::class)
    ->middleware(['universal', InitializeTenancyBySubdomain::class])
    ->name('sign-up');
