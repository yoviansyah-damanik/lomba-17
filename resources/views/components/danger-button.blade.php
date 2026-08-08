<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center px-4 py-2 bg-white dark:bg-gray-800 border border-red-300 dark:border-red-800 rounded-lg font-semibold text-xs text-red-600 dark:text-red-400 uppercase tracking-widest shadow-sm hover:bg-red-50 dark:hover:bg-red-950/40 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
