@use(App\Enums\Can)
    <!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" href="{{ Vite::asset('resources/img/favicons/favicon.ico') }}">
    <title>{{ isset($title) ? $title . ' - ' . config('app.name') : config('app.name') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @fluxAppearance
</head>

<body class="min-h-screen bg-white dark:bg-zinc-800">

@if (session('impersonate'))
    <livewire:admin.users.stop-impersonate/>
@endif

@include('parts.sidebar')
@include('parts.header')

<flux:main class="lg:!p-4 !p-4">
    {{ $slot }}
</flux:main>


<livewire:auth.logout/>
@fluxScripts

@persist('toast')
<flux:toast/>
@endpersist
</body>

</html>
