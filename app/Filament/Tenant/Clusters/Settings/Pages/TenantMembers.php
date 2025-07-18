<?php

namespace App\Filament\Tenant\Clusters\Settings\Pages;

use App\Filament\Tenant\Clusters\Settings;
use BackedEnum;
use Exception;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Spatie\Permission\Models\Role;
use TenantForge\Security\Actions\Invitation\CreateInvitationAction;
use TenantForge\Security\Actions\Invitation\RevokeInvitationAction;
use TenantForge\Security\Actions\Invitation\SendInvitationNotificationAction;
use TenantForge\Security\Enums\InvitationStatus;
use TenantForge\Security\Models\CentralUser;
use TenantForge\Security\Models\Invitation;

use function __;
use function auth;
use function tenant;

class TenantMembers extends Page implements HasActions, HasSchemas, HasTable
{
    use InteractsWithActions;
    use InteractsWithSchemas;
    use InteractsWithTable;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-users';

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
                        ->notIn(fn () => Invitation::query()
                            ->where('tenant_id', tenant('id'))
                            ->pluck('email'))
                        ->required(),
                    Select::make('role')
                        ->options(function () {
                            return Role::query()->whereNot('name', \TenantForge\Security\Enums\SecurityRole::Owner)->pluck('name', 'name');
                        }),
                ])
                ->modalSubmitAction(fn (Action $action) => $action->label(__('Send Invite'))->icon(Heroicon::OutlinedPaperAirplane))
                ->action(function (array $data, CreateInvitationAction $createInvitationAction): void {
                    $user = CentralUser::query()
                        ->where('global_id', auth()->user()->global_id)
                        ->first();

                    $createInvitationAction->handle(
                        email: $data['email'],
                        inviter: $user,
                        tenant: tenant(),
                        role: $data['role'],
                    );

                    Notification::make()
                        ->title(__('Invitation Sent'))
                        ->body('You have invited '.$data['email'].' to join your organization.')
                        ->success()
                        ->sendToDatabase($user);

                }),
        ];
    }

    /**
     * @throws Exception
     */
    public function table(Table $table): Table
    {
        return $table
            ->query(fn () => Invitation::query()
                ->where('tenant_id', tenant('id')))
            ->heading(__('Pending Invitations'))
            ->description(__('List of pending invitations for your organization.'))
            ->columns([
                TextColumn::make('email')
                    ->sortable(),
                TextColumn::make('inviter.name'),
                TextColumn::make('status')
                    ->sortable()
                    ->badge(),
                SelectColumn::make('role')
                    ->options(function () {
                        return Role::query()->whereNot('name', \TenantForge\Security\Enums\SecurityRole::Owner)->pluck('name', 'name');
                    })
                    ->disabled(fn (Invitation $record): bool => $record->status !== InvitationStatus::PENDING)

                    ->selectablePlaceholder(false)
                    ->sortable(),
            ])
            ->recordActions([
                ActionGroup::make([
                    Action::make('revoke')
                        ->hidden(fn (Invitation $record): bool => $record->status !== InvitationStatus::PENDING)
                        ->authorize('revoke', Invitation::class)
                        ->authorizationMessage(__('You cannot revoke this invitation.'))
                        ->authorizationTooltip()
                        ->icon(Heroicon::OutlinedMinusCircle)
                        ->action(function (Invitation $record, RevokeInvitationAction $revokeInvitationAction): void {
                            $revokeInvitationAction->handle($record, auth()->user());
                            Notification::make()
                                ->title(__('Invitation Revoked'))
                                ->body('You have revoked the invitation to '.$record->email)
                                ->success()
                                ->send();
                        })
                        ->extraAttributes(['x-on:mousedown' => 'toggle']),
                    Action::make('resend')
                        ->authorize('resend', Invitation::class)
                        ->authorizationMessage(__('You cannot resend this invitation.'))
                        ->authorizationTooltip()
                        ->requiresConfirmation(fn (Invitation $record): bool => $record->status !== InvitationStatus::PENDING)
                        ->modalHidden(fn (Invitation $record): bool => $record->status === InvitationStatus::PENDING)
                        ->modalDescription(__('Are you sure you want to resend this invitation? This will send an email to the user. And the invitation will be marked as pending.'))
                        ->modalSubmitActionLabel(__('Yes, Resend it'))
                        ->color('warning')
                        ->action(function (Invitation $record, SendInvitationNotificationAction $sendInvitationAction): void {
                            $sendInvitationAction->handle($record);
                            Notification::make()
                                ->title(__('Invitation Resent'))
                                ->body('You have resent the invitation to '.$record->email)
                                ->success()
                                ->send();
                        })
                        ->icon(Heroicon::OutlinedPaperAirplane)
                        ->extraAttributes(['x-on:mousedown' => 'toggle']),
                    Action::make('delete')
                        ->authorize('delete', Invitation::class)
                        ->authorizationMessage(__('You cannot delete this invitation.'))
                        ->authorizationTooltip()
                        ->requiresConfirmation()
                        ->modalSubmitActionLabel(__('Yes, delete it'))
                        ->color('danger')
                        ->icon(Heroicon::OutlinedTrash)
                        ->extraAttributes(['x-on:mousedown' => 'toggle']),
                ]),
            ]);
    }
}
