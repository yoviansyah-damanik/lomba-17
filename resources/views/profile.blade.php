<x-app-layout>
    <x-slot name="header">
        <h2 class="text-lg font-semibold leading-tight text-gray-800 sm:text-xl dark:text-gray-200">
            {{ __('Profil') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="px-4 mx-auto space-y-6 max-w-7xl sm:px-6 lg:px-8">
            <div
                class="p-4 bg-white shadow-sm sm:p-8 dark:bg-gray-800 ring-1 ring-gray-100 dark:ring-gray-700 rounded-2xl">
                <div class="max-w-xl">
                    <livewire:profile.update-profile-information-form />
                </div>
            </div>

            <div
                class="p-4 bg-white shadow-sm sm:p-8 dark:bg-gray-800 ring-1 ring-gray-100 dark:ring-gray-700 rounded-2xl">
                <div class="max-w-xl">
                    <livewire:profile.update-password-form />
                </div>
            </div>

            {{-- <div class="p-4 bg-white shadow-sm sm:p-8 dark:bg-gray-800 ring-1 ring-gray-100 dark:ring-gray-700 rounded-2xl">
                <div class="max-w-xl">
                    <livewire:profile.delete-user-form />
                </div>
            </div> --}}
        </div>
    </div>
</x-app-layout>
