<div class="flex h-screen w-full">

    <div class="lg:w-7/12 flex items-center justify-center">
        <div class="border-1 bg-white border-gray-200 rounded-lg p-4 flex flex-col min-w-xl">

            <div class="flex justify-end items-center w-full">
                <span class="text-sm text-gray-600">
                    {{ __('Already have an account?') }} <a href="/" class="text-sm text-gray-600 underline hover:text-gray-900">{{ __('Login') }}</a>
                </span>
            </div>

            <x-filament-panels::form wire:submit.prevent="register">
                {{ $this->form }}

                {{ $this->registerAction }}

                <x-filament-actions::modals />
            </x-filament-panels::form>

            <p class="mt-6 mb-4 text-sm text-gray-600">
                By signing up, you agree to our <a href="#" class="text-primary">Terms of Service</a> and <a href="#" class="text-primary">Privacy Policy</a>.
            </p>

        </div>
    </div>

    <div class="grow bg-gradient-to-b from-blue-500 to-blue-800 text-white flex flex-col px-8 py-2">
        <div class="text-right w-full text-sm">
            <a href="/" class="text-white hover:text-gray-200">
               <x-icon name="o-chevron-left" class="h-4 w-4" /> {{ __('Back to Home') }}
            </a>
        </div>
    </div>



</div>
