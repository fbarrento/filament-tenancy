<div class="flex h-screen">
    <div class="flex justify-center w-full md:w-6/12 shrink-0">
        <div class="max-w-lg lg:max-w-xl px-4 w-full">

            <div class="my-10">
                <h1 class="text-2xl font-bold">{{ filament()->getBrandName() }}</h1>
            </div>

            <div class="my-16">
                <h2 class="text-lg font-semibold">Create an account</h2>
                <p class="text-sm text-gray-600">Experience the power of {{ filament()->getBrandName() }}.</p>
            </div>


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

        </div>

    </div>
    <div class="hidden md:flex w-full">
        vfvf
    </div>
</div>
