<nav x-data="{ isOpen: false, isNotifOpen: false }" class="fixed top-0 z-[999] w-full bg-white border-b border-gray-200">
    <div class="px-3 py-3 lg:px-5 lg:pl-3">
        <div class="flex items-center justify-between">
            <!-- Sidebar toggle & logo -->
            <div class="flex items-center justify-start rtl:justify-end">
                <button data-drawer-target="logo-sidebar" data-drawer-toggle="logo-sidebar" aria-controls="logo-sidebar"
                    type="button"
                    class="inline-flex items-center p-2 text-sm text-gray-500 rounded-lg sm:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600">
                    <span class="sr-only">Open sidebar</span>
                    <svg class="w-6 h-6" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20"
                        xmlns="http://www.w3.org/2000/svg">
                        <path clip-rule="evenodd" fill-rule="evenodd"
                            d="M2 4.75A.75.75 0 012.75 4h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 4.75zm0 10.5a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5a.75.75 0 01-.75-.75zM2 10a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 10z">
                        </path>
                    </svg>
                </button>
                <a href="/dashboard" class="flex ms-2 md:me-24">
                    <img src="{{ asset('img/Lambang_BPS.png') }}" class="h-8 me-3" alt="BPSAbsen Provsulsel" />
                    <span class="self-center text-lg font-bold sm:text-2xl whitespace-nowrap dark:text-white">BPS
                        Provinsi Sulawesi Selatan</span>
                </a>
            </div>

            <!-- Right section (notif & user menu) -->
            <div class="flex items-center relative space-x-3">
                <!-- Notifikasi Bell Icon -->
                @php
                    $isAdmin = Auth::user()->is_admin;
                @endphp
                @if (!$isAdmin)
                    <div class="relative" @click.away="isNotifOpen = false">
                        <button @click.stop="isNotifOpen = !isNotifOpen"
                            class="relative  p-1 mr-3 rounded hover:bg-gray-100 transition-colors duration-150 ease-in-out cursor-pointer">

                            <!-- Icon (jadi parent relative) -->
                            <div class="relative">
                                <svg class="w-7 h-7 text-gray-800 dark:text-white" aria-hidden="true"
                                    xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                                    viewBox="0 0 24 24">
                                    <path
                                        d="M17.133 12.632v-1.8a5.406 5.406 0 0 0-4.154-5.262.955.955 0 0 0 .021-.106V3.1a1 1 0 0 0-2 0v2.364a.955.955 0 0 0 .021.106 5.406 5.406 0 0 0-4.154 5.262v1.8C6.867 15.018 5 15.614 5 16.807 5 17.4 5 18 5.538 18h12.924C19 18 19 17.4 19 16.807c0-1.193-1.867-1.789-1.867-4.175ZM8.823 19a3.453 3.453 0 0 0 6.354 0H8.823Z" />
                                </svg>


                                <!-- Badge -->
                                @if ($unreadCount > 0)
                                    <span
                                        class="absolute -top-1 -right-1 inline-flex items-center justify-center px-1.5 py-0.5 text-xs font-bold leading-none text-white bg-red-600 rounded-full">
                                        {{ $unreadCount }}
                                    </span>
                                @endif
                            </div>
                        </button>

                        <!-- NOTIFICATION DROPDOWN -->
                        <div x-show="isNotifOpen" x-transition
                            class="absolute right-0 z-[9999] mt-2 w-80 max-h-96 overflow-y-auto bg-white border border-gray-100 rounded-lg shadow-lg">
                            <ul>
                                @forelse ($recentNotifs as $notif)
                                    <li class="border-b border-gray-100 dark:border-gray-600">
                                        <a href="/pesan/{{ $notif->slug }}" class="flex px-4 py-3 hover:bg-gray-50">
                                            <img class="me-3 rounded-full w-11 h-11" src="{{ asset($notif->foto) }}"
                                                alt="{{ $notif->nama }}">
                                            <div>
                                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                                    Pesan baru dari
                                                    <span
                                                        class="font-medium text-gray-900 dark:text-white">{{ $notif->nama }}</span>
                                                </p>
                                                <span class="text-xs text-blue-600 dark:text-blue-500">
                                                    {{ $notif->created_at->diffForHumans() }}
                                                </span>
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
                @endif
            </div>

            <!-- User menu -->
            <div class="relative" @click.away="isOpen = false">
                <button @click="isOpen = !isOpen"
                    :class="{ 'ring ring-gray-600 ring-offset-2 ring-offset-white': isOpen }"
                    class="flex text-sm max-w-xs rounded-full items-center">
                    <!-- Avatar + Nama -->
                    <img class="w-8 h-8 rounded-full"
                        src="{{ Auth::user()->foto ? asset('storage/' . Auth::user()->foto) : asset('img/Anonymous.png') }}"
                        alt="{{ Auth::user()->name }}">
                    <span class="hidden sm:inline-block text-sm font-medium px-2">{{ Auth::user()->name }}</span>
                    <svg x-show="!isOpen" class="fill-current h-4 w-4" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                            clip-rule="evenodd" />
                    </svg>
                    <svg x-show="isOpen" class="fill-current h-4 w-4 rotate-180" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                            clip-rule="evenodd" />
                    </svg>
                </button>

                <!-- Dropdown user menu -->
                <div x-show="isOpen"
                    class="absolute right-0 z-[9999] mt-2 w-48 origin-top-right bg-white border border-gray-200 divide-y divide-gray-100 rounded shadow-md dark:bg-gray-700 dark:divide-gray-600">
                    <ul class="py-1">
                        <li><a href="/profile"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-600">Profil
                                Anda</a></li>
                        <li><a href="/users/{{ Auth::user()->slug }}"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-600">Card
                                Anda</a></li>
                        <li><a href="/dashboard"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-600">Dashboard</a>
                        </li>
                        <li>
                            <form method="POST" action="/logout">
                                @csrf
                                <button type="submit"
                                    class="w-full text-start block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-600 cursor-pointer">Sign
                                    out</button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    </div>
</nav>
