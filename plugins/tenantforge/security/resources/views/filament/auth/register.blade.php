<x-tenantforge:security::layouts.auth>

    <x-slot:heading>{{__('Create an account')}}</x-slot:heading>

    {{ $this->content }}


    <div class="my-6">
        <p class="text-sm text-gray-600">
            Already have an account?
            <a
                href="{{ route('login') }}"
                class="font-semibold text-sm text-custom-600 dark:text-custom-400"
                style="--c-400:var(--primary-400);--c-600:var(--primary-600);">
                {{ __('Sign in') }}
            </a>
        </p>
    </div>
</x-tenantforge:security::layouts.auth>
