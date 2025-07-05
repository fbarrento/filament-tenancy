<?php

declare(strict_types=1);

namespace App\Livewire;

use Filament\Schemas\Schema;
use App\Actions\Tenant\CreateTenantDomainAction;
use App\Actions\Tenant\CreateTenantUserAction;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;
use TenantForge\Security\Models\CentralUser;
use Throwable;

use function __;

#[Layout('components.layouts.auth')]
final class RegistrationForm extends Component implements HasActions, HasForms
{
    use InteractsWithActions, InteractsWithForms;

    public ?array $data = [];

    private CreateTenantUserAction $createTenantUserAction;

    private CreateTenantDomainAction $createTenantDomainAction;

    public function boot(
        CreateTenantUserAction $createTenantUserAction,
        CreateTenantDomainAction $createTenantDomainAction
    ): void {
        $this->createTenantUserAction = $createTenantUserAction;
        $this->createTenantDomainAction = $createTenantDomainAction;
    }

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                /**TextInput::make('organization')
                    ->required()
                    ->minLength(5)
                    ->maxLength(100),
                TextInput::make('domain')
                    ->columnSpanFull()
                    ->unique('domains', 'domain')
                    ->required()
                    ->suffix('.'.request()->getHost()),*/
                TextInput::make('name')
                    ->required()
                    ->minLength(5)
                    ->maxLength(100),
                TextInput::make('email')
                    ->email()
                    ->unique(CentralUser::class, 'email')
                    ->minLength(5)
                    ->maxLength(100)
                    ->required(),
                TextInput::make('password')
                    ->password()
                    ->hint(__('Must be at least 12 characters long.'))
                    ->revealable()
                    ->same('password_confirmation')
                    ->required()
                    ->revealable()
                    ->same('password_confirmation')
                    ->required()
                    ->minLength(12)
                    ->maxLength(100),
                TextInput::make('password_confirmation')
                    ->same('password')
                    ->password()
                    ->revealable()
                    ->minLength(12)
                    ->maxLength(100)
                    ->required(),
            ])
            ->statePath('data');
    }

    protected function registerAction(): Action
    {
        return Action::make('register')
            ->label(__('Create Account'))
            ->submit('register');
    }

    /**
     * @throws Throwable
     */
    public function register(): void
    {

        $data = $this->form->getState();

        $user = CentralUser::query()->create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        Notification::make()
            ->title(__('Account Created'))
            ->success()
            ->send();

    }

    public function render(): View
    {
        return view('livewire.registration-form');
    }
}
