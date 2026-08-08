@props(['rank'])

@php
    $medal = match ($rank) {
        1 => ['bg' => 'bg-gradient-to-br from-yellow-300 to-yellow-500', 'ring' => 'ring-yellow-400', 'emoji' => '🥇'],
        2 => ['bg' => 'bg-gradient-to-br from-gray-200 to-gray-400', 'ring' => 'ring-gray-300', 'emoji' => '🥈'],
        3 => ['bg' => 'bg-gradient-to-br from-amber-500 to-amber-700', 'ring' => 'ring-amber-500', 'emoji' => '🥉'],
        default => null,
    };
@endphp

@if ($medal)
    <div class="flex items-center justify-center h-12 w-12 rounded-full {{ $medal['bg'] }} ring-2 {{ $medal['ring'] }} shadow-sm shrink-0">
        <span class="text-2xl leading-none">{{ $medal['emoji'] }}</span>
    </div>
@else
    <div class="flex items-center justify-center h-12 w-12 rounded-full bg-gray-100 dark:bg-gray-700 shrink-0">
        <span class="text-lg font-bold text-gray-600 dark:text-gray-300">{{ $rank }}</span>
    </div>
@endif
