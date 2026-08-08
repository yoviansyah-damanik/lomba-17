<div class="space-y-5">
    <div class="flex items-start justify-between gap-4">
        <div>
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ __('Riwayat Lomba') }}</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $participant->school_name }} &middot; {{ $participant->school_type }}</p>
        </div>
        <button type="button" wire:click="$parent.closeHistory()"
            class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>

    <div class="space-y-2 max-h-96 overflow-y-auto">
        @forelse ($rows as $row)
            <div class="p-3 bg-gray-50 dark:bg-gray-900/40 rounded-xl">
                <div class="flex items-start justify-between gap-3">
                    <div class="min-w-0">
                        <p class="font-medium text-gray-900 dark:text-gray-100 truncate">{{ $row->competition->name }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                            {{ $row->competition->start_time->translatedFormat('d M Y') }} &middot; {{ __('NPP') }}: {{ $row->npp }}
                        </p>
                    </div>
                    @if ($row->rank)
                        <span class="shrink-0 inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-red-100 text-red-700 dark:bg-red-900/40 dark:text-red-300">
                            {{ __('Peringkat :rank/:total', ['rank' => $row->rank, 'total' => $row->total_peers]) }}
                        </span>
                    @endif
                </div>
                <p class="mt-1.5 text-sm text-gray-700 dark:text-gray-300">
                    {{ __('Total nilai') }}: <span class="font-semibold">{{ $row->total_score }}</span>
                </p>
            </div>
        @empty
            <p class="text-sm text-gray-500 dark:text-gray-400 p-2">{{ __('Peserta ini belum pernah didaftarkan ke lomba manapun.') }}</p>
        @endforelse
    </div>
</div>
