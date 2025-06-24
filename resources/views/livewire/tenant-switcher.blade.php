@php use Filament\Support\Colors\Color; @endphp
<div class="-mx-2">
    <x-filament::dropdown placement="bottom-start">
        <x-slot name="trigger">

            <div class="flex justify-between items-center p-2 rounded-lg w-full bg-zinc-100 dark:bg-gray-900 ">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-md bg-gray-200 dark:bg-gray-950 flex items-center justify-center">
                        <span class="uppercase">FB</span>
                    </div>
                    <div class="flex flex-col space-y-0.5">
                        <span class="text-xs text-gray-600 dark:text-gray-500">
                            {{ __('Current Organization') }}
                        </span>
                        <span class="font-semibold text-sm">{{ tenant()->name }}</span>
                    </div>
                </div>
                <div class="">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-5 w-5 text-slate-500">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                    </svg>

                </div>
            </div>
        </x-slot>
        <x-filament::dropdown.list>
            @foreach ($tenants as $tenant)
                <x-filament::dropdown.list.item wire:click="switchTenant('{{ $tenant['id'] }}'); close()">
                    {{ $tenant['name'] }}
                </x-filament::dropdown.list.item>
            @endforeach
        </x-filament::dropdown.list>
    </x-filament::dropdown>
</div>
