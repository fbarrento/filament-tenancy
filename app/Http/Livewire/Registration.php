<?php

declare(strict_types=1);

namespace App\Http\Livewire;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.auth')]
final class Registration extends Component implements HasForms
{
    use InteractsWithForms;

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name'),
                TextInput::make('email'),
                TextInput::make('password'),
                TextInput::make('password_confirmation'),
            ])
            ->statePath('data');
    }

    public function render(): View
    {
        return view('livewire.registration');
    }
}
