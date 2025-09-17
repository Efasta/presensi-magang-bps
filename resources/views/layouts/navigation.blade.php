<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="/dashboard" class="flex items-center space-x-3">
                        <x-application-logo class="h-10 w-auto fill-current text-gray-500" />
                        <div class="text-lg font-semibold text-gray-900">
                            BPS Provinsi Sulawesi Selatan
                        </div>
                    </a>
                </div>
            </div>

            <div class="flex items-center justify-end w-full gap-1">
                @php
                    $isAdmin = Auth::user()->is_admin;
                @endphp

                @if (!$isAdmin)
                    <!-- Notifikasi -->
                    <div x-data="{ isNotifOpen: false }" class="relative">
                        <button @click="isNotifOpen = !isNotifOpen"
                            class="relative p-1 rounded hover:bg-gray-100 transition">
                            <div class="relative">
                                <svg class="w-7 h-7 text-gray-700" fill="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M17.133 12.632v-1.8a5.406 5.406 0 0 0-4.154-5.262.955.955 0 0 0 .021-.106V3.1a1 1 0 0 0-2 0v2.364a.955.955 0 0 0 .021.106 5.406 5.406 0 0 0-4.154 5.262v1.8C6.867 15.018 5 15.614 5 16.807 5 17.4 5 18 5.538 18h12.924C19 18 19 17.4 19 16.807c0-1.193-1.867-1.789-1.867-4.175ZM8.823 19a3.453 3.453 0 0 0 6.354 0H8.823Z" />
                                </svg>

                                @if ($unreadCount > 0)
                                    <span
                                        class="absolute -top-1 -right-1 inline-flex items-center justify-center px-1.5 py-0.5 text-xs font-bold leading-none text-white bg-red-600 rounded-full">
                                        {{ $unreadCount }}
                                    </span>
                                @endif
                            </div>
                        </button>

                        <!-- Dropdown Notifikasi -->
                        <div x-show="isNotifOpen" x-transition
                            class="absolute right-0 mt-2 w-80 max-h-96 overflow-y-auto bg-white border border-gray-100 rounded-lg shadow-lg z-50">
                            <ul>
                                @forelse ($recentNotifs as $notif)
                                    <li class="border-b border-gray-100">
                                        <a href="/pesan/{{ $notif->slug }}" class="flex px-4 py-3 hover:bg-gray-50">
                                            <img class="me-3 rounded-full w-10 h-10" src="{{ asset($notif->foto) }}"
                                                alt="{{ $notif->nama }}">
                                            <div>
                                                <p class="text-sm text-gray-500">
                                                    Pesan baru dari <span
                                                        class="font-medium text-gray-900">{{ $notif->nama }}</span>
                                                </p>
                                                <span
                                                    class="text-xs text-blue-600">{{ $notif->created_at->diffForHumans() }}</span>
                                            </div>
                                        </a>
                                    </li>
                                @empty
                                    <li class="w-full px-4 py-6 text-center text-gray-500 text-sm">
                                        Notifikasi kamu lagi kosong nih...
                                    </li>
                                @endforelse
                            </ul>
                        </div>
                    </div>
                @endif

                <!-- Dropdown User -->
                <div class="hidden sm:flex sm:items-center">
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button
                                class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 transition ease-in-out duration-150">
                                <div>
                                    <span class="sr-only">Open user menu</span>
                                    <img class="w-8 h-8 rounded-full"
                                        src="{{ Auth::user()->foto ? asset('storage/' . Auth::user()->foto) : asset('img/Anonymous.png') }}"
                                        alt="{{ Auth::user()->name }}">
                                </div>
                                <div class="px-2">{{ Auth::user()->name }}</div>

                                <div class="ms-1">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <x-dropdown-link :href="route('profile.edit')">
                                {{ __('Profil Anda') }}
                            </x-dropdown-link>

                            <a href="/users/{{ Auth::user()->slug }}"
                                class='block w-full px-4 py-2 text-start text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-hidden focus:bg-gray-100 transition duration-150 ease-in-out'>
                                {{ __('Card Anda') }}
                            </a>

                            <x-dropdown-link :href="route('dashboard')">
                                {{ __('Dashboard') }}
                            </x-dropdown-link>

                            <!-- Authentication -->
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf

                                <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                    this.closest('form').submit();">
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                </div>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open"
                    class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-hidden focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round"
                            stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>

        <!-- Responsive Navigation Menu -->
        <div :class="{ 'block': open, 'hidden': !open }" class="hidden sm:hidden">

            <!-- Responsive Settings Options -->
            <div class="pt-4 pb-1 border-t border-gray-200">
                <div class="px-4">
                    <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                </div>

                <div class="mt-3 space-y-1">
                    <x-responsive-nav-link :href="route('profile.edit')">
                        {{ __('Profil Anda') }}
                    </x-responsive-nav-link>

                    <a href="/users/{{ Auth::user()->slug }}"
                        class='block w-full ps-3 pe-4 py-2 border-l-4 border-transparent text-start text-base font-medium text-gray-600 hover:text-gray-800 hover:bg-gray-50 hover:border-gray-300 focus:outline-hidden focus:text-gray-800 focus:bg-gray-50 focus:border-gray-300 transition duration-150 ease-in-out'>
                        {{ __('Card Anda') }}
                    </a>

                    <x-responsive-nav-link :href="route('dashboard')">
                        {{ __('Dashboard') }}
                    </x-responsive-nav-link>

                    <!-- Authentication -->
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf

                        <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                            {{ __('Log Out') }}
                        </x-responsive-nav-link>
                    </form>
                </div>
            </div>
        </div>
</nav>
