<?php

use App\Livewire\Actions\Logout;
use Livewire\Volt\Component;

// ✅ PHP logic is 100% unchanged.
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

<nav x-data="{ open: false }" class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" wire:navigate>
                        <img src="{{ asset('logo\logo.png') }}" alt="Your Company Name" class="block h-12 w-auto">
                    </a>
                </div>

                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="text-gray-700 dark:text-gray-300" wire:navigate>
                        {{ __('Dashboard') }}
                    </x-nav-link>
                    <x-nav-link :href="route('users')" :active="request()->routeIs('users')" class="text-gray-700 dark:text-gray-300">
                        {{ __('Users') }}
                    </x-nav-link>
                    <x-nav-link :href="route('friends')" :active="request()->routeIs('friends')" class="text-gray-700 dark:text-gray-300">
                        {{ __('Friends') }}
                    </x-nav-link>
                    <x-nav-link :href="route('chat')" :active="request()->routeIs('chat')" class="text-gray-700 dark:text-gray-300">
                        {{ __('Chat') }}
                    </x-nav-link>
                </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:ms-6 space-x-4">

                <a href="{{ route('profile') }}" wire:navigate class="inline-flex items-center text-sm font-medium text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-gray-100 transition-colors rounded-lg p-2 hover:bg-gray-100 dark:hover:bg-gray-700">
                    @if (Auth::user()->profile_photo_path)
                        <img class="h-8 w-8 rounded-full object-cover me-2" src="{{ asset('storage/' . Auth::user()->profile_photo_path) }}" alt="{{ Auth::user()->name }}" />
                    @else
                        <div class="h-8 w-8 rounded-full bg-gray-200 dark:bg-gray-600 flex items-center justify-center me-2 shrink-0">
                            <span class="text-sm font-bold text-gray-500 dark:text-gray-300">{{ substr(Auth::user()->name, 0, 1) }}</span>
                        </div>
                    @endif
                    <div class="truncate" x-data="{{ json_encode(['name' => auth()->user()->name]) }}" x-text="name" x-on:profile-updated.window="name = $event.detail.name"></div>
                </a>



                <button wire:click="logout" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-red-500 dark:text-red-400 bg-red-50 dark:bg-red-900/30 hover:bg-red-100 dark:hover:bg-red-900/50 focus:outline-none transition ease-in-out duration-150">
                    <svg class="w-4 h-4 me-1.5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                      <path fill-rule="evenodd" d="M3 4.25A2.25 2.25 0 015.25 2h5.5A2.25 2.25 0 0113 4.25v2a.75.75 0 01-1.5 0v-2A.75.75 0 0010.75 3h-5.5A.75.75 0 004.5 3.75v12.5c0 .414.336.75.75.75h5.5a.75.75 0 00.75-.75v-2a.75.75 0 011.5 0v2A2.25 2.25 0 0110.75 18h-5.5A2.25 2.25 0 013 15.75V4.25z" clip-rule="evenodd" />
                      <path fill-rule="evenodd" d="M19 10a.75.75 0 00-.75-.75h-5.5a.75.75 0 000 1.5h5.5A.75.75 0 0019 10zM17.22 7.22a.75.75 0 00-1.06 0l-2.25 2.25a.75.75 0 000 1.06l2.25 2.25a.75.75 0 101.06-1.06L15.44 10l1.78-1.78a.75.75 0 000-1.06z" clip-rule="evenodd" />
                    </svg>
                    Log Out
                </button>
            </div>

            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-700 focus:text-gray-500 dark:focus:text-gray-400 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">

            <x-responsive-nav-link
            :href="route('dashboard')"
            :active="request()->routeIs('dashboard')"
            class="text-gray-700 dark:text-gray-300 w-full flex justify-center
                   {{ request()->routeIs('dashboard') ? 'bg-blue-100 text-blue-800 border-blue-800 dark:bg-blue-900/40 dark:text-blue-300 dark:border-blue-600' : '' }}">
            {{ __('Dashboard') }}
        </x-responsive-nav-link>

            <x-responsive-nav-link
            :href="route('users')"
            :active="request()->routeIs('users')"
            class="text-gray-700 dark:text-gray-300 w-full flex justify-center
                   {{ request()->routeIs('users') ? 'bg-blue-100 text-blue-800 border-blue-800 dark:bg-blue-900/40 dark:text-blue-300 dark:border-blue-600' : '' }}">
            {{ __('Users') }}
        </x-responsive-nav-link>

        <x-responsive-nav-link
            :href="route('friends')"
            :active="request()->routeIs('friends')"
            class="text-gray-700 dark:text-gray-300 w-full flex justify-center
                   {{ request()->routeIs('friends') ? 'bg-blue-100 text-blue-800 border-blue-800 dark:bg-blue-900/40 dark:text-blue-300 dark:border-blue-600' : '' }}">
            {{ __('Friends') }}
        </x-responsive-nav-link>

        <x-responsive-nav-link
            :href="route('chat')"
            :active="request()->routeIs('chat')"
            class="text-gray-700 dark:text-gray-300 w-full flex justify-center
                   {{ request()->routeIs('chat') ? 'bg-blue-100 text-blue-800 border-blue-800 dark:bg-blue-900/40 dark:text-blue-300 dark:border-blue-600' : '' }}">
            {{ __('Chat') }}
        </x-responsive-nav-link>
        </div>

        <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-600">
            <div class="flex items-center px-4">

            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile')" wire:navigate class="text-gray-700 dark:text-gray-300 w-full flex justify-center" >
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <button wire:click="logout" class="w-full flex justify-center">
                    <x-responsive-nav-link
                        class="flex items-center justify-center gap-2 border border-transparent text-sm leading-4 font-medium rounded-md text-red-500 dark:text-red-400 bg-red-50 dark:bg-red-900/30 hover:bg-red-100 dark:hover:bg-red-900/50">
                        <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M3 4.25A2.25 2.25 0 015.25 2h5.5A2.25 2.25 0 0113 4.25v2a.75.75 0 01-1.5 0v-2A.75.75 0 0010.75 3h-5.5A.75.75 0 004.5 3.75v12.5c0 .414.336.75.75.75h5.5a.75.75 0 00.75-.75v-2a.75.75 0 011.5 0v2A2.25 2.25 0 0110.75 18h-5.5A2.25 2.25 0 013 15.75V4.25z" clip-rule="evenodd" />
                            <path fill-rule="evenodd" d="M19 10a.75.75 0 00-.75-.75h-5.5a.75.75 0 000 1.5h5.5A.75.75 0 0019 10zM17.22 7.22a.75.75 0 00-1.06 0l-2.25 2.25a.75.75 0 000 1.06l2.25 2.25a.75.75 0 101.06-1.06L15.44 10l1.78-1.78a.75.75 0 000-1.06z" clip-rule="evenodd" />
                        </svg>
                        <span>Log Out</span>
                    </x-responsive-nav-link>
                </button>
            </div>
        </div>
    </div>
</nav>
