@props(['href' => null, 'variant' => 'neutral', 'target' => null])

@php
    $base = 'inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold shadow-sm transition ease-in-out duration-150 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900';
    $variants = [
        'neutral' => 'bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700',
        'danger' => 'bg-white dark:bg-gray-800 border border-red-300 dark:border-red-800 text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-950/40',
    ];
    $classes = $base.' '.$variants[$variant];
@endphp

@if ($href)
    <a href="{{ $href }}" @if ($target) target="{{ $target }}" @endif {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </a>
@else
    <button type="button" {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </button>
@endif
