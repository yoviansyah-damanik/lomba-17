<div class="space-y-6">
    @if ($competitions->isEmpty())
        <div
            class="bg-white dark:bg-gray-800 shadow-sm ring-1 ring-gray-100 dark:ring-gray-700 rounded-2xl p-4 sm:p-6 text-sm text-gray-600 dark:text-gray-400">
            {{ __('Anda belum memiliki kriteria yang ditugaskan pada lomba yang sedang berlangsung.') }}
        </div>
    @else
        @if ($justSaved)
            <div class="rounded-2xl bg-green-50 dark:bg-green-900/40 p-4 text-sm text-green-700 dark:text-green-300">
                {{ __('Penilaian tersimpan. Siap cari peserta berikutnya.') }}
            </div>
        @endif

        @if ($searched && $registration)
            <div
                class="sticky top-16 z-30 -mx-4 sm:mx-0 px-4 sm:px-0 py-2 bg-gray-100/95 dark:bg-gray-900/95 backdrop-blur">
                <div
                    class="bg-white dark:bg-gray-800 shadow-sm ring-1 ring-gray-100 dark:ring-gray-700 rounded-2xl p-3 sm:p-4 flex items-center gap-3">
                    <button type="button" wire:click="resetSearch"
                        class="shrink-0 h-10 w-10 rounded-full flex items-center justify-center text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700"
                        aria-label="{{ __('Kembali ke pencarian') }}">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                    </button>
                    <div class="min-w-0 flex-1">
                        <p class="font-semibold text-gray-900 dark:text-gray-100 truncate">
                            {{ $registration->displayName() }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ $registration->npp }} &middot;
                            {{ $registration->school_type }}</p>
                    </div>
                </div>
            </div>
        @else
            <div
                class="bg-white dark:bg-gray-800 shadow-sm ring-1 ring-gray-100 dark:ring-gray-700 rounded-2xl p-4 sm:p-6">
                <form wire:submit="search" class="space-y-4" autocomplete="off">
                    <div>
                        <x-input-label for="competitionId" :value="__('Lomba')" />
                        <select wire:model="competitionId" id="competitionId" autocomplete="off"
                            class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-red-500 focus:ring-red-500 rounded-md shadow-sm mt-1 block w-full">
                            <option value="">{{ __('Pilih lomba') }}</option>
                            @foreach ($competitions as $item)
                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('competitionId')" class="mt-2" />
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div class="sm:col-span-2">
                            <x-input-label for="npp" :value="__('NPP')" />
                            <x-text-input wire:model="npp" id="npp" class="block mt-1 w-full" type="text"
                                required autocomplete="off" />
                            <x-input-error :messages="$errors->get('npp')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="school_type" :value="__('Jenis Sekolah')" />
                            <select wire:model="school_type" id="school_type" autocomplete="off"
                                class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-red-500 focus:ring-red-500 rounded-md shadow-sm mt-1 block w-full">
                                <option value="SD">SD</option>
                                <option value="SMP">SMP</option>
                                <option value="SMA">SMA</option>
                            </select>
                        </div>
                    </div>

                    <div class="flex justify-end gap-2">
                        @if ($searched)
                            <x-secondary-button type="button"
                                wire:click="resetSearch">{{ __('Reset') }}</x-secondary-button>
                        @endif
                        <x-primary-button type="submit">{{ __('Cari Peserta') }}</x-primary-button>
                    </div>
                </form>
            </div>
        @endif

        @if ($searched)
            @if ($registration)
                <livewire:evaluation.criterion-form :registration="$registration" :competition-id="$competitionId" :key="'criterion-form-' . $registration->id" />
            @else
                <div
                    class="bg-white dark:bg-gray-800 shadow-sm ring-1 ring-gray-100 dark:ring-gray-700 rounded-2xl p-4 sm:p-6 text-sm text-gray-600 dark:text-gray-400">
                    {{ __('Peserta dengan NPP dan jenis sekolah tersebut tidak ditemukan.') }}
                </div>
            @endif
        @endif
    @endif
</div>
