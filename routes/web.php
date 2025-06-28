<?php

use App\Http\Livewire\Registration;
use App\Http\Livewire\Welcome;
use Illuminate\Support\Facades\Route;

Route::get('/', Welcome::class)->name('welcome');

Route::get('/sign-up-2', Registration::class)
    ->name('sign-up');
