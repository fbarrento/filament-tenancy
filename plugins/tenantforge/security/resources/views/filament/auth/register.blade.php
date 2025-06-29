<x-tenantforge:security::layouts.auth>

    <x-slot:heading>{{__('Create an account')}}</x-slot:heading>

    {{ \Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::AUTH_REGISTER_FORM_BEFORE, scopes: $this->getRenderHookScopes()) }}

    <x-filament-panels::form id="form" wire:submit="register">
        {{ $this->form }}

        <x-filament-panels::form.actions
            :actions="$this->getCachedFormActions()"
            :full-width="$this->hasFullWidthFormActions()"
        />
    </x-filament-panels::form>

    {{ \Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::AUTH_REGISTER_FORM_AFTER, scopes: $this->getRenderHookScopes()) }}


    @if (filament()->hasLogin())
        <div class="my-6">
            <p class="text-sm text-gray-600">
                Already have an account?
                <a
                    href="{{ filament()->getLoginUrl() }}"
                    class="font-semibold text-sm text-custom-600 dark:text-custom-400"
                    style="--c-400:var(--primary-400);--c-600:var(--primary-600);">
                    {{ __('Sign in') }}
                </a>
            </p>
        </div>

    @endif
</x-tenantforge:security::layouts.auth>
