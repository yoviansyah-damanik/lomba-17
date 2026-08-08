<?php

use App\Livewire\Actions\Logout;
use Livewire\Volt\Component;

new class extends Component
{
    /**
     * Log the current user out of the application.
     */
    public function logout(Logout $logout): void
    {
        $logout();

        $this->redirect('/', navigate: true);
    }
}; ?>

<div>
<nav class="fixed top-0 inset-x-0 z-40 bg-white/90 dark:bg-gray-800/90 backdrop-blur border-b border-gray-100 dark:border-gray-700">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16 gap-3">
            <div class="flex items-center min-w-0">
                <!-- Logo + Title -->
                <a href="{{ route('dashboard') }}" wire:navigate class="flex items-center gap-2 shrink-0">
                    <x-application-logo class="block h-9 w-9 rounded-full ring-2 ring-red-100 dark:ring-red-900/50" />
                    <span class="font-bold text-gray-800 dark:text-gray-100 truncate max-w-[9rem] sm:max-w-none">{{ config('app.name') }}</span>
                </a>

                <!-- Navigation Links -->
                <div class="hidden sm:flex space-x-6 sm:ms-8">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" wire:navigate>
                        {{ __('Dashboard') }}
                    </x-nav-link>

                    @if (auth()->user()->isAdmin())
                        <x-nav-link :href="route('admin.competitions')" :active="request()->routeIs('admin.competitions')" wire:navigate>
                            {{ __('Lomba & Kriteria') }}
                        </x-nav-link>
                        <x-nav-link :href="route('admin.judges')" :active="request()->routeIs('admin.judges')" wire:navigate>
                            {{ __('Juri') }}
                        </x-nav-link>
                        <x-nav-link :href="route('admin.participants')" :active="request()->routeIs('admin.participants')" wire:navigate>
                            {{ __('Peserta') }}
                        </x-nav-link>
                    @else
                        <x-nav-link :href="route('evaluation')" :active="request()->routeIs('evaluation')" wire:navigate>
                            {{ __('Penilaian') }}
                        </x-nav-link>
                    @endif
                </div>
            </div>

            <div class="flex items-center gap-4 shrink-0">
                <!-- Live date & time -->
                <div
                    x-data="{
                        now: new Date(),
                        tick: null,
                        init() {
                            this.tick = setInterval(() => { this.now = new Date() }, 1000);
                        },
                        destroy() { clearInterval(this.tick) },
                        get formatted() {
                            if (window.innerWidth >= 640) {
                                return this.now.toLocaleString('id-ID', {
                                    day: '2-digit', month: 'short', year: 'numeric',
                                    hour: '2-digit', minute: '2-digit', second: '2-digit',
                                });
                            }
                            return this.now.toLocaleString('id-ID', {
                                day: '2-digit', month: 'short',
                                hour: '2-digit', minute: '2-digit',
                            });
                        }
                    }"
                    x-text="formatted"
                    class="text-[11px] sm:text-sm font-medium text-gray-500 dark:text-gray-400 tabular-nums whitespace-nowrap"
                ></div>

                <x-dark-mode-toggle class="h-8 w-8 sm:h-9 sm:w-9 text-gray-500 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600" />

                <!-- Settings Dropdown (desktop only — mobile uses the Profil tab in the bottom bar) -->
                <div class="hidden sm:flex sm:items-center">
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center gap-2 px-3 py-2 rounded-full text-sm font-medium text-gray-600 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 focus:outline-none transition ease-in-out duration-150">
                                <span class="flex items-center justify-center h-6 w-6 rounded-full bg-red-600 text-white text-xs font-bold uppercase">{{ Str::substr(auth()->user()->name, 0, 1) }}</span>
                                <span x-data="{{ json_encode(['name' => auth()->user()->name]) }}" x-text="name" x-on:profile-updated.window="name = $event.detail.name"></span>

                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <x-dropdown-link :href="route('profile')" wire:navigate>
                                {{ __('Profile') }}
                            </x-dropdown-link>

                            <!-- Authentication -->
                            <button wire:click="logout" class="w-full text-start">
                                <x-dropdown-link>
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </button>
                        </x-slot>
                    </x-dropdown>
                </div>
            </div>
        </div>
    </div>
</nav>

<!-- Mobile bottom tab bar -->
<nav class="sm:hidden fixed bottom-0 inset-x-0 z-40 bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700 pb-[env(safe-area-inset-bottom)]">
    <div class="grid {{ auth()->user()->isAdmin() ? 'grid-cols-5' : 'grid-cols-3' }}">
        @php
            $tabClasses = fn (bool $active) => 'flex flex-col items-center justify-center gap-0.5 py-2.5 text-[11px] font-medium w-full '
                .($active ? 'text-red-600 dark:text-red-400' : 'text-gray-500 dark:text-gray-400');
        @endphp

        <a href="{{ route('dashboard') }}" wire:navigate class="{{ $tabClasses(request()->routeIs('dashboard')) }}">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l9-9 9 9M5 10v10a1 1 0 001 1h4a1 1 0 001-1v-4h2v4a1 1 0 001 1h4a1 1 0 001-1V10" />
            </svg>
            {{ __('Dashboard') }}
        </a>

        @if (auth()->user()->isAdmin())
            <a href="{{ route('admin.competitions') }}" wire:navigate class="{{ $tabClasses(request()->routeIs('admin.competitions')) }}">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 21h8m-4-4v4M6 4h12v3a6 6 0 01-12 0V4zM6 6H4a2 2 0 002 4M18 6h2a2 2 0 01-2 4" />
                </svg>
                {{ __('Lomba') }}
            </a>
            <a href="{{ route('admin.judges') }}" wire:navigate class="{{ $tabClasses(request()->routeIs('admin.judges')) }}">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m5-2.13a4 4 0 100-8 4 4 0 000 8zm6 1a4 4 0 00-3-3.87" />
                </svg>
                {{ __('Juri') }}
            </a>
            <a href="{{ route('admin.participants') }}" wire:navigate class="{{ $tabClasses(request()->routeIs('admin.participants')) }}">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.42A9.99 9.99 0 0121 12c0 2.21-1.79 4-4 4H7c-2.21 0-4-1.79-4-4 0-1.34.53-2.6 1.84-3.42L12 14zm0 0v7" />
                </svg>
                {{ __('Peserta') }}
            </a>
        @else
            <a href="{{ route('evaluation') }}" wire:navigate class="{{ $tabClasses(request()->routeIs('evaluation')) }}">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                </svg>
                {{ __('Penilaian') }}
            </a>
        @endif

        <!-- Profil (dropdown, opens upward — same visual style as the other tabs) -->
        <div class="relative" x-data="{ open: false }" @click.outside="open = false">
            <button type="button" @click="open = ! open" class="{{ $tabClasses(request()->routeIs('profile')) }}">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                {{ __('Profil') }}
            </button>

            <div x-show="open"
                x-transition:enter="transition ease-out duration-150"
                x-transition:enter-start="opacity-0 translate-y-1"
                x-transition:enter-end="opacity-100 translate-y-0"
                x-transition:leave="transition ease-in duration-100"
                x-transition:leave-start="opacity-100 translate-y-0"
                x-transition:leave-end="opacity-0 translate-y-1"
                class="absolute bottom-full right-0 mb-2 w-48 rounded-xl shadow-lg ring-1 ring-black/5 bg-white dark:bg-gray-700 overflow-hidden"
                style="display: none;"
                @click="open = false">
                <div class="px-4 py-3 border-b border-gray-100 dark:border-gray-600 flex items-center gap-3">
                    <span class="flex items-center justify-center h-8 w-8 rounded-full bg-red-600 text-white text-xs font-bold uppercase shrink-0">{{ Str::substr(auth()->user()->name, 0, 1) }}</span>
                    <div class="min-w-0 text-start">
                        <div class="font-medium text-sm text-gray-800 dark:text-gray-200 truncate">{{ auth()->user()->name }}</div>
                        <div class="text-xs text-gray-500 truncate">{{ auth()->user()->username }}</div>
                    </div>
                </div>

                <x-dropdown-link :href="route('profile')" wire:navigate>
                    {{ __('Profile') }}
                </x-dropdown-link>

                <button wire:click="logout" class="w-full text-start">
                    <x-dropdown-link>
                        {{ __('Log Out') }}
                    </x-dropdown-link>
                </button>
            </div>
        </div>
    </div>
</nav>
</div>
