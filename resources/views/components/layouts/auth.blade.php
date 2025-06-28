<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="{{ isset($dark) ? 'dark' : '' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    @filamentStyles

    @vite(['resources/css/filament/theme.css', 'resources/js/app.js'])

    {{ filament()->getFontHtml() }}

    <style>
        :root {
            --font-family: '{!! filament()->getFontFamily() !!}';
            --sidebar-width: {{ filament()->getSidebarWidth() }};
            --collapsed-sidebar-width: {{ filament()->getCollapsedSidebarWidth() }};
            --default-theme-mode: {{ filament()->getDefaultThemeMode()->value }};
        }
    </style>
    @livewireStyles
</head>
<body class="min-h-screen font-sans antialiased bg-base-200 text-base-content">

    <div id="app">
        {{ $slot }}
    </div>
    @livewire('notifications')
    @filamentScripts
    @livewireScripts

    <!-- Theme toggle button (hidden by default, use where needed) -->
    <button id="theme-toggle" class="hidden">Toggle Theme</button>
</body>
</html>
