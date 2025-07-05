<x-tenantforge:security::layouts.auth>

    <x-slot:heading>{{__('Sign in to your account')}}</x-slot:heading>


   {{ $this->content }}


        @if (filament()->hasRegistration())
            <x-slot name="subheading">
                {{ __('filament-panels::pages/auth/login.actions.register.before') }}

                {{ $this->registerAction }}
            </x-slot>
        @endif

</x-tenantforge:security::layouts.auth>
