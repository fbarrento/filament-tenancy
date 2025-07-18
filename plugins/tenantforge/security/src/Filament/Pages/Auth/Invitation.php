<?php

declare(strict_types=1);

namespace TenantForge\Security\Filament\Pages\Auth;

use Exception;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Schemas\Schema;
use Filament\Support\Exceptions\Halt;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;
use TenantForge\Security\Actions\Invitation\AcceptInvitationForNewTenantUserAction;
use TenantForge\Security\DataObjects\NewUserData;
use TenantForge\Security\Enums\InvitationStatus;
use TenantForge\Security\Filament\Forms\RegistrationForm;
use TenantForge\Security\Models\CentralUser;
use TenantForge\Security\Models\Invitation as InvitationModel;
use Throwable;

use function __;

#[Layout('components.layouts.auth')]
class Invitation extends Component implements HasActions, HasForms
{
    use InteractsWithActions;
    use InteractsWithForms;

    public ?InvitationModel $invitation;

    public ?CentralUser $centralUser = null;

    private AcceptInvitationForNewTenantUserAction $acceptNewUserTenantInvitationAction;

    public array $data = [];

    public function boot(AcceptInvitationForNewTenantUserAction $acceptNewUserTenantInvitationAction): void
    {
        $this->acceptNewUserTenantInvitationAction = $acceptNewUserTenantInvitationAction;
    }

    public function mount(string $token): void
    {

        $this->invitation = InvitationModel::query()
            ->where('token', $token)
            ->where('status', InvitationStatus::PENDING)
            ->firstOrFail();

        $this->centralUser = CentralUser::query()
            ->where('email', $this->invitation->email)
            ->first();

        $this->form->fill([
            'email' => $this->invitation->email,
        ]);

    }

    /**
     * @throws Exception
     */
    public function form(Schema $schema): Schema
    {
        return RegistrationForm::configure($schema)
            ->statePath('data');
    }

    public function registerAction(): Action
    {
        return Action::make('register')
            ->label(__('filament-panels::auth/pages/register.form.actions.register.label'))
            ->extraAttributes(['class' => 'w-full'])
            ->submit('register');
    }

    public function acceptTenantInvitationAction(): Action
    {

        return Action::make('acceptTenantInvitation')
            ->label(__('Accept'))
            ->color('success')
            ->button()
            ->action(function (Invitation $livewire) {
                $livewire->acceptTenantInvitation();
            });

    }

    public function declineTenantInvitationAction(): Action
    {
        return Action::make('declineTenantInvitation')
            ->label(__('Decline'))
            ->color('danger')
            ->link()
            ->action(function (Invitation $livewire) {
                $livewire->declineTenantInvitation();
            });
    }

    public function declineTenantInvitation(): void
    {
        Notification::make('dc')
            ->danger()
            ->title(__('You have been joined to ').$this->invitation->tenant->name.'!')
            ->send();
    }

    public function acceptTenantInvitation(): void
    {

        Notification::make('dc')
            ->success()
            ->title(__('You have been joined to ').$this->invitation->tenant->name.'!')
            ->send();

    }

    /**
     * @throws Throwable
     */
    public function register(): void
    {

        try {
            $data = $this->form->getState();

            if (! $this->centralUser && $this->invitation->isTenantInvitation()) {
                $this->acceptNewUserTenantInvitationAction->handle(
                    data: NewUserData::fromArray($data),
                    invitation: $this->invitation,
                    tenantRole: $this->invitation->role,
                );
            }

        } catch (Halt $exception) {
            return;
        }

        Notification::make('dc')
            ->success()
            ->title(__('You have successfully registered!'))
            ->send();
    }

    public function render(): View
    {
        return view('tenantforge:security::filament.auth.invitation');
    }
}
