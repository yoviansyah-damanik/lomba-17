<div class="space-y-5">
    <div class="flex items-start justify-between gap-4">
        <div>
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ __('Riwayat Penjurian') }}</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $judge->name }} &middot; {{ $judge->username }}</p>
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
            @php $isComplete = $row->total > 0 && $row->submitted >= $row->total; @endphp
            <div class="p-3 bg-gray-50 dark:bg-gray-900/40 rounded-xl">
                <div class="flex items-start justify-between gap-3">
                    <div class="min-w-0">
                        <p class="font-medium text-gray-900 dark:text-gray-100 truncate">{{ $row->competition->name }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                            {{ $row->competition->start_time->translatedFormat('d M Y') }} &middot; {{ __('Kriteria') }}: {{ $row->criterion->name }}
                        </p>
                    </div>
                    <span class="shrink-0 inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold {{ $isComplete ? 'bg-green-100 text-green-800 dark:bg-green-900/40 dark:text-green-300' : 'bg-amber-100 text-amber-800 dark:bg-amber-900/40 dark:text-amber-300' }}">
                        {{ __(':submitted/:total dinilai', ['submitted' => $row->submitted, 'total' => $row->total]) }}
                    </span>
                </div>
            </div>
        @empty
            <p class="text-sm text-gray-500 dark:text-gray-400 p-2">{{ __('Juri ini belum ditugaskan ke kriteria manapun.') }}</p>
        @endforelse
    </div>
</div>
