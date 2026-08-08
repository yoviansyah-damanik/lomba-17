<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        @include('partials.theme-init')

        <title>{{ __('Live Rank') }} - {{ config('app.name') }}</title>
        <link rel="icon" href="{{ Vite::asset('resources/images/logo.png') }}">

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-gray-100 dark:bg-gray-900">
        <div class="min-h-screen">
            <header class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700">
                <div class="max-w-4xl mx-auto py-3 px-4 sm:px-6 lg:px-8 flex items-center justify-between gap-2">
                    <div class="flex items-center gap-2">
                        <img src="{{ Vite::asset('resources/images/logo.png') }}" alt="{{ config('app.name') }}" class="h-8 w-8 rounded-full ring-2 ring-red-100 dark:ring-red-900/50 object-contain">
                        <span class="text-sm font-semibold text-gray-700 dark:text-gray-200">{{ config('app.name') }}</span>
                    </div>
                    <x-dark-mode-toggle class="h-8 w-8 text-gray-500 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600" />
                </div>
            </header>

            <main class="max-w-4xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                <livewire:live-rank.board :competition-id="$competitionId" />
            </main>
        </div>
    </body>
</html>
