<nav x-data="{ isNotifOpen: false, isUserOpen: false }" class="bg-white border-b border-gray-100">
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

            <div class="flex items-center justify-end w-full gap-4">

                <!-- NOTIFIKASI -->
                <div class="relative" @click.away="isNotifOpen = false">
                    <button @click.stop="isNotifOpen = !isNotifOpen; if(isNotifOpen) isUserOpen = false"
                        class="relative p-1 rounded hover:bg-gray-100 transition">
                        <div class="relative">
                            <svg class="w-7 h-7 text-gray-700" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M17.133 12.632v-1.8a5.406 5.406 0 0 0-4.154-5.262.955.955 0 0 0 .021-.106V3.1a1 1 0 0 0-2 0v2.364a.955.955 0 0 0 .021.106 5.406 5.406 0 0 0-4.154 5.262v1.8C6.867 15.018 5 15.614 5 16.807 5 17.4 5 18 5.538 18h12.924C19 18 19 17.4 19 16.807c0-1.193-1.867-1.789-1.867-4.175ZM8.823 19a3.453 3.453 0 0 0 6.354 0H8.823Z" />
                            </svg>

                            @if ($unreadCount > 0)
                                <span
                                    class="absolute -top-1 -right-1 flex items-center justify-center min-w-[18px] h-[18px] text-[11px] font-bold text-white bg-red-600 rounded-full">
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
                                <li class="border-b border-gray-100 last:border-none">
                                    <a href="/pesan/{{ $notif->slug }}"
                                        class="flex gap-3 px-4 py-3 hover:bg-gray-50 transition-all duration-150 ease-in-out">
                                        <img src="{{ asset($notif->foto) }}" alt="{{ $notif->nama }}"
                                            class="w-10 h-10 rounded-full object-cover shadow-sm">

                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-center justify-between">
                                                <p class="font-semibold text-gray-900 text-sm truncate">
                                                    {{ $notif->nama }}
                                                </p>
                                                <span class="text-xs text-blue-400 whitespace-nowrap">
                                                    {{ $notif->created_at->diffForHumans() }}
                                                </span>
                                            </div>

                                            <p class="text-sm text-gray-600 mt-0.5 line-clamp-2 notif-message">
                                                {!! Str::limit($notif->pesan, 120) !!}
                                            </p>
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

                <!-- USER MENU -->
                <div class="relative" @click.away="isUserOpen = false">
                    <button @click.stop="isUserOpen = !isUserOpen; if(isUserOpen) isNotifOpen = false"
                        :class="{ 'ring ring-gray-600 ring-offset-2 ring-offset-white': isUserOpen }"
                        class="flex text-sm max-w-xs rounded-full items-center">

                        <!-- Avatar + Nama -->
                        <img class="w-8 h-8 rounded-full"
                            src="{{ Auth::user()->foto ? asset('storage/' . Auth::user()->foto) : asset('img/Anonymous.png') }}"
                            alt="{{ Auth::user()->name }}">
                        <div class="px-2">{{ Auth::user()->name }}</div>

                        <!-- Panah (animasi rotasi) -->
                        <svg :class="{ 'rotate-180': isUserOpen, 'rotate-0': !isUserOpen }"
                            class="fill-current h-4 w-4 transform ease-in-out"
                            xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                clip-rule="evenodd" />
                        </svg>
                    </button>

                    <!-- Dropdown User -->
                    <div x-show="isUserOpen" x-transition:enter="transition ease-out duration-100"
                        x-transition:enter-start="opacity-0 transform scale-95 translate-y-1"
                        x-transition:enter-end="opacity-100 transform scale-100 translate-y-0"
                        x-transition:leave="transition ease-in duration-75"
                        x-transition:leave-start="opacity-100 transform scale-100 translate-y-0"
                        x-transition:leave-end="opacity-0 transform scale-95 translate-y-1"
                        class="absolute right-0 mt-2 w-48 origin-top-right bg-white border border-black rounded-md shadow-lg z-50"
                        x-cloak>
                        <ul class="py-1">
                            <li>
                                <a href="{{ route('profile.edit') }}"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    Profil Anda
                                </a>
                            </li>
                            <li>
                                <a href="/users/{{ Auth::user()->slug }}"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    Card Anda
                                </a>
                            </li>
                            <li>
                                <a href="/dashboard" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    Dashboard
                                </a>
                            </li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit"
                                        class="block w-full px-4 py-2 text-sm text-start text-gray-700 hover:bg-gray-100">
                                        Log Out
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>

            </div>

            <!-- HAMBURGER -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open"
                    class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-hidden transition duration-150 ease-in-out">
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
    </div>
</nav>
