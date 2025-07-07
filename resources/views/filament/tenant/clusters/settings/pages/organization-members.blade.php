@php use TenantForge\Security\Enums\SecurityPermission; @endphp
<x-filament-panels::page>

    @can(SecurityPermission::ViewInvites)
        {{$this->table}}
    @endcan

</x-filament-panels::page>
