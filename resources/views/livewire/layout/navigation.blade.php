<?php

use App\Livewire\Actions\Logout;
use Livewire\Volt\Component;

new class extends Component {
    /**
     * Log the current user out of the application.
     */
    public function logout(Logout $logout): void
    {
        $logout();

        $this->redirect('/', navigate: true);
    }
}; ?>

<nav x-data="{ open: false }"
    class="bg-[#fffbf7]/90 backdrop-blur-md sticky top-0 z-50 border-b border-stone-200/50 transition-all duration-300">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-20">
            <div class="flex items-center gap-8">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('home') }}" wire:navigate
                        class="font-serif text-2xl font-bold text-gray-900 tracking-tight flex items-center gap-2">
                        <span
                            class="w-8 h-8 bg-indigo-600 rounded-lg flex items-center justify-center text-white text-lg font-serif italic">S</span>
                        StoryVerse
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-2 sm:flex sm:items-center">
                    <x-nav-link :href="route('home')" :active="request()->routeIs('home')" wire:navigate
                        class="rounded-full px-4 py-2 hover:bg-stone-100 transition-colors text-center {{ request()->routeIs('home') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-600' }}">
                        {{ __('Discover') }}
                    </x-nav-link>
                    @auth
                        <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" wire:navigate
                            class="rounded-full px-4 py-2 hover:bg-stone-100 transition-colors {{ request()->routeIs('dashboard') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-600' }}">
                            {{ __('Dashboard') }}
                        </x-nav-link>
                    @endauth
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                @auth
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button
                                class="inline-flex items-center gap-2 px-3 py-2 border border-stone-200 text-sm leading-4 font-medium rounded-full text-gray-700 bg-white hover:bg-stone-50 hover:text-gray-900 focus:outline-none transition ease-in-out duration-150 shadow-sm">
                                <div
                                    class="w-6 h-6 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 font-bold text-xs">
                                    {{ substr(auth()->user()->name, 0, 1) }}
                                </div>
                                <div x-data="{{ json_encode(['name' => auth()->user()->name]) }}" x-text="name"
                                    x-on:profile-updated.window="name = $event.detail.name"></div>
                                <svg class="fill-current h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                        clip-rule="evenodd" />
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
                @else
                    <div class="flex items-center gap-2">
                        <a href="{{ route('login') }}"
                            class="px-5 py-2.5 rounded-full text-sm font-medium text-gray-700 hover:bg-stone-100 transition-colors"
                            wire:navigate>Log in</a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}"
                                class="px-5 py-2.5 rounded-full text-sm font-medium text-white bg-gray-900 hover:bg-gray-800 shadow-md hover:shadow-lg transition-all"
                                wire:navigate>Sign up</a>
                        @endif
                    </div>
                @endauth
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open"
                    class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out z-50 relative">
                    <div class="w-6 h-6 flex flex-col justify-center items-center">
                        <span class="bg-current block transition-all duration-300 ease-out h-0.5 w-6 rounded-sm"
                            :class="{ 'rotate-45 translate-y-1': open, '-translate-y-0.5': !open }"></span>
                        <span class="bg-current block transition-all duration-300 ease-out h-0.5 w-6 rounded-sm my-0.5"
                            :class="{ 'opacity-0': open, 'opacity-100': !open }"></span>
                        <span class="bg-current block transition-all duration-300 ease-out h-0.5 w-6 rounded-sm"
                            :class="{ '-rotate-45 -translate-y-1': open, 'translate-y-0.5': !open }"></span>
                    </div>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div x-show="open" x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 -translate-y-2"
        class="sm:hidden absolute top-20 left-0 w-full bg-white/95 backdrop-blur-xl border-b border-stone-200 shadow-xl z-40"
        style="display: none;">
        <div class="pt-2 pb-3 space-y-1 px-4">
            <x-responsive-nav-link :href="route('home')" :active="request()->routeIs('home')" wire:navigate class="rounded-lg">
                {{ __('Discover') }}
            </x-responsive-nav-link>
            @auth
                <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" wire:navigate class="rounded-lg">
                    {{ __('Dashboard') }}
                </x-responsive-nav-link>
            @endauth
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-4 border-t border-stone-100">
            @auth
                <div class="px-4">
                    <div class="font-medium text-base text-gray-800" x-data="{{ json_encode(['name' => auth()->user()->name]) }}" x-text="name"
                        x-on:profile-updated.window="name = $event.detail.name"></div>
                    <div class="font-medium text-sm text-gray-500">{{ auth()->user()->email }}</div>
                </div>

                <div class="mt-3 space-y-1 px-4">
                    <x-responsive-nav-link :href="route('profile')" wire:navigate class="rounded-lg">
                        {{ __('Profile') }}
                    </x-responsive-nav-link>

                    <!-- Authentication -->
                    <button wire:click="logout" class="w-full text-start">
                        <x-responsive-nav-link class="rounded-lg">
                            {{ __('Log Out') }}
                        </x-responsive-nav-link>
                    </button>
                </div>
            @else
                <div class="mt-3 space-y-2 px-4">
                    <x-responsive-nav-link :href="route('login')" wire:navigate class="rounded-lg text-center bg-stone-50">
                        {{ __('Log in') }}
                    </x-responsive-nav-link>

                    @if (Route::has('register'))
                        <x-responsive-nav-link :href="route('register')" wire:navigate
                            class="rounded-lg text-center bg-gray-900 text-white hover:bg-gray-800">
                            {{ __('Sign up') }}
                        </x-responsive-nav-link>
                    @endif
                </div>
            @endauth
        </div>
    </div>
</nav>
