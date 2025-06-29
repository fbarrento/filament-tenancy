@php @endphp
    <!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="{{ isset($dark) ? 'dark' : '' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- MaryUI Assets (Separate Tailwind v4 Build) -->
    @vite(['src/js/app.js', 'src/css/app.css'], 'build-mary', 'frontend')

    @livewireStyles
</head>
<body class="min-h-screen font-sans antialiased bg-base-200 text-base-content">

<x-nav sticky full-width="">
    <x-slot:brand>
        <label for="main-drawer" class="lg:hidden mr-3">
            <x-icon name="o-bars-3" class="cursor-pointer"/>
        </label>
        <div>App</div>
    </x-slot:brand>

    <x-slot:actions>
        <x-button
            label="{{__('Sign in')}}"
            link="{{route('login')}}"
            class="btn-ghost btn-sm"
            responsive/>
        <x-button
            label="{{__('Sign up')}}"
            link="{{route('filament.guest.auth.register')}}"
            class="btn-outline btn-sm"
            responsive/>
        <x-theme-toggle class="btn btn-circle btn-ghost"/>
    </x-slot:actions>
</x-nav>

<div id="app">
    {{ $slot }}
</div>
@livewireScripts

<!-- Theme toggle button (hidden by default, use where needed) -->
<button id="theme-toggle" class="hidden">Toggle Theme</button>
</body>
</html>
