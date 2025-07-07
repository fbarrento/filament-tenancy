<x-filament-panels::page>
    <form wire:submit.prevent="save" class="space-y-6">
        {{ $this->form }}

        {{$this->saveAction}}

    </form>

    <x-filament-actions::modals />
</x-filament-panels::page>
