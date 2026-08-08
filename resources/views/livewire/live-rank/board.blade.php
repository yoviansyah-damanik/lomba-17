@php
    $typeColors = [
        'SD' => 'bg-sky-100 text-sky-700 dark:bg-sky-900/40 dark:text-sky-300',
        'SMP' => 'bg-violet-100 text-violet-700 dark:bg-violet-900/40 dark:text-violet-300',
        'SMA' => 'bg-teal-100 text-teal-700 dark:bg-teal-900/40 dark:text-teal-300',
    ];
    $maxScore = $participants->max('total_score') ?: 1;
    $podium = $participants->take(3);
    $rest = $participants->slice(3);
@endphp

<div wire:poll.7s class="space-y-6">
    @if (!$competition)
        <div
            class="bg-white dark:bg-gray-800 shadow-sm ring-1 ring-gray-100 dark:ring-gray-700 rounded-2xl p-10 text-center text-gray-500 dark:text-gray-400">
            {{ __('Live Rank untuk lomba ini tidak tersedia.') }}
        </div>
    @else
        {{-- Hero header --}}
        <div
            class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-red-700 via-red-600 to-rose-800 px-6 py-8 text-white shadow-lg">
            <div class="absolute -right-8 -top-8 h-40 w-40 rounded-full bg-white/10"></div>
            <div class="absolute -bottom-10 -left-10 h-32 w-32 rounded-full bg-white/10"></div>
            <div class="relative">
                <div class="flex items-center gap-2 text-sm font-medium text-red-100">
                    @if ($competition->isActive())
                        <span class="h-2 w-2 rounded-full bg-green-400 animate-pulse"></span>
                        {{ __('Sedang berlangsung') }}
                    @else
                        {{ __('Live Rank') }}
                    @endif
                    @if ($maskIdentity)
                        <span
                            class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-white/20 text-xs font-medium">
                            {{ __('Identitas disamarkan') }}
                        </span>
                    @endif
                </div>
                <h1 class="mt-1 text-2xl sm:text-3xl font-extrabold tracking-tight">{{ $competition->name }}</h1>
                <p class="mt-1 text-sm text-red-100">
                    {{ __(':count peserta', ['count' => $participants->count()]) }}
                    &middot; {{ __('Diperbarui :time', ['time' => now()->format('H:i:s')]) }}
                </p>
            </div>
        </div>

        {{-- Filter --}}
        <div class="flex flex-wrap gap-2">
            @foreach (\App\Livewire\LiveRank\Board::SCHOOL_TYPES as $type)
                <button wire:click="$set('school_type', '{{ $type }}')"
                    class="px-3 py-1.5 rounded-full text-sm font-medium {{ $school_type === $type ? 'bg-red-600 text-white' : 'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 border border-gray-300 dark:border-gray-700' }}">
                    {{ $type }}
                </button>
            @endforeach
        </div>

        @if ($participants->isEmpty())
            <div
                class="bg-white dark:bg-gray-800 shadow-sm ring-1 ring-gray-100 dark:ring-gray-700 rounded-2xl p-10 text-center text-gray-500 dark:text-gray-400">
                {{ __('Belum ada peserta.') }}
            </div>
        @else
            {{-- Podium top 3 --}}
            <div class="flex items-end justify-center gap-3 sm:gap-6 pt-4">
                @foreach ([1 => $podium->get(1), 0 => $podium->get(0), 2 => $podium->get(2)] as $index => $participant)
                    @continue(!$participant)
                    @php
                        $rank = $index + 1;
                        $podiumStyle = match ($rank) {
                            1 => [
                                'height' => 'h-28 sm:h-36',
                                'ring' => 'ring-yellow-400',
                                'bg' => 'bg-gradient-to-b from-yellow-300 to-yellow-500',
                                'order' => 'order-2',
                                'avatar' => 'h-16 w-16 sm:h-20 sm:w-20 text-xl sm:text-2xl',
                                'emoji' => '🥇',
                            ],
                            2 => [
                                'height' => 'h-20 sm:h-28',
                                'ring' => 'ring-gray-300',
                                'bg' => 'bg-gradient-to-b from-gray-200 to-gray-400',
                                'order' => 'order-1',
                                'avatar' => 'h-14 w-14 sm:h-16 sm:w-16 text-lg sm:text-xl',
                                'emoji' => '🥈',
                            ],
                            3 => [
                                'height' => 'h-14 sm:h-20',
                                'ring' => 'ring-amber-500',
                                'bg' => 'bg-gradient-to-b from-amber-500 to-amber-700',
                                'order' => 'order-3',
                                'avatar' => 'h-14 w-14 sm:h-16 sm:w-16 text-lg sm:text-xl',
                                'emoji' => '🥉',
                            ],
                        };
                    @endphp
                    <div class="flex flex-col items-center {{ $podiumStyle['order'] }} w-24 sm:w-32">
                        <span class="text-2xl sm:text-3xl">{{ $podiumStyle['emoji'] }}</span>

                        <div
                            class="mt-1 flex items-center justify-center rounded-full {{ $podiumStyle['avatar'] }} ring-4 {{ $podiumStyle['ring'] }} bg-white dark:bg-gray-800 font-bold text-gray-700 dark:text-gray-200 shadow-md">
                            @if ($maskIdentity)
                                {{ $rank }}
                            @else
                                {{ Str::of($participant->displayName())->explode(' ')->reject(fn($w) => is_numeric($w))->map(fn($w) => Str::substr($w, 0, 1))->take(2)->implode('') }}
                            @endif
                        </div>

                        <p
                            class="mt-2 text-center text-sm font-semibold text-gray-900 dark:text-gray-100 truncate w-full">
                            {{ $maskIdentity ? __('Peserta :rank', ['rank' => $rank]) : $participant->displayName() }}
                        </p>
                        <span
                            class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-medium {{ $typeColors[$participant->school_type] }}">{{ $participant->school_type }}</span>
                        <p class="mt-1 text-lg font-extrabold text-red-600 dark:text-red-400">
                            {{ $participant->total_score ?? 0 }}</p>
                        @unless ($participant->is_complete)
                            <span class="inline-flex items-center px-1.5 py-0.5 rounded-full text-[9px] font-medium bg-amber-100 text-amber-700 dark:bg-amber-900/40 dark:text-amber-300"
                                title="{{ __('Belum semua juri menilai — nilai masih bisa berubah.') }}">
                                {{ __('Sementara') }}
                            </span>
                        @endunless

                        <div
                            class="mt-2 w-full {{ $podiumStyle['height'] }} rounded-t-lg {{ $podiumStyle['bg'] }} shadow-inner flex items-start justify-center pt-1">
                            <span class="text-white font-bold text-lg drop-shadow">{{ $rank }}</span>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Rank 4+ list --}}
            @if ($rest->isNotEmpty())
                <div
                    class="bg-white dark:bg-gray-800 shadow-sm ring-1 ring-gray-100 dark:ring-gray-700 rounded-2xl divide-y divide-gray-100 dark:divide-gray-700 overflow-hidden">
                    @foreach ($rest as $participant)
                        @php $rank = $loop->iteration + 3; @endphp
                        <div class="flex items-center gap-4 p-4">
                            <span
                                class="flex items-center justify-center h-9 w-9 rounded-full bg-gray-100 dark:bg-gray-700 text-sm font-bold text-gray-600 dark:text-gray-300 shrink-0">{{ $rank }}</span>

                            <div class="min-w-0 flex-1">
                                <div class="flex items-center gap-2">
                                    <p class="font-medium text-gray-900 dark:text-gray-100 truncate">
                                        {{ $maskIdentity ? __('Peserta :rank', ['rank' => $rank]) : $participant->displayName() }}
                                    </p>
                                    <span
                                        class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-medium {{ $typeColors[$participant->school_type] }}">{{ $participant->school_type }}</span>
                                </div>
                                @if (!$maskIdentity)
                                    <p class="text-xs text-gray-400 dark:text-gray-500">{{ $participant->npp }}</p>
                                @endif
                                <div
                                    class="mt-1.5 h-1.5 w-full max-w-xs rounded-full bg-gray-100 dark:bg-gray-700 overflow-hidden">
                                    <div class="h-full rounded-full bg-red-500"
                                        style="width: {{ max(4, (($participant->total_score ?? 0) / $maxScore) * 100) }}%">
                                    </div>
                                </div>
                            </div>

                            <span class="flex flex-col items-end shrink-0">
                                <span class="text-lg sm:text-xl font-bold text-red-600 dark:text-red-400">{{ $participant->total_score ?? 0 }}</span>
                                @unless ($participant->is_complete)
                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded-full text-[9px] font-medium bg-amber-100 text-amber-700 dark:bg-amber-900/40 dark:text-amber-300"
                                        title="{{ __('Belum semua juri menilai — nilai masih bisa berubah.') }}">
                                        {{ __('Sementara') }}
                                    </span>
                                @endunless
                            </span>
                        </div>
                    @endforeach
                </div>
            @endif
        @endif
    @endif
</div>
