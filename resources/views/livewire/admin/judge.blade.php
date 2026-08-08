<div>
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ __('Daftar Juri') }}</h3>
        <x-primary-button wire:click="create">{{ __('Tambah Juri') }}</x-primary-button>
    </div>

    <div class="mb-4">
        <div class="relative w-full sm:w-64">
            <input type="search" wire:model.live.debounce.400ms="search"
                placeholder="{{ __('Cari nama atau username...') }}"
                class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-red-500 focus:ring-red-500 rounded-lg shadow-sm w-full text-sm">
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 shadow-sm ring-1 ring-gray-100 dark:ring-gray-700 rounded-2xl divide-y divide-gray-200 dark:divide-gray-700">
        @forelse ($judges as $judge)
            <div class="flex items-center justify-between p-4 gap-4">
                <div class="min-w-0">
                    <p class="font-medium text-gray-900 dark:text-gray-100 truncate">{{ $judge->name }}</p>
                    <p class="text-sm text-gray-500 dark:text-gray-400 truncate">{{ $judge->username }}</p>
                </div>
                <div class="flex items-center gap-2 shrink-0">
                    <x-action-button wire:click="viewHistory('{{ $judge->id }}')">
                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        {{ __('Riwayat') }}
                    </x-action-button>
                    <x-action-button wire:click="edit('{{ $judge->id }}')">
                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        {{ __('Edit') }}
                    </x-action-button>
                    <x-action-button variant="danger" wire:click="delete('{{ $judge->id }}')"
                        wire:confirm="{{ __('Hapus juri ini?') }}">
                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        {{ __('Hapus') }}
                    </x-action-button>
                </div>
            </div>
        @empty
            <p class="p-4 text-sm text-gray-500 dark:text-gray-400">
                {{ $search ? __('Tidak ada juri yang cocok dengan pencarian.') : __('Belum ada juri terdaftar.') }}
            </p>
        @endforelse
    </div>

    <div class="mt-4">
        {{ $judges->links() }}
    </div>

    <x-simple-modal :show="$showModal">
        <form wire:submit="save" class="space-y-4">
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                {{ $editing ? __('Edit Juri') : __('Tambah Juri') }}
            </h2>

            <div>
                <x-input-label for="name" :value="__('Nama')" />
                <x-text-input wire:model="name" id="name" class="block mt-1 w-full" type="text" required autofocus />
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="username" :value="__('Username')" />
                <x-text-input wire:model="username" id="username" class="block mt-1 w-full" type="text" required />
                <x-input-error :messages="$errors->get('username')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="password" :value="__('Password')" />
                <x-text-input wire:model="password" id="password" class="block mt-1 w-full" type="password" placeholder="{{ $editing ? __('Kosongkan jika tidak diubah') : '' }}" />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <div class="flex justify-end gap-2">
                <x-secondary-button type="button" wire:click="$set('showModal', false)">{{ __('Batal') }}</x-secondary-button>
                <x-primary-button type="submit">{{ __('Simpan') }}</x-primary-button>
            </div>
        </form>
    </x-simple-modal>

    <x-simple-modal :show="$showHistoryModal" show-property="showHistoryModal">
        @if ($viewingHistoryFor)
            <livewire:admin.judge-history :judge-id="$viewingHistoryFor" :key="$viewingHistoryFor" lazy />
        @endif
    </x-simple-modal>
</div>
