@props(['data' => []])

@php
    $max = max(1, ...array_values($data ?: [0]));
    $peak = array_search($max, $data, true);
    $lastKey = array_key_last($data);
@endphp

<div class="flex items-end gap-2 h-28">
    @foreach ($data as $label => $value)
        <div class="flex-1 flex flex-col items-center gap-1.5 min-w-0">
            <span class="text-[11px] font-semibold text-gray-700 dark:text-gray-300 {{ $label === $peak || $label === $lastKey ? '' : 'invisible' }}">{{ $value }}</span>
            <div
                class="w-full max-w-[22px] mx-auto rounded-t bg-red-500 dark:bg-red-500"
                style="height: {{ $value > 0 ? max(4, ($value / $max) * 80) : 2 }}px"
                title="{{ $label }}: {{ $value }}"
            ></div>
            <span class="text-[10px] text-gray-400 dark:text-gray-500 whitespace-nowrap">{{ $label }}</span>
        </div>
    @endforeach
</div>
