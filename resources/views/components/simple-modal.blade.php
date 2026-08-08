@props(['show' => false, 'maxWidth' => 'sm:max-w-md', 'showProperty' => 'showModal'])

@if ($show)
    <div class="fixed inset-0 z-50 overflow-y-auto px-4 py-6 sm:px-0">
        <div class="fixed inset-0 bg-gray-500/75 dark:bg-gray-900/75" wire:click="$set('{{ $showProperty }}', false)">
        </div>

        <div @class([
            'relative mb-6 bg-white dark:bg-gray-800 rounded-2xl shadow-xl w-full sm:mx-auto max-h-[85vh] overflow-y-auto p-6',
            $maxWidth,
        ])>
            {{ $slot }}
        </div>
    </div>
@endif
