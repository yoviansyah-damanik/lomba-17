<div class="space-y-6">
    <!-- Hero stat -->
    <div class="bg-white dark:bg-gray-800 shadow-sm ring-1 ring-gray-100 dark:ring-gray-700 rounded-2xl p-4 sm:p-5">
        <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Total Penilaian Selesai') }}</p>
        <p class="mt-1 text-3xl sm:text-4xl font-bold text-gray-900 dark:text-gray-100">
            {{ $totalCompleted }} <span class="text-base sm:text-lg font-medium text-gray-400">/ {{ $totalAssigned }}</span>
        </p>
        <div class="mt-3 h-2 w-full rounded-full bg-red-100 dark:bg-red-900/30 overflow-hidden">
            <div class="h-full rounded-full bg-red-500" style="width: {{ $totalAssigned > 0 ? min(100, round(($totalCompleted / $totalAssigned) * 100)) : 0 }}%"></div>
        </div>
    </div>

    <!-- Trend -->
    <div class="bg-white dark:bg-gray-800 shadow-sm ring-1 ring-gray-100 dark:ring-gray-700 rounded-2xl p-4 sm:p-5">
        <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-4">{{ __('Aktivitas Penilaian Anda (7 Hari Terakhir)') }}</h3>
        <x-charts.trend-bar :data="$trend" />
    </div>

    <!-- Progress per kriteria -->
    @php
        $typeColors = [
            'SD' => 'bg-sky-100 text-sky-700 dark:bg-sky-900/40 dark:text-sky-300',
            'SMP' => 'bg-violet-100 text-violet-700 dark:bg-violet-900/40 dark:text-violet-300',
            'SMA' => 'bg-teal-100 text-teal-700 dark:bg-teal-900/40 dark:text-teal-300',
        ];
    @endphp
    <div class="bg-white dark:bg-gray-800 shadow-sm ring-1 ring-gray-100 dark:ring-gray-700 rounded-2xl p-4 sm:p-5">
        <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-4">{{ __('Progres per Kriteria') }}</h3>
        @if ($criteria->isEmpty())
            <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Anda belum memiliki kriteria yang ditugaskan.') }}</p>
        @else
            <div class="space-y-4">
                @foreach ($criteria as $criterion)
                    <div>
                        <x-charts.meter :value="$criterion->evaluations_count" :max="$criterion->registrations_count">
                            <x-slot:label>
                                <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-medium {{ $typeColors[$criterion->assignment_school_type] }}">
                                    {{ $criterion->assignment_school_type }}
                                </span>
                                {{ $criterion->name }}
                            </x-slot:label>
                        </x-charts.meter>
                        <p class="mt-1 text-xs text-gray-400 dark:text-gray-500">
                            {{ $criterion->competition->name }}
                            @if ($criterion->competition->isActive())
                                <span class="ms-1 inline-flex items-center px-1.5 py-0.5 rounded-full text-[10px] font-medium bg-green-100 text-green-800 dark:bg-green-900/40 dark:text-green-300">{{ __('Berlangsung') }}</span>
                            @endif
                        </p>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
