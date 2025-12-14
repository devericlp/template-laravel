<!DOCTYPE html>
<html class="h-full" lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" href="{{ Vite::asset('resources/img/favicons/favicon.ico') }}">
    <title>{{ isset($title) ? $title . ' - ' . config('app.name') : config('app.name') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @fluxAppearance
</head>

<body class="min-h-screen flex flex-col items-center justify-start md:justify-center">

<livewire:dev.login/>

<div class="rounded-xl shadow-2xl flex flex-col md:flex-row w-full max-w-5xl sm:border sm:border-accent/25">
    <div
        class="relative md:w-1/3 hidden bg-accent p-6 md:flex flex-col justify-center items-center space-y-3 md:rounded-l-xl"
        style="background-image: url('https://i.pinimg.com/1200x/83/73/8c/83738c77ac5e9d9fc13bd252e6be801f.jpg'); background-size: cover">
        <flux:brand href="{{ route('login') }}" name="{{ config('app.name') }}" class="top-0 left-0 absolute ml-3">
            <x-slot name="logo" class="size-6 rounded-full bg-white text-accent text-xs font-bold">
                <flux:icon name="code-bracket" variant="micro"/>
            </x-slot>
        </flux:brand>
        <flux:heading size="xl" class="text-2xl text-white text-center">{{ __('messages.welcome_back') }}</flux:heading>
        <flux:text class="text-center text-gray-300 px-10">
            {{ __('messages.keep_connect') }}
        </flux:text>
    </div>

    <div class="md:w-2/3 md:px-16 p-4 flex flex-col justify-center md:rounded-r-2xl">

        <flux:dropdown x-data align="end">
            <flux:button variant="subtle" square class="group" aria-label="Preferred color scheme">
                <flux:icon.sun x-show="$flux.appearance === 'light'" variant="mini"
                               class="text-zinc-500"/>
                <flux:icon.moon x-show="$flux.appearance === 'dark'" variant="mini"
                                class="text-zinc-500"/>
                <flux:icon.moon x-show="$flux.appearance === 'system' && $flux.dark" variant="mini"/>
                <flux:icon.sun x-show="$flux.appearance === 'system' && ! $flux.dark" variant="mini"/>
            </flux:button>
            <flux:menu>
                <flux:menu.item icon="sun" x-on:click="$flux.appearance = 'light'">Light</flux:menu.item>
                <flux:menu.item icon="moon" x-on:click="$flux.appearance = 'dark'">Dark</flux:menu.item>
                <flux:menu.item icon="computer-desktop" x-on:click="$flux.appearance = 'system'">System
                </flux:menu.item>
            </flux:menu>
        </flux:dropdown>

        <div class="flex flex-col justify-center items-center sm:hidden mb-2">
            <flux:brand href="{{ route('login') }}" name="{{ config('app.name') }}">
                <x-slot name="logo" class="size-6 rounded-full bg-white text-accent text-xs font-bold">
                    <flux:icon name="code-bracket" variant="micro"/>
                </x-slot>
            </flux:brand>
            <flux:heading size="sm"
                          class="text-gray-300 dark:text-white">{{ __('messages.welcome_back') }}</flux:heading>
        </div>

        {{ $slot }}
    </div>
</div>

<livewire:components.auth.logout/>
@fluxScripts

@persist('toast')
<flux:toast/>
@endpersist
</body>

</html>
