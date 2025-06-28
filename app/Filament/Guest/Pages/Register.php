<?php

declare(strict_types=1);

namespace App\Filament\Guest\Pages;

use Filament\Pages\Auth\Register as BaseRegister;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.auth')]
final class Register extends BaseRegister
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.guest.pages.register';
}
