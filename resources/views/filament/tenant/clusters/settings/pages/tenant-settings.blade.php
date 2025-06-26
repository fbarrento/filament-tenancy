<x-filament-panels::page>
    <form wire:submit.prevent="save">
        {{ $this->form }}


        <x-filament::button type="submit">
            {{ __('Save') }}
        </x-filament::button>


    </form>

    <x-filament-actions::modals />
</x-filament-panels::page>
