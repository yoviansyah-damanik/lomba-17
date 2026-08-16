<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-lg sm:text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Rekap Penilaian Saya') }}
            </h2>
            <a href="{{ route('evaluation') }}" class="text-sm font-medium text-red-600 dark:text-red-400 hover:text-red-700 dark:hover:text-red-300">
                &larr; {{ __('Kembali ke Penilaian') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <livewire:evaluation.recap />
        </div>
    </div>
</x-app-layout>
