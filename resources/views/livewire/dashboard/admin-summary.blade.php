<div class="space-y-6">
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        <div class="bg-white dark:bg-gray-800 shadow-sm ring-1 ring-gray-100 dark:ring-gray-700 rounded-2xl p-4">
            <div class="flex items-center justify-center h-9 w-9 rounded-full bg-sky-100 dark:bg-sky-900/40 text-sky-600 dark:text-sky-300 mb-3">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m5-2.13a4 4 0 100-8 4 4 0 000 8zm6 1a4 4 0 00-3-3.87" />
                </svg>
            </div>
            <p class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $judgeCount }}</p>
            <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Juri') }}</p>
        </div>

        <div class="bg-white dark:bg-gray-800 shadow-sm ring-1 ring-gray-100 dark:ring-gray-700 rounded-2xl p-4">
            <div class="flex items-center justify-center h-9 w-9 rounded-full bg-violet-100 dark:bg-violet-900/40 text-violet-600 dark:text-violet-300 mb-3">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <p class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $criterionCount }}</p>
            <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Kriteria') }}</p>
        </div>

        <div class="bg-white dark:bg-gray-800 shadow-sm ring-1 ring-gray-100 dark:ring-gray-700 rounded-2xl p-4">
            <div class="flex items-center justify-center h-9 w-9 rounded-full bg-amber-100 dark:bg-amber-900/40 text-amber-600 dark:text-amber-300 mb-3">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.42A9.99 9.99 0 0121 12c0 2.21-1.79 4-4 4H7c-2.21 0-4-1.79-4-4 0-1.34.53-2.6 1.84-3.42L12 14zm0 0v7" />
                </svg>
            </div>
            <p class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $participantsByType->sum() }}</p>
            <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Peserta') }}</p>
            <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">
                @foreach (['SD', 'SMP', 'SMA'] as $type)
                    {{ $type }}: {{ $participantsByType[$type] ?? 0 }}@if (! $loop->last), @endif
                @endforeach
            </p>
        </div>

        <div class="bg-white dark:bg-gray-800 shadow-sm ring-1 ring-gray-100 dark:ring-gray-700 rounded-2xl p-4">
            <div class="flex items-center justify-center h-9 w-9 rounded-full bg-red-100 dark:bg-red-900/40 text-red-600 dark:text-red-300 mb-3">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                </svg>
            </div>
            <p class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $evaluationCount }} <span class="text-sm sm:text-base font-medium text-gray-400">/ {{ $expectedEvaluations }}</span></p>
            <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Progres Penilaian') }}</p>
        </div>
    </div>

    <!-- Trend -->
    <div class="bg-white dark:bg-gray-800 shadow-sm ring-1 ring-gray-100 dark:ring-gray-700 rounded-2xl p-4 sm:p-5">
        <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-4">{{ __('Aktivitas Penilaian (7 Hari Terakhir)') }}</h3>
        <x-charts.trend-bar :data="$trend" />
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Progress per lomba -->
        <div class="bg-white dark:bg-gray-800 shadow-sm ring-1 ring-gray-100 dark:ring-gray-700 rounded-2xl p-4 sm:p-5">
            <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-4">{{ __('Progres per Lomba') }}</h3>
            @if ($competitionProgress->isEmpty())
                <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Belum ada lomba dengan kriteria.') }}</p>
            @else
                <div class="space-y-4">
                    @foreach ($competitionProgress as $row)
                        <x-charts.meter :label="$row['label']" :value="$row['value']" :max="$row['max']" />
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Distribusi peserta -->
        <div class="bg-white dark:bg-gray-800 shadow-sm ring-1 ring-gray-100 dark:ring-gray-700 rounded-2xl p-4 sm:p-5">
            <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-4">{{ __('Distribusi Peserta per Jenis Sekolah') }}</h3>
            @php
                $typeColors = ['SD' => 'bg-sky-500', 'SMP' => 'bg-violet-500', 'SMA' => 'bg-teal-500'];
                $typeTotal = max(1, $participantsByType->sum());
            @endphp
            <div class="flex h-3 w-full rounded-full overflow-hidden gap-0.5 bg-gray-100 dark:bg-gray-700">
                @foreach (['SD', 'SMP', 'SMA'] as $type)
                    @php $count = $participantsByType[$type] ?? 0; @endphp
                    @if ($count > 0)
                        <div class="{{ $typeColors[$type] }}" style="width: {{ ($count / $typeTotal) * 100 }}%"></div>
                    @endif
                @endforeach
            </div>
            <div class="mt-4 space-y-2">
                @foreach (['SD', 'SMP', 'SMA'] as $type)
                    @php $count = $participantsByType[$type] ?? 0; @endphp
                    <div class="flex items-center justify-between text-sm">
                        <span class="flex items-center gap-2 text-gray-700 dark:text-gray-300">
                            <span class="h-2.5 w-2.5 rounded-full {{ $typeColors[$type] }} shrink-0"></span>
                            {{ $type }}
                        </span>
                        <span class="text-gray-500 dark:text-gray-400 tabular-nums">{{ $count }} ({{ $typeTotal > 0 ? round(($count / $typeTotal) * 100) : 0 }}%)</span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Progress per judge -->
    <div class="bg-white dark:bg-gray-800 shadow-sm ring-1 ring-gray-100 dark:ring-gray-700 rounded-2xl p-4 sm:p-5">
        <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-4">{{ __('Progres per Juri') }}</h3>
        @if ($judgeProgress->isEmpty())
            <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Belum ada juri dengan kriteria ditugaskan.') }}</p>
        @else
            <div class="space-y-4">
                @foreach ($judgeProgress as $row)
                    <x-charts.meter :label="$row['label']" :value="$row['value']" :max="$row['max']" />
                @endforeach
            </div>
        @endif
    </div>
</div>
