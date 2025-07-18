@php use TenantForge\Security\Enums\InvitationType; @endphp
<x-tenantforge:security::layouts.auth>


    @if (!$this->centralUser && $this->invitation->type === InvitationType::TENANT)
        <x-slot:heading>{{__('Create an account')}}</x-slot:heading>
        <x-slot:description>
            You have been invited to join <span class="font-semibold">{{ $this->invitation->tenant->name }}</span>.
        </x-slot:description>

    <form class="space-y-6" wire:submit.prevent="register">
        {{ $this->form }}
        {{ $this->registerAction }}
    </form>

    @endif

    @if ($this->centralUser && $this->invitation->type === InvitationType::TENANT)
        <x-slot:heading>{{__('Accept the invitation')}}</x-slot:heading>
        <x-slot:description>
            You have been invited to join <span class="font-semibold">{{ $this->invitation->tenant->name }}</span>.
        </x-slot:description>

        <div class="flex gap-4">
            {{ $this->acceptTenantInvitationAction }} {{ $this->declineTenantInvitationAction }}
        </div>

    @endif

    @if ($this->invitation->type === InvitationType::TENANT)

    @endif

</x-tenantforge:security::layouts.auth>>
