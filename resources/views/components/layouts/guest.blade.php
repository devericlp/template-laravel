<!DOCTYPE html>
<html class="h-full bg-white dark:bg-gray-900" lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ isset($title) ? $title . ' - ' . config('app.name') : config('app.name') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @fluxAppearance
</head>

<body class="h-full">

@if (!app()->environment('production'))
    <x-devbar/>
@endif
<div class="flex min-h-screen">
    <div class="flex-1 flex justify-center items-center">
        <div class="w-80 max-w-80 space-y-6">
            <div class="flex justify-between opacity-50">
                <span></span>
                <flux:brand href="/" logo="resources/img/logo.png" name="{{ config('app.name') }}"/>
                <flux:button x-data x-on:click="$flux.dark = ! $flux.dark" icon="moon" variant="subtle"
                             aria-label="Toggle dark mode"/>
            </div>

            {{ $slot }}
        </div>
    </div>

    <div class="flex-1 p-4 max-lg:hidden">

    </div>
</div>

@fluxScripts

@persist('toast')
<flux:toast/>
@endpersist
</body>

</html>
