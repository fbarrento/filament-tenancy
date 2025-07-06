<?php

namespace App\Filament\Tenant\Clusters\Settings\Pages;

use App\Filament\Tenant\Clusters\Settings;
use Exception;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use Spatie\Permission\Models\Role;
use TenantForge\Security\Models\CentralUser;

use function __;
use function auth;

class TenantMembers extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-users';

    protected string $view = 'filament.tenant.clusters.settings.pages.organization-members';

    protected static ?string $cluster = Settings::class;

    public function getTitle(): string
    {
        return __('Members');
    }

    public static function getNavigationLabel(): string
    {
        return __('Members');
    }

    /**
     * @throws Exception
     */
    public function getHeaderActions(): array
    {
        return [
            Action::make('invite')
                ->icon(Heroicon::OutlinedEnvelope)
                ->label(__('Invite User'))
                ->schema([
                    TextInput::make('email')
                        ->label(__('Email'))
                        ->email()
                        ->required(),
                    Select::make('role')
                        ->options(function () {
                            return Role::all()->pluck('name', 'id');
                        }),
                ])
                ->modalSubmitAction(fn (Action $action) => $action->label(__('Send Invite'))->icon(Heroicon::OutlinedPaperAirplane))
                ->action(function (array $data): void {

                    $user = CentralUser::query()
                        ->where('global_id', auth()->user()->global_id)
                        ->first();

                    Notification::make()
                        ->title(__('Invitation Sent'))
                        ->body('You have invited '.$data['email'].' to join your organization.')
                        ->success()
                        ->sendToDatabase($user);

                }),
        ];
    }
}
