@php use App\Filament\Tenant\Clusters\Settings\Pages\OrganizationSettings;use App\Helpers\Initials; @endphp
<div class="-mx-2 w-full">
    <x-filament::dropdown placement="bottom-start">
        <x-slot name="trigger">
            <div class="flex justify-between items-center p-2 rounded-lg w-full ">
                <div class="flex items-center gap-3">
                    <div
                        class="w-10 h-10 shrink-0 rounded-md flex items-center justify-center {{ tenant('avatar') ? '' : 'bg-gray-200 dark:bg-gray-700' }}">
                        <span class="uppercase">
                            @if (tenant('avatar'))
                                <img src="{{ tenant('avatar_url') }}" alt="{{ tenant('name') }}" class="w-10 object-contain rounded-md">
                            @else
                                <span class="font-bold">{{ Initials::generate(tenant('name')) }}</span>
                            @endif
                        </span>
                    </div>
                    <div class="flex flex-col space-y-0.5 w-full">
                        <div class="w-full">
                            <span class="line-clamp-1 font-semibold text-sm text-ellipsis overflow-hidden w-full ...">
                                {{ tenant('name') }}
                            </span>
                        </div>
                        <span class="text-xs text-gray-600 dark:text-gray-500">
                            {{ auth()->user()->name }}
                        </span>
                    </div>
                </div>
                <div class="">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                         stroke="currentColor" class="h-4 w-4 text-gray-500">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M8.25 15 12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9"/>
                    </svg>
                </div>
            </div>
        </x-slot>

        <x-filament::dropdown.list>
            <x-filament::dropdown.list.item icon="heroicon-o-cog-6-tooth" href="{{ OrganizationSettings::getUrl() }}"
                                            tag="a">
                {{__('Organization settings')}}
            </x-filament::dropdown.list.item>
        </x-filament::dropdown.list>
        <x-filament::dropdown.list>
            @foreach ($tenants as $tenant)
                <x-filament::dropdown.list.item wire:click="switchTenant('{{ $tenant['id'] }}'); close()">
                    {{ $tenant['name'] }}
                </x-filament::dropdown.list.item>
            @endforeach
        </x-filament::dropdown.list>
        <x-filament::dropdown.list>
            <x-filament::dropdown.list.item icon="heroicon-o-plus">
                {{ __('Create New Organization') }}
            </x-filament::dropdown.list.item>
        </x-filament::dropdown.list>
    </x-filament::dropdown>
</div>
