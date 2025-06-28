<?php

namespace App\Filament\Tenant\Clusters\Settings\Pages;

use App\Filament\Tenant\Clusters\Settings;
use App\Models\Tenant;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Support\Exceptions\Halt;

use function __;
use function tenant;

class OrganizationSettings extends Page implements HasForms
{
    use InteractsWithForms;

    public ?array $data = [];

    public Tenant $tenant;

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static string $view = 'filament.tenant.clusters.settings.pages.tenant-settings';

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

    public function form(Form $form): Form
    {
        return $form
            ->schema([
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

    protected function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label(__('Save changes'))
                ->keyBindings(['command+s', 'ctrl+s'])
                ->submit('save'),
        ];
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
            ->title(__('Organization Settings Saved'))
            ->success()
            ->send();

    }
}
