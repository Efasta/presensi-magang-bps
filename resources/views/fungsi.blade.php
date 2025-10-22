<x-layout :title="$title">
    @php
        $isAdmin = Auth::user()->is_admin;
    @endphp

    <!-- Chart.js & Flowbite CDN -->
    @if ($isAdmin)
        <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
        <main class="mt-6 mb-20 sm:mb-18.5">
            <div class=" px-4 mx-auto max-w-screen-full">
                <article class="mx-auto w-full">

                    <!-- Tabel & Chart Container -->
                    <div
                        class="flex flex-col sm:flex-row gap-6 bg-white dark:bg-gray-800 p-6 rounded-lg border border-gray-200">

                        <!-- Tabel Kehadiran -->
                        <div class="w-full sm:w-1/2">
                            <div class="flex justify-between items-center mb-4">
                                <h2 id="tabelKehadiranTitle"
                                    class="sm:text-xl font-semibold text-gray-800 dark:text-white">
                                    Tabel Kehadiran:
                                </h2>

                                <!-- Dropdown Fungsi -->
                                <div class="relative">
                                    <button id="fungsiDropdownButton" data-dropdown-toggle="fungsiDropdownMenu"
                                        type="button"
                                        class="text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-700 inline-flex items-center">
                                        <span id="fungsiDropdownLabel">IPDS</span>
                                        <svg class="w-3 h-3 ml-2" xmlns="http://www.w3.org/2000/svg" fill="none"
                                            viewBox="0 0 10 6">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                                stroke-width="2" d="M1 1l4 4 4-4" />
                                        </svg>
                                    </button>

                                    <!-- Menu Dropdown -->
                                    <div id="fungsiDropdownMenu"
                                        class="z-20 hidden absolute right-0 mt-2 w-44 bg-white dark:bg-gray-700 divide-y divide-gray-100 rounded-lg shadow">
                                        <ul class="py-2 text-sm text-gray-700 dark:text-gray-200"
                                            aria-labelledby="fungsiDropdownButton">
                                            @foreach ($fungsis as $fungsi)
                                                <li>
                                                    <a href="#" data-fungsi="{{ $fungsi->slug }}"
                                                        class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">
                                                        {{ $fungsi->nama }}
                                                    </a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <!-- Tabel -->
                            <div class="overflow-x-auto rounded-lg border border-gray-200 dark:border-gray-700">
                                <table class="min-w-full text-sm text-left h-[300px] text-gray-600 dark:text-gray-300">
                                    <thead
                                        class="text-xs uppercase bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300">
                                        <tr>
                                            <th class="px-4 py-2">Kategori</th>
                                            <th class="px-4 py-2">Jumlah</th>
                                        </tr>
                                    </thead>
                                    <tbody id="attendanceTableBody" class="bg-white dark:bg-gray-800">
                                        <!-- Diisi oleh JS -->
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Pie Chart -->
                        <div class="w-full sm:w-1/2 flex flex-col relative space-y-4">

                            <!-- Header: Judul + Dropdown (Responsif & Sejajar) -->
                            <div
                                class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-2 space-y-2 sm:space-y-0">
                                <h2
                                    class="text-xl font-semibold text-gray-800 dark:text-white text-center sm:text-left">
                                    Grafik Kehadiran
                                </h2>

                                <!-- Dropdown Range -->
                                <div class="relative text-center sm:text-right">
                                    <button id="dropdownDefaultButton" data-dropdown-toggle="lastDaysdropdown"
                                        class="z-10 relative text-sm font-medium text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white inline-flex items-center">
                                        <span id="dropdownButtonText">
                                            {{-- Tampilkan label berdasarkan request --}}
                                            @php
                                                $labels = [
                                                    'today' => 'Hari ini',
                                                    'yesterday' => 'Kemarin',
                                                    '7' => '1 minggu terakhir',
                                                    '30' => '1 bulan terakhir',
                                                    '60' => '2 bulan terakhir',
                                                    'all' => 'Sepanjang waktu',
                                                ];
                                                $currentRange = $defaultRange;
                                            @endphp
                                            {{ $labels[$currentRange] ?? 'Hari ini' }}
                                        </span>
                                        <svg class="w-2.5 h-2.5 ml-2.5" aria-hidden="true"
                                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                                stroke-width="2" d="M1 1l4 4 4-4" />
                                        </svg>
                                    </button>

                                    <!-- Dropdown Menu -->
                                    <div id="lastDaysdropdown"
                                        class="z-10 hidden absolute right-0 mt-2 w-44 bg-white divide-y divide-gray-100 rounded-lg shadow-sm dark:bg-gray-700">
                                        <ul class="py-2 text-sm text-gray-700 dark:text-gray-200 text-start"
                                            aria-labelledby="dropdownDefaultButton">
                                            <li><a href="#" data-range="today"
                                                    class="range-option block px-4 py-2 hover:bg-gray-200 dark:hover:bg-gray-600 dark:hover:text-white">Hari
                                                    ini</a></li>
                                            @if ($isAdmin)
                                                <li><a href="#" data-range="yesterday"
                                                        class="range-option block px-4 py-2 hover:bg-gray-200 dark:hover:bg-gray-600 dark:hover:text-white">Kemarin</a>
                                                </li>
                                            @endif
                                            <li><a href="#" data-range="7"
                                                    class="range-option block px-4 py-2 hover:bg-gray-200 dark:hover:bg-gray-600 dark:hover:text-white">1
                                                    minggu terakhir</a></li>
                                            <li><a href="#" data-range="30"
                                                    class="range-option block px-4 py-2 hover:bg-gray-200 dark:hover:bg-gray-600 dark:hover:text-white">1
                                                    bulan terakhir</a></li>
                                            <li><a href="#" data-range="60"
                                                    class="range-option block px-4 py-2 hover:bg-gray-200 dark:hover:bg-gray-600 dark:hover:text-white">2
                                                    bulan terakhir</a></li>
                                            <li><a href="#" data-range="all"
                                                    class="range-option block px-4 py-2 hover:bg-gray-200 dark:hover:bg-gray-600 dark:hover:text-white">Sepanjang
                                                    waktu</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <!-- Grafik Pie (kalau ada data) -->
                            <div class="relative w-full max-w-lg min-h-[300px] mx-auto">
                                <div id="pie-chart-canvas"
                                    class="{{ array_sum($chartData[$initialFungsi] ?? []) === 0 ? 'hidden' : '' }}">
                                    <!-- Chart akan muncul di sini -->
                                </div>

                                <!-- State kosong (centered) -->
                                <div id="empty-piechart-state"
                                    class="absolute inset-0 flex flex-col items-center justify-center text-gray-500 text-sm text-center {{ array_sum($chartData[$initialFungsi] ?? []) > 0 ? 'hidden' : '' }}">
                                    <svg class="w-6 h-6 text-gray-800 dark:text-white mb-2"
                                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M5 1v3m5-3v3m5-3v3M1 7h18M5 11h10M2 3h16a1 1 0 0 1 1 1v14a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V4a1 1 0 0 1 1-1Z" />
                                    </svg>
                                    Belum ada data absensi untuk ditampilkan.
                                </div>
                            </div>
                        </div>
                    </div>
                </article>
            </div>
            <!-- Start block -->
            <section class= "dark:bg-gray-900 py-3 sm:p-5 antialiased">
                <div class=" mx-auto max-w-screen-full px-4 sm:px-0">
                    <div class="bg-white dark:bg-gray-800 relative border border-gray-200 rounded-lg">
                        <div class="flex items-center justify-between p-5">
                            <!-- Judul tabel -->
                            <p id="tabelAnggotaTitle" class="sm:text-xl font-semibold text-gray-900 dark:text-white">
                                Anggota Fungsi:
                            </p>

                            <!-- Wrapper tombol filter -->
                            <div class="relative inline-block text-left">
                                <!-- Tombol filter -->
                                <button id="filterDropdownButton" type="button"
                                    class="flex items-center py-2 px-4 text-sm font-medium text-white bg-emerald-600 hover:bg-emerald-800 focus:outline-none rounded-lg focus:ring-4 focus:ring-emerald-300 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">
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

                                <!-- Badge -->
                                <span id="filterBadge"
                                    class="absolute -top-1 -right-1 inline-flex items-center justify-center min-w-[20px] h-[20px] text-[11px] font-bold leading-none text-white bg-red-600 rounded-full px-[5px] py-[1px] hidden">
                                </span>

                                <!-- Dropdown -->
                                <div id="filterDropdown"
                                    class="absolute z-10 hidden px-3 pt-1 bg-white rounded-lg shadow w-80 dark:bg-gray-700 right-0 mt-2 max-h-72 overflow-y-auto">
                                    <div
                                        class="flex items-center justify-between pt-2 pb-2 border-b border-gray-200 dark:border-gray-600">
                                        <h6 class="text-sm font-medium text-black dark:text-white">Filters</h6>
                                        <div class="flex items-center space-x-3">
                                            <button type="button"
                                                class="text-sm font-medium text-emerald-600 dark:text-emerald-500 hover:underline cursor-pointer"
                                                onclick="applyFilter()">Terapkan</button>
                                            <button type="button"
                                                class="text-sm font-medium text-emerald-600 dark:text-emerald-500 hover:underline cursor-pointer"
                                                onclick="resetFilter()">Reset</button>
                                        </div>
                                    </div>

                                    <!-- Accordion Filter -->
                                    <div id="accordion-flush" data-accordion="collapse"
                                        data-active-classes="text-black dark:text-white"
                                        data-inactive-classes="text-gray-500 dark:text-gray-400">
                                        <h2 id="kehadiran-heading">
                                            <button type="button"
                                                class="flex items-center justify-between w-full py-2 px-1.5 text-sm font-medium text-left text-gray-500 border-gray-200 dark:border-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700"
                                                data-accordion-target="#kehadiran-body" aria-expanded="true"
                                                aria-controls="kehadiran-body">
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
                                                @php
                                                    $selectedStatuses = (array) request()->input('status');
                                                @endphp

                                                @foreach ($statuses as $status)
                                                    <ul class="space-y-2 mb-2">
                                                        <li class="flex items-center">
                                                            <input id="status-{{ Str::slug($status->nama) }}"
                                                                type="checkbox" name="status[]"
                                                                value="{{ $status->nama }}"
                                                                {{ in_array($status->nama, $selectedStatuses) ? 'checked' : '' }}
                                                                class="w-4 h-4 bg-gray-100 border-gray-300 rounded text-emerald-600 focus:ring-emerald-500 dark:focus:ring-emerald-600 dark:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500">

                                                            <label for="status-{{ Str::slug($status->nama) }}"
                                                                class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-100">{{ $status->nama }}</label>
                                                        </li>
                                                    </ul>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                                <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                                    <tr>
                                        <th scope="col" class="p-4">Id
                                        </th>
                                        <th scope="col" class="p-4">Nama</th>
                                        <th scope="col" class="p-4">Nim/Nisn</th>
                                        <th scope="col" class="p-4">Fungsi</th>
                                        <th scope="col" class="p-4 whitespace-nowrap">Jenis Kelamin</th>
                                        <th scope="col" class="p-4">Status</th>
                                        <th scope="col" class="p-4"></th>
                                        <th scope="col" class="p-4">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="anggotaFungsiTableBody">
                                    @forelse ($processedUsers as $item)
                                        <tr data-status="{{ strtolower($item['status']) }}"
                                            data-fungsi="{{ strtolower($item['user']->fungsi->slug ?? '') }}"
                                            class="border-t">
                                            <td class="px-4 py-3 text-black">
                                                {{ $loop->iteration + $processedUsers->firstItem() - 1 }}</td>
                                            <td class="px-4 py-3 text-black">
                                                <div class="flex items-center gap-3 whitespace-nowrap">
                                                    <img class="w-8 h-8 rounded-full"
                                                        src="{{ $item['user']->foto ? asset('storage/' . $item['user']->foto) : asset('img/Anonymous.png') }}"
                                                        alt="{{ $item['user']->name }}">
                                                    {{ $item['user']->name }}
                                                </div>
                                            </td>
                                            <td class="pl-6 py-3 text-black">{{ $item['user']->nim ?? '-' }}</td>
                                            <td class="px-4 py-3 whitespace-nowrap">
                                                <a href="/fungsi?fungsi={{ $item['user']->fungsi->slug ?? '' }}"
                                                    class="{{ $item['user']->fungsi->warna ?? 'bg-gray-100' }} font-medium px-2 py-0.5 rounded hover:underline">
                                                    {{ $item['user']->fungsi->nama ?? 'Umum' }}
                                                </a>
                                            </td>
                                            <td class="px-4 py-3">{{ $item['user']->jenis_kelamin }}</td>
                                            <td class="px-4 py-3">
                                                <div class="flex items-center">
                                                    <div
                                                        class="h-4 w-4 rounded-full inline-block mr-2 {{ $item['status_color'] }}">
                                                    </div>
                                                    {{ $item['status'] }}
                                                </div>
                                            </td>
                                            <td class="px-4 py-3 text-black">
                                                {{ $item['count'] }}x
                                            </td>
                                            <td class="px-4 py-3 flex items-center">
                                                <x-dropdown-action :user="$item['user']" :rowId="$loop->iteration + $processedUsers->firstItem() - 1" />
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="text-center text-gray-500 py-10">
                                                Belum ada data anggota untuk ditampilkan...
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                            @if ($processedUsers->hasPages())
                                <div class="border-t py-2 border-gray-200">
                                    {{ $processedUsers->appends(request()->query())->links() }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </section>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/datepicker.min.js"></script>
        </main>
    @else
        {{-- Optional: Jika kamu mau tampilkan pesan untuk non-admin --}}
        <section class="bg-white dark:bg-gray-900">
            <div class="py-8 px-4 mx-auto max-w-screen-xl lg:pt-40 pt-50 lg:px-6">
                <div class="mx-auto max-w-screen-sm text-center">
                    <h1 class="mb-4 text-7xl tracking-tight font-extrabold lg:text-9xl text-red-600">
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

    @push('script')
        <!-- Script Chart.js & Table Update (revisi: sinkronisasi fungsi / fungsi[]) -->
        <script>
            function resetFilter() {
                window.location.href = window.location.pathname;
            }

            function applyFilter() {
                const checkedStatuses = Array.from(document.querySelectorAll('#kehadiran-body input[type="checkbox"]:checked'))
                    .map(cb => cb.value);

                const checkedFunctions = Array.from(document.querySelectorAll('#fungsi-body input[type="checkbox"]:checked'))
                    .map(cb => cb.value);

                // Jika semua filter kosong → langsung reset ke halaman utama
                if (checkedStatuses.length === 0 && checkedFunctions.length === 0) {
                    window.location.href = window.location.pathname;
                    return;
                }

                // Ambil param saat ini supaya bisa mempertahankan 'range', 'search', dan 'fungsi' jika perlu
                const currentParams = new URLSearchParams(window.location.search);
                const existingFungsiSingular = currentParams.get('fungsi'); // parameter fungsi yang dipakai chart (singular)

                // Buat params baru (tanpa membawa sisa query lama)
                const params = new URLSearchParams();

                // Tambahkan status jika ada
                checkedStatuses.forEach(status => params.append('status[]', status));

                // LOGIKA SINKRONISASI fungsi[] <-> fungsi (singular)
                if (checkedFunctions.length > 0) {
                    // Jika user memilih fungsi via checkbox, kirim sebagai fungsi[] untuk server
                    checkedFunctions.forEach(f => params.append('fungsi[]', f));
                    // Dan set juga fungsi (singular) ke fungsi pertama agar chart/label tetap sinkron
                    params.set('fungsi', checkedFunctions[0]);
                } else if (existingFungsiSingular) {
                    // Jika tidak ada checkbox fungsi tapi URL sekarang punya fungsi (klik sebelumnya),
                    // pertahankan fungsi (singular) agar pilihan fungsi tidak hilang
                    params.set('fungsi', existingFungsiSingular);
                }

                // Reset pagination ke halaman pertama
                params.set('page', 1);

                // Pertahankan parameter lain seperti range/search kalau ada di URL sekarang
                ['range', 'search'].forEach(key => {
                    const val = currentParams.get(key);
                    if (val) params.set(key, val);
                });

                // Tutup dropdown filter
                const dropdown = document.getElementById('filterDropdown');
                if (dropdown) dropdown.classList.add('hidden');

                // Bangun ulang URL dan reload
                const url = `${window.location.pathname}?${params.toString()}`;
                window.location.href = url;
            }

            // Toggle dropdown filter
            const dropdownButton = document.getElementById('filterDropdownButton');
            if (dropdownButton) {
                dropdownButton.addEventListener('click', () => {
                    const dd = document.getElementById('filterDropdown');
                    if (dd) dd.classList.toggle('hidden');
                });
            }

            // Tutup dropdown jika klik di luar
            document.addEventListener('click', (event) => {
                const dropdown = document.getElementById('filterDropdown');
                if (dropdownButton && dropdown && !dropdownButton.contains(event.target) && !dropdown.contains(event
                        .target)) {
                    dropdown.classList.add('hidden');
                }
            });

            // Badge counter untuk jumlah filter aktif
            document.addEventListener('DOMContentLoaded', function() {
                const badge = document.getElementById('filterBadge');
                const checkboxes = document.querySelectorAll('#filterDropdown input[type="checkbox"]');

                function getActiveFilterCount() {
                    return Array.from(checkboxes).filter(cb => cb.checked).length;
                }

                function updateBadge() {
                    const total = getActiveFilterCount();
                    if (total > 0) {
                        badge.textContent = total;
                        badge.classList.remove('hidden');
                        badge.style.display = 'flex';
                    } else {
                        badge.textContent = '';
                        badge.classList.add('hidden');
                        badge.style.display = 'none';
                    }
                }

                updateBadge();
            });

            // -----------------------------
            //   Bagian Grafik & Tabel
            // -----------------------------

            const pieChartDataByfungsi = @json($chartData);
            const pieChartLabels = @json($statuses->pluck('nama'));
            const urlParams = new URLSearchParams(window.location.search);

            // initialFungsi: cek 'fungsi' singular dulu; jika tidak ada, fallback ke existing keys
            const initialFungsi = urlParams.get('fungsi') || Object.keys(pieChartDataByfungsi)[0];

            let chartInstance;
            let reloadTimeout;

            function updateURLAndReload(newParams = {}) {
                const url = new URL(window.location.href);

                // Hapus page supaya selalu reset pagination ke 1
                url.searchParams.delete('page');

                // Tambahkan/overwrite param baru
                Object.entries(newParams).forEach(([key, value]) => {
                    url.searchParams.set(key, value);
                });

                clearTimeout(reloadTimeout);
                reloadTimeout = setTimeout(() => {
                    window.location.href = url.toString();
                }, 0);
            }

            function renderPieChart(data) {
                const pieCanvas = document.querySelector("#pie-chart-canvas");
                const emptyState = document.getElementById("empty-piechart-state");

                if (chartInstance) chartInstance.destroy();

                const total = data.reduce((sum, value) => sum + value, 0);

                if (total === 0) {
                    pieCanvas.classList.add("hidden");
                    emptyState.classList.remove("hidden");
                    return;
                } else {
                    pieCanvas.classList.remove("hidden");
                    emptyState.classList.add("hidden");
                }

                const options = {
                    chart: {
                        type: 'pie',
                        height: 300
                    },
                    series: data,
                    labels: pieChartLabels,
                    colors: ['#10B981', '#EAB308', '#3B82F6', '#DC2626'],
                    legend: {
                        position: 'bottom',
                        labels: {
                            colors: '#374151'
                        }
                    }
                };

                chartInstance = new ApexCharts(pieCanvas, options);
                chartInstance.render();
            }

            function updateTable(data) {
                const tbody = document.getElementById('attendanceTableBody');
                if (!tbody) return;

                tbody.innerHTML = '';
                pieChartLabels.forEach((label, index) => {
                    const row = `
                <tr class="border-b border-gray-200">
                    <td class="px-4 py-2 font-medium text-gray-800 dark:text-white">${label}</td>
                    <td class="px-4 py-2">${data[index]}</td>
                </tr>`;
                    tbody.insertAdjacentHTML('beforeend', row);
                });
            }

            document.addEventListener('DOMContentLoaded', () => {
                if (!pieChartDataByfungsi) return;

                renderPieChart(pieChartDataByfungsi[initialFungsi]);
                updateTable(pieChartDataByfungsi[initialFungsi]);

                const selectedText = document.querySelector(`[data-fungsi="${initialFungsi}"]`)?.textContent;
                if (selectedText) {
                    document.getElementById('fungsiDropdownLabel').textContent = selectedText;
                    document.getElementById('tabelKehadiranTitle').textContent = `Tabel Kehadiran: ${selectedText}`;
                    document.getElementById('tabelAnggotaTitle').textContent = `Anggota Fungsi: ${selectedText}`;
                }

                document.querySelectorAll('[data-fungsi]').forEach(el => el.classList.remove('active'));
                const activeEl = document.querySelector(`[data-fungsi="${initialFungsi}"]`);
                if (activeEl) activeEl.classList.add('active');

                // Klik fungsi
                document.querySelectorAll('[data-fungsi]').forEach(item => {
                    item.addEventListener('click', event => {
                        event.preventDefault();
                        event.stopPropagation();

                        if (event.target.closest('tbody')) return;

                        document.querySelectorAll('[data-fungsi]').forEach(el => el.classList.remove(
                            'active'));
                        event.target.classList.add('active');

                        const selectedFungsi = event.target.getAttribute('data-fungsi');
                        const data = pieChartDataByfungsi[selectedFungsi];

                        renderPieChart(data);
                        updateTable(data);

                        const name = event.target.textContent;
                        document.getElementById('fungsiDropdownLabel').textContent = name;
                        document.getElementById('tabelKehadiranTitle').textContent =
                            `Tabel Kehadiran: ${name}`;
                        document.getElementById('tabelAnggotaTitle').textContent =
                            `Anggota Fungsi: ${name}`;

                        const currentRange = new URLSearchParams(window.location.search).get('range') ||
                            'all';
                        // Saat klik fungsi via chart/list, set 'fungsi' singular (dipakai chart) dan juga reset page
                        updateURLAndReload({
                            fungsi: selectedFungsi,
                            range: currentRange
                        });
                    });
                });

                // Klik range
                document.querySelectorAll('.range-option').forEach(item => {
                    item.addEventListener('click', e => {
                        e.preventDefault();
                        e.stopPropagation();

                        const selectedRange = item.getAttribute('data-range');
                        const selectedFungsi = document.querySelector('[data-fungsi].active')
                            ?.getAttribute('data-fungsi') || initialFungsi;

                        updateURLAndReload({
                            fungsi: selectedFungsi,
                            range: selectedRange
                        });
                    });
                });
            });
        </script>
    @endpush
</x-layout>
