<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-lg sm:text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            @if (auth()->user()->isAdmin())
                <livewire:dashboard.admin-summary />
            @else
                <livewire:dashboard.judge-progress />
            @endif
        </div>
    </div>
</x-app-layout>
