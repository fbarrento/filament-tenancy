<?php

namespace App\Filament\Tenant\Clusters\Settings\Pages;

use App\Filament\Tenant\Clusters\Settings;
use App\Models\Tenant;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Schema;
use Filament\Support\Exceptions\Halt;

use function __;
use function tenant;

class OrganizationSettings extends Page implements HasForms
{
    use InteractsWithActions;
    use InteractsWithSchemas;

    public ?array $data = [];

    public Tenant $tenant;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected string $view = 'filament.tenant.clusters.settings.pages.tenant-settings';

    protected static ?string $cluster = Settings::class;

    public function mount(): void
    {
        $this->tenant = tenant();
        $this->form->fill(tenant()->attributesToArray());
    }

    public function getTitle(): string
    {
        return __('Organization Settings');
    }

    public static function getNavigationLabel(): string
    {
        return __('General Settings');
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('General'))
                    ->description(__('Your Organization General Settings'))
                    ->schema([
                        TextInput::make('name'),
                        TextInput::make('short_name'),
                    ]),

                Section::make(__('Avatar'))
                    ->schema([
                        FileUpload::make('avatar')
                            ->label(false)
                            ->disk('public')
                            ->visibility('public')
                            ->directory('avatar')
                            ->imageEditor()
                            ->circleCropper()
                            ->avatar()
                            ->image(),
                    ]),
            ])
            ->statePath('data')
            ->model($this->tenant);
    }

    public function saveAction(): Action
    {
        return Action::make('save')
            ->label(__('Save changes'))
            ->keyBindings(['command+s', 'ctrl+s'])
            ->submit('save');
    }

    public function save(): void
    {

        try {
            $data = $this->form->getState();

            $this->tenant->update($data);

        } catch (Halt $exception) {
            return;
        }

        Notification::make()
            ->title(__('Saved'))
            ->success()
            ->send();

    }
}
