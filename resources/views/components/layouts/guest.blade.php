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

<div class="flex min-h-screen">
    <div class="flex-1 flex justify-center items-center">
        <div class="w-80 max-w-80">
            <div class="flex justify-end opacity-50">
                <flux:button x-data x-on:click="$flux.dark = ! $flux.dark" icon="moon" variant="subtle"
                             aria-label="Toggle dark mode"/>
            </div>

            {{ $slot }}
        </div>
    </div>

    <div class="flex-1 p-4 max-lg:hidden bg-[url('/resources/img/bg01.jpg')] bg-cover">
        <div class="h-full flex flex-col justify-center">
            <flux:heading class="text-center mb-3" size="xl">{{ __('messages.welcome') }}</flux:heading>
        </div>
    </div>
</div>

@fluxScripts

@persist('toast')
<flux:toast/>
@endpersist
</body>

</html>
