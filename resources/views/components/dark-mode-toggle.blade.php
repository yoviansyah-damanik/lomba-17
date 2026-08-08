@props(['class' => 'h-9 w-9 text-gray-500 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600'])

<button
    type="button"
    x-data="{ dark: document.documentElement.classList.contains('dark') }"
    x-init="$watch('dark', value => {
        localStorage.setItem('theme', value ? 'dark' : 'light');
        document.documentElement.classList.toggle('dark', value);
    })"
    @click="dark = ! dark"
    :aria-label="dark ? '{{ __('Aktifkan mode terang') }}' : '{{ __('Aktifkan mode gelap') }}'"
    {{ $attributes->merge(['class' => 'inline-flex items-center justify-center rounded-full focus:outline-none focus:ring-2 focus:ring-red-500 transition ease-in-out duration-150 '.$class]) }}
>
    <svg x-show="! dark" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2m0 14v2m9-9h-2M5 12H3m15.364 6.364l-1.414-1.414M7.05 7.05L5.636 5.636m12.728 0l-1.414 1.414M7.05 16.95l-1.414 1.414M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
    </svg>
    <svg x-show="dark" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="display: none;">
        <path stroke-linecap="round" stroke-linejoin="round" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
    </svg>
</button>
