@props(['label', 'value' => 0, 'max' => 0])

@php
    $pct = $max > 0 ? min(100, round(($value / $max) * 100)) : 0;
@endphp

<div>
    <div class="flex items-center justify-between gap-2 text-sm mb-1">
        <span class="font-medium text-gray-700 dark:text-gray-300 truncate">{!! $label !!}</span>
        <span class="text-gray-500 dark:text-gray-400 shrink-0 tabular-nums">{{ $value }}/{{ $max }} &middot; {{ $pct }}%</span>
    </div>
    <div class="h-2 w-full rounded-full bg-red-100 dark:bg-red-900/30 overflow-hidden">
        <div class="h-full rounded-full bg-red-500" style="width: {{ $pct }}%"></div>
    </div>
</div>
