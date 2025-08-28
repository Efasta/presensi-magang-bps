<x-layout :title="$title">
    <!-- Start block -->
    @php
        // Pastikan user sudah login (karena middleware auth)
        $user = Auth::user();
        $isAdmin = $user?->is_admin ?? false;
        $isOwner = false;
    @endphp

    @if ($isAdmin)
        <section class= "pb-20.5 dark:bg-gray-900 pt-3 antialiased">
            <div class="mx-auto max-w-screen-2xl px-4 lg:px-12">
                <div class="bg-white dark:bg-gray-800 relative border border-gray-200 rounded-lg">
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
                                    class="absolute hidden z-10 px-3 pt-1 bg-white rounded-lg shadow w-80 dark:bg-gray-700 right-0 max-h-72 overflow-y-auto">
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
                                @forelse ($users as $user)
                                    @php
                                        $absensi = $user->absensis->first();
                                        $isOwner = Auth::user()->id === $user->id; // ambil absensi hari ini
                                    @endphp
                                    <tr data-status="{{ strtolower(optional($absensi->status)->nama) ?? 'belum-absen' }}"
                                        data-fungsi="{{ strtolower($user->fungsi->nama) }}"
                                        class="border-b border-gray-200 dark:border-gray-600 dark:hover:bg-gray-700">
                                        <td class="px-4 py-3 font-medium text-gray-900">{{ $loop->iteration }}</td>
                                        <th scope="row"
                                            class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                            <div class="flex items-center gap-3">
                                                <img class="w-8 h-8 rounded-full"
                                                    src="{{ $user->foto ? asset('storage/' . $user->foto) : asset('img/Anonymous.png') }}"
                                                    alt="{{ $user->name }}">
                                                {{ $user->name }}
                                            </div>
                                        </th>
                                        <td class="px-4 py-3">
                                            <a href="/fungsi?fungsi={{ $user->fungsi->slug }}"
                                                class="{{ $user->fungsi->warna }} text-xs font-medium px-2 py-0.5 rounded hover:underline">
                                                {{ $user->fungsi->nama }}
                                            </a>
                                        </td>
                                        <td class="px-4 py-3">{{ $absensi?->tanggal ?? '-' }}</td>
                                        <td class="px-4 py-3">{{ $absensi?->jam_masuk ?? '-' }}</td>
                                        <td class="px-4 py-3">{{ $absensi?->jam_keluar ?? '-' }}</td>
                                        <td class="px-4 py-3">
                                            @if ($absensi)
                                                <div class="flex items-center">
                                                    <div
                                                        class="h-4 w-4 rounded-full inline-block mr-2 {{ $absensi->status->warna }}">
                                                    </div>
                                                    {{ $absensi->status->nama }}
                                                </div>
                                            @else
                                                <span class="text-gray-500 italic">Belum absen</span>
                                            @endif
                                        </td>
                                        @php
                                            $absensiToday = $user->absensis->first(); // ambil absensi pertama di koleksi absensis
                                        @endphp
                                        <td class="px-4 py-3">
                                            <a href="/keterangan/{{ $absensiToday->slug }}" class="hover:underline">
                                                {{ Str::limit($absensiToday->judul ?? $absensiToday->status->nama, 10) }}
                                            </a>
                                        </td>
                                        <td class="px-4 py-3">
                                            @if ($absensi)
                                                <!-- tombol dropdown seperti biasa -->
                                            @else
                                                <span class="text-xs text-gray-400 italic">Tidak ada aksi</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center py-4 text-gray-500 italic">
                                            Tidak ada data pengguna atau absensi hari ini.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    @if ($users->hasPages())
                        {{ $users->onEachSide(0)->links('components.pagination.custom') }}
                    @endif
                </div>
            </div>
        </section>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/datepicker.min.js"></script>
    @else
        {{-- Optional: Jika kamu mau tampilkan pesan untuk non-admin --}}
        <section class="bg-white dark:bg-gray-900">
            <div class="py-8 px-4 mx-auto max-w-screen-xl lg:pt-40 lg:px-6">
                <div class="mx-auto max-w-screen-sm text-center">
                    <h1
                        class="mb-4 text-7xl tracking-tight font-extrabold lg:text-9xl text-blue-600 dark:text-red-500">
                        403</h1>
                    <p class="mb-4 text-3xl tracking-tight font-bold text-gray-900 md:text-4xl dark:text-white">Akses
                        Ditolak</p>
                    <p class="mb-4 text-lg font-light text-gray-500 dark:text-gray-400">Maaf, Anda tidak memiliki izin
                        untuk mengakses halaman ini.</p>
                    <a href="{{ route('dashboard') }}"
                        class="inline-flex text-white bg-emerald-600 hover:bg-emerald-700 focus:ring-4 focus:outline-none focus:ring-emerald-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:focus:ring-emerald-800 my-4">Kembali
                        ke Dashboard</a>
                </div>
            </div>
        </section>
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
