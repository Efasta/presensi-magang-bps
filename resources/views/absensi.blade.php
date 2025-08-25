<x-layout :title="$title">
    <!-- Start block -->
    @php
        $isAdmin = Auth::user()->is_admin;
    @endphp

    @if ($isAdmin)
        <section class= "pb-20.5 dark:bg-gray-900 pt-3 antialiased">
            <div class="mx-auto max-w-screen-2xl px-4 lg:px-12">
                <div class="bg-white dark:bg-gray-800 relative border border-gray-200 rounded-lg overflow-hidden">
                    <div class="flex items-center justify-between p-5">
                        <p class="text-xl font-semibold text-gray-900 dark:text-white">From Absensi</p>
                    </div>
                    <div
                        class="flex flex-col md:flex-row items-stretch md:items-center md:space-x-3 space-y-3 md:space-y-0 justify-between mx-4 py-4 border-t border-gray-200 dark:border-gray-700">
                        <div class="w-full md:w-1/2">
                            <form class="flex items-center">
                                <label for="simple-search" class="sr-only">Search</label>
                                <div class="relative w-full">
                                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                        <svg aria-hidden="true" class="w-5 h-5 text-gray-500 dark:text-gray-400"
                                            fill="currentColor" viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" />
                                        </svg>
                                    </div>
                                    <input type="search" id="simple-search" placeholder="Cari user..."
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-emerald-500 focus:border-emerald-500 block w-full pl-10 p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-emerald-500 dark:focus:border-emerald-500"
                                        autocomplete="off" autofocus name="search">
                                </div>
                            </form>
                        </div>
                        <div
                            class="w-full md:w-auto flex flex-col md:flex-row space-y-2 md:space-y-0 items-stretch md:items-center justify-end md:space-x-3 flex-shrink-0">

                            <form method="GET" action="/absensi" id="filterForm" onsubmit="return true;">
                                <button id="filterDropdownButton" type="button"
                                    class="w-full md:w-auto flex items-center justify-center py-2 px-4 text-sm font-medium text-white bg-emerald-600 hover:bg-emerald-800 focus:outline-none rounded-lg focus:z-10 focus:ring-4 focus:ring-emerald-300 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">
                                    <svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true"
                                        class="h-4 w-4 mr-1.5 -ml-1" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M3 3a1 1 0 011-1h12a1 1 0 011 1v3a1 1 0 01-.293.707L12 11.414V15a1 1 0 01-.293.707l-2 2A1 1 0 018 17v-5.586L3.293 6.707A1 1 0 013 6V3z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    Filter
                                    <svg class="-mr-1 ml-1.5 w-5 h-5" fill="currentColor" viewBox="0 0 20 20"
                                        xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" />
                                    </svg>
                                </button>



                                <div id="filterDropdown"
                                    class="absolute z-10 hidden px-3 pt-1 bg-white rounded-lg shadow w-80 dark:bg-gray-700 right-0 max-h-72 overflow-y-auto">
                                    <div class="flex items-center justify-between pt-2 pb-2 border-b border-gray-200">
                                        <h6 class="text-sm font-medium text-black dark:text-white">Filters</h6>
                                        <div class="flex items-center space-x-3">
                                            <button type="submit"
                                                class="text-sm font-medium text-emerald-600 dark:text-emerald-500 hover:underline">
                                                Terapkan
                                            </button>
                                            <a href="/absensi"
                                                class="text-sm font-medium text-emerald-600 dark:text-emerald-500 hover:underline">
                                                Reset filter
                                            </a>
                                        </div>
                                    </div>

                                    <div id="accordion-flush" data-accordion="collapse"
                                        data-active-classes="text-black dark:text-white"
                                        data-inactive-classes="text-gray-500 dark:text-gray-400">

                                        {{-- Kehadiran --}}
                                        <h2 id="kehadiran-heading">
                                            <button type="button"
                                                class="flex items-center justify-between w-full py-2 px-1.5 text-sm font-medium text-left text-gray-500 border-gray-200 dark:border-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700 border-b"
                                                data-accordion-target="#kehadiran-body" aria-expanded="true"
                                                aria-controls="category-body">
                                                <span>Kehadiran</span>
                                                <svg aria-hidden="true" data-accordion-icon=""
                                                    class="w-5 h-5 rotate-180 shrink-0" fill="currentColor"
                                                    viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" />
                                                </svg>
                                            </button>
                                        </h2>
                                        <div id="kehadiran-body" class="hidden" aria-labelledby="kehadiran-heading">
                                            <div class="py-2 font-light border-gray-300 dark:border-gray-600">
                                                @foreach ($statuses as $status)
                                                    <ul class="space-y-2 mb-2">
                                                        <li class="flex items-center">
                                                            <input id="status-{{ Str::slug($status->nama) }}"
                                                                type="checkbox" name="status[]"
                                                                value="{{ $status->nama }}"
                                                                {{ in_array($status->nama, request()->input('status', [])) ? 'checked' : '' }}
                                                                class="w-4 h-4 bg-gray-100 border-gray-300 rounded text-emerald-600 focus:ring-emerald-500 dark:focus:ring-emerald-600 dark:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500">
                                                            <label for="status-{{ Str::slug($status->nama) }}"
                                                                class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-100">
                                                                {{ $status->nama }}
                                                            </label>
                                                        </li>
                                                    </ul>
                                                @endforeach
                                            </div>
                                        </div>

                                        {{-- Fungsi --}}
                                        <h2 id="fungsi-heading">
                                            <button type="button"
                                                class="flex items-center justify-between w-full py-2 px-1.5 text-sm font-medium text-left text-gray-500 border-gray-300 dark:border-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700"
                                                data-accordion-target="#fungsi-body" aria-expanded="true"
                                                aria-controls="fungsi-body">
                                                <span>Fungsi</span>
                                                <svg aria-hidden="true" data-accordion-icon=""
                                                    class="w-5 h-5 rotate-180 shrink-0" fill="currentColor"
                                                    viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" />
                                                </svg>
                                            </button>
                                        </h2>
                                        <div id="fungsi-body" class="hidden" aria-labelledby="fungsi-heading">
                                            <div class="py-2 font-light border-gray-300 dark:border-gray-600">
                                                @foreach ($fungsis as $fungsi)
                                                    <ul class="space-y-2 mb-2">
                                                        <li class="flex items-center">
                                                            <input id="fungsi-{{ Str::slug($fungsi->nama) }}"
                                                                type="checkbox" name="fungsi[]"
                                                                value="{{ $fungsi->nama }}"
                                                                {{ in_array($fungsi->nama, request()->input('fungsi', [])) ? 'checked' : '' }}
                                                                class="w-4 h-4 bg-gray-100 border-gray-300 rounded text-emerald-600 focus:ring-emerald-500 dark:focus:ring-emerald-600 dark:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500">
                                                            <label for="fungsi-{{ Str::slug($fungsi->nama) }}"
                                                                class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-100">
                                                                {{ $fungsi->nama }}
                                                            </label>
                                                        </li>
                                                    </ul>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                                <tr>
                                    <th scope="col" class="p-4">Id
                                    </th>
                                    <th scope="col" class="p-4">Nama</th>
                                    <th scope="col" class="p-4">Fungsi</th>
                                    <th scope="col" class="p-4">Tanggal</th>
                                    <th scope="col" class="p-4">Jam Masuk</th>
                                    <th scope="col" class="p-4">Jam Keluar</th>
                                    <th scope="col" class="p-4">Status</th>
                                    <th scope="col" class="p-4">Keterangan</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $user)
                                    <tr data-status="{{ strtolower($user->absensi->status->nama) }}"
                                        data-fungsi="{{ strtolower($user->fungsi->nama) }}"
                                        class="border-b border-gray-200 dark:border-gray-600 dark:hover:bg-gray-700">
                                        <td class="px-4 py-3 font-medium text-gray-900">{{ $loop->iteration }}</td>
                                        <th scope="row"
                                            class="user-name px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                            <div class="flex items-center mr-3 gap-3">
                                                <img class="w-8 h-8 rounded-full"
                                                    src="{{ $user->foto ? asset('storage/' . $user->foto) : asset('img/Anonymous.png') }}"
                                                    alt="{{ $user->name }}" class="h-8 w-auto mr-3 user">
                                                {{ $user->name }}
                                            </div>
                                        </th>
                                        <td class="px-4 py-3">
                                            <a href="/fungsi?fungsi={{ $user->fungsi->slug }}"
                                                class="{{ $user->fungsi->warna }} text-xs font-medium px-2 py-0.5 rounded dark:bg-emerald-900 dark:text-emerald-300 hover:underline">{{ $user->fungsi->nama }}</a>
                                        </td>
                                        <td class="px-4 py-3">{{ $user->absensi->tanggal }}</td>

                                        <td class="px-4 py-3 max-w-[12rem] truncate">{{ $user->absensi->jam_masuk }}
                                        </td>
                                        <td class="px-4 py-3 max-w-[12rem] truncate">{{ $user->absensi->jam_keluar }}
                                        </td>
                                        <td
                                            class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                            <div class="flex items-center">
                                                <div
                                                    class="h-4 w-4 rounded-full inline-block mr-2 {{ $user->absensi->status->warna }}">
                                                </div>
                                                {{ $user->absensi->status->nama }}
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 max-w-[12rem] truncate">{{ $user->absensi->keterangan }}
                                        </td>
                                        <td class="px-4 py-3 flex items-center justify-self-end">
                                            <button id="users-{{ $user->id }}-dropdown-button"
                                                data-dropdown-toggle="users-{{ $user->id }}-dropdown"
                                                class="inline-flex items-center text-sm font-medium hover:bg-gray-100 dark:hover:bg-gray-700 p-1.5 dark:hover-bg-gray-800 text-center text-gray-500 hover:text-gray-800 rounded-lg focus:outline-none dark:text-gray-400 dark:hover:text-gray-100"
                                                type="button">
                                                <svg class="w-5 h-5" aria-hidden="true" fill="currentColor"
                                                    viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                    <path
                                                        d="M6 10a2 2 0 11-4 0 2 2 0 014 0zM12 10a2 2 0 11-4 0 2 2 0 014 0zM16 12a2 2 0 100-4 2 2 0 000 4z" />
                                                </svg>
                                            </button>
                                            <div id="users-{{ $user->id }}-dropdown"
                                                class="hidden z-10 w-44 bg-white rounded divide-y divide-gray-100 shadow dark:bg-gray-700 dark:divide-gray-600">
                                                <ul class="py-1 text-sm"
                                                    aria-labelledby="users-{{ $user->id }}-dropdown-button">
                                                    <li>
                                                        <a href="/users/{{ $user->slug }}"
                                                            class="flex w-full items-center py-2 px-4 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white text-gray-700 dark:text-gray-200">
                                                            <svg class="w-4 h-4 mr-2"
                                                                xmlns="http://www.w3.org/2000/svg" viewbox="0 0 20 20"
                                                                fill="currentColor" aria-hidden="true">
                                                                <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                                                <path fill-rule="evenodd" clip-rule="evenodd"
                                                                    d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" />
                                                            </svg>
                                                            Lihat Detail
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <button type="button" data-modal-target="updateProductModal"
                                                            data-modal-toggle="updateProductModal"
                                                            class="flex w-full items-center py-2 px-4 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white text-gray-700 dark:text-gray-200">
                                                            <svg class="w-4 h-4 mr-2"
                                                                xmlns="http://www.w3.org/2000/svg" viewbox="0 0 20 20"
                                                                fill="currentColor" aria-hidden="true">
                                                                <path
                                                                    d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z" />
                                                                <path fill-rule="evenodd" clip-rule="evenodd"
                                                                    d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" />
                                                            </svg>
                                                            Edit
                                                        </button>
                                                    </li>
                                                    <li>
                                                        <button type="button" data-modal-target="deleteModal"
                                                            data-modal-toggle="deleteModal"
                                                            class="flex w-full items-center py-2 px-4 hover:bg-gray-100 dark:hover:bg-gray-600 text-red-500 dark:hover:text-red-400">
                                                            <svg class="w-4 h-4 mr-2" viewbox="0 0 14 15"
                                                                fill="none" xmlns="http://www.w3.org/2000/svg"
                                                                aria-hidden="true">
                                                                <path fill-rule="evenodd" clip-rule="evenodd"
                                                                    fill="currentColor"
                                                                    d="M6.09922 0.300781C5.93212 0.30087 5.76835 0.347476 5.62625 0.435378C5.48414 0.523281 5.36931 0.649009 5.29462 0.798481L4.64302 2.10078H1.59922C1.36052 2.10078 1.13161 2.1956 0.962823 2.36439C0.79404 2.53317 0.699219 2.76209 0.699219 3.00078C0.699219 3.23948 0.79404 3.46839 0.962823 3.63718C1.13161 3.80596 1.36052 3.90078 1.59922 3.90078V12.9008C1.59922 13.3782 1.78886 13.836 2.12643 14.1736C2.46399 14.5111 2.92183 14.7008 3.39922 14.7008H10.5992C11.0766 14.7008 11.5344 14.5111 11.872 14.1736C12.2096 13.836 12.3992 13.3782 12.3992 12.9008V3.90078C12.6379 3.90078 12.8668 3.80596 13.0356 3.63718C13.2044 3.46839 13.2992 3.23948 13.2992 3.00078C13.2992 2.76209 13.2044 2.53317 13.0356 2.36439C12.8668 2.1956 12.6379 2.10078 12.3992 2.10078H9.35542L8.70382 0.798481C8.62913 0.649009 8.5143 0.523281 8.37219 0.435378C8.23009 0.347476 8.06631 0.30087 7.89922 0.300781H6.09922ZM4.29922 5.70078C4.29922 5.46209 4.39404 5.23317 4.56282 5.06439C4.73161 4.8956 4.96052 4.80078 5.19922 4.80078C5.43791 4.80078 5.66683 4.8956 5.83561 5.06439C6.0044 5.23317 6.09922 5.46209 6.09922 5.70078V11.1008C6.09922 11.3395 6.0044 11.5684 5.83561 11.7372C5.66683 11.906 5.43791 12.0008 5.19922 12.0008C4.96052 12.0008 4.73161 11.906 4.56282 11.7372C4.39404 11.5684 4.29922 11.3395 4.29922 11.1008V5.70078ZM8.79922 4.80078C8.56052 4.80078 8.33161 4.8956 8.16282 5.06439C7.99404 5.23317 7.89922 5.46209 7.89922 5.70078V11.1008C7.89922 11.3395 7.99404 11.5684 8.16282 11.7372C8.33161 11.906 8.56052 12.0008 8.79922 12.0008C9.03791 12.0008 9.26683 11.906 9.43561 11.7372C9.6044 11.5684 9.69922 11.3395 9.69922 11.1008V5.70078C9.69922 5.46209 9.6044 5.23317 9.43561 5.06439C9.26683 4.8956 9.03791 4.80078 8.79922 4.80078Z" />
                                                            </svg>
                                                            Delete
                                                        </button>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @if ($users->hasPages())
                        {{ $users->onEachSide(0)->links('components.pagination.custom') }}
                    @endif
                </div>
            </div>
        </section>
        <!-- Delete Modal -->
        <div id="delete-modal" tabindex="-1"
            class="fixed top-0 left-0 right-0 z-50 hidden p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full">
            <div class="relative w-full h-auto max-w-md max-h-full">
                <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                    <button type="button"
                        class="absolute top-3 right-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-800 dark:hover:text-white"
                        data-modal-toggle="delete-modal">
                        <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewbox="0 0 20 20"
                            xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                clip-rule="evenodd" />
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                    <div class="p-6 text-center">
                        <svg aria-hidden="true" class="mx-auto mb-4 text-gray-400 w-14 h-14 dark:text-gray-200"
                            fill="none" stroke="currentColor" viewbox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <h3 class="mb-5 text-lg font-normal text-gray-500 dark:text-gray-400">Are you sure you want to
                            delete this product?</h3>
                        <button data-modal-toggle="delete-modal" type="button"
                            class="text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center mr-2">Yes,
                            I'm sure</button>
                        <button data-modal-toggle="delete-modal" type="button"
                            class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600">No,
                            cancel</button>
                    </div>
                </div>
            </div>
        </div>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/datepicker.min.js"></script>
    @else
        {{-- Optional: Jika kamu mau tampilkan pesan untuk non-admin --}}
        <div class="p-6 text-center text-gray-500">
            Anda tidak memiliki izin untuk melihat data absensi.
        </div>
    @endif

</x-layout>

<script>
    function resetFilter() {
        // Reset semua checkbox di dalam #filterDropdown
        document.querySelectorAll('#filterDropdown input[type="checkbox"]').forEach(cb => cb.checked = false);

        // Reset input teks search (simple-search)
        const searchInput = document.getElementById('simple-search');
        if (searchInput) {
            searchInput.value = '';
        }

        // applyFilter(); // tampilkan semua kembali setelah reset
    }

    function applyFilter() {
        const checkedStatuses = Array.from(document.querySelectorAll('#kehadiran-body input[type="checkbox"]:checked'))
            .map(cb => cb.value.toLowerCase());

        const checkedFunctions = Array.from(document.querySelectorAll('#fungsi-body input[type="checkbox"]:checked'))
            .map(cb => cb.value.toLowerCase());

        const searchInput = document.getElementById('simple-search');
        const searchValue = searchInput ? searchInput.value.toLowerCase() : '';

        const rows = document.querySelectorAll('table tbody tr');

        rows.forEach(row => {
            const status = (row.dataset.status || '').toLowerCase();
            const fungsi = (row.dataset.fungsi || '').toLowerCase();

            // Ambil nama user dari class user-name (pastikan ada di HTML tabelmu)
            const nameCell = row.querySelector('.user-name');
            const name = nameCell ? nameCell.innerText.toLowerCase() : '';

            const statusMatch = checkedStatuses.length === 0 || checkedStatuses.includes(status);
            const fungsiMatch = checkedFunctions.length === 0 || checkedFunctions.includes(fungsi);
            const nameMatch = !searchValue || name.includes(searchValue);

            row.style.display = (statusMatch && fungsiMatch && nameMatch) ? '' : 'none';
        });
    }

    // Event listener untuk tombol filter buka/tutup dropdown
    document.getElementById('filterDropdownButton').addEventListener('click', () => {
        const dd = document.getElementById('filterDropdown');
        dd.classList.toggle('hidden');
    });
</script>
