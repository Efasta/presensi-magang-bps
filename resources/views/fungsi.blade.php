<x-layout :title="$title">
    @php
        $isAdmin = Auth::user()->is_admin;
    @endphp

    <!-- Chart.js & Flowbite CDN -->
    @if ($isAdmin)
        <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
        <script src="https://unpkg.com/flowbite@1.6.5/dist/flowbite.min.js"></script>

        <main class="pt-3 pb-17.5 lg:pb-15">
            <div class=" px-4 2xl:px-0 mx-auto max-w-screen-2xl">
                <article class="mx-auto w-full">

                    <!-- Tabel & Chart Container -->
                    <div
                        class="flex flex-col lg:flex-row bg-white dark:bg-gray-800 p-6 rounded-lg border-1 border-gray-200">

                        <div class="w-full md:w-1/2">
                            <div class="flex justify-between items-center mb-4">
                                <h2 id="tabelKehadiranTitle" class="text-xl font-semibold text-gray-800 dark:text-white">
                                    Tabel
                                    Kehadiran:</h2>

                                <!-- Dropdown Filter Fungsi -->
                                <div class="relative inline-block text-left">

                                    <!-- Dropdown List -->
                                    <div id="fungsiDropdownMenu"
                                        class="z-20 hidden bg-white dark:bg-gray-700 divide-y divide-gray-100 rounded-lg shadow w-44 mt-2">
                                        <ul class="py-2 text-sm text-gray-700 dark:text-gray-200"
                                            aria-labelledby="fungsiDropdownButton">
                                            @foreach ($fungsis as $fungsi)
                                                <li>
                                                    <a href="#" data-fungsi="{{ $fungsi->slug }}"
                                                        class="block px-4 py-2 hover:bg-gray-100 ">
                                                        {{ $fungsi->nama }}
                                                    </a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <!-- Tabel Kehadiran -->
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
                        <div class="w-full md:w-1/2 pt-3 sm:pt-0 relative">
                            <!-- Tombol IPDS di pojok kanan atas -->
                            <button id="fungsiDropdownButton" data-dropdown-toggle="fungsiDropdownMenu" type="button"
                                class=" absolute z-10 sm:top-0 right-0 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-700 inline-flex items-center shadow-sm">
                                <span id="fungsiDropdownLabel">IPDS</span>
                                <svg class="w-3 h-3 ml-2" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 10 6">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" d="M1 1l4 4 4-4" />
                                </svg>
                            </button>

                            <!-- Judul di atas grafik, rata tengah -->
                            <h2
                                class="text-xl font-semibold mt-11 2xl:mt-1.5 mb-4 text-gray-800 dark:text-white text-center">
                                Grafik Kehadiran
                            </h2>

                            <!-- Grafik -->
                            <div id="empty-piechart-state"
                                class="absolute pointer-events-none inset-0 flex flex-col items-center justify-center text-gray-500 text-sm text-center {{ array_sum($chartData[$initialFungsi] ?? []) > 0 ? 'hidden' : '' }}">
                                <svg class="w-6 h-6 text-gray-800 dark:text-white mb-2"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M5 1v3m5-3v3m5-3v3M1 7h18M5 11h10M2 3h16a1 1 0 0 1 1 1v14a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V4a1 1 0 0 1 1-1Z" />
                                </svg>
                                Belum ada data absensi untuk ditampilkan.
                            </div>

                            <div id="pie-chart-canvas"
                                class="w-full max-w-lg mx-auto {{ array_sum($chartData[$initialFungsi] ?? []) === 0 ? 'hidden' : '' }}">
                            </div>


                            <!-- 🔽 Dropdown Filter (Non-AJAX Version) -->
                            <div class="grid grid-cols-1 items-center absolute sm:top-75 right-0">
                                <div class="flex justify-between items-center pt-5">
                                    <button id="dropdownDefaultButton" data-dropdown-toggle="lastDaysdropdown"
                                        data-dropdown-placement="top"
                                        class="text-sm font-medium text-gray-500 dark:text-gray-400 hover:text-gray-900 text-center inline-flex items-center dark:hover:text-white"
                                        type="button">
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
                                                $currentRange = request('range', 'today');
                                            @endphp
                                            {{ $labels[$currentRange] ?? 'Hari ini' }}
                                        </span>
                                        <svg class="w-2.5 m-2.5 ms-1.5" aria-hidden="true"
                                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                                stroke-width="2" d="m1 1 4 4 4-4" />
                                        </svg>
                                    </button>
                                    <div id="lastDaysdropdown"
                                        class="z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow-sm w-44 dark:bg-gray-700">
                                        <ul class="py-2 text-sm text-gray-700 dark:text-gray-200"
                                            aria-labelledby="dropdownDefaultButton">

                                            <li>
                                                <a href="#" data-range="today"
                                                    class="range-option block px-4 py-2 hover:bg-gray-200 dark:hover:bg-gray-600 dark:hover:text-white">
                                                    Hari ini
                                                </a>
                                            </li>

                                            @if ($isAdmin)
                                                <li>
                                                    <a href="#" data-range="yesterday"
                                                        class="range-option block px-4 py-2 hover:bg-gray-200 dark:hover:bg-gray-600 dark:hover:text-white">
                                                        Kemarin
                                                    </a>
                                                </li>
                                            @endif

                                            <li>
                                                <a href="#" data-range="7"
                                                    class="range-option block px-4 py-2 hover:bg-gray-200 dark:hover:bg-gray-600 dark:hover:text-white">
                                                    1 minggu terakhir
                                                </a>
                                            </li>
                                            <li>
                                                <a href="#" data-range="30"
                                                    class="range-option block px-4 py-2 hover:bg-gray-200 dark:hover:bg-gray-600 dark:hover:text-white">
                                                    1 bulan terakhir
                                                </a>
                                            </li>
                                            <li>
                                                <a href="#" data-range="60"
                                                    class="range-option block px-4 py-2 hover:bg-gray-200 dark:hover:bg-gray-600 dark:hover:text-white">
                                                    2 bulan terakhir
                                                </a>
                                            </li>
                                            <li>
                                                <a href="#" data-range="all"
                                                    class="range-option block px-4 py-2 hover:bg-gray-200 dark:hover:bg-gray-600 dark:hover:text-white">
                                                    Sepanjang waktu
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>


                    </div>

                </article>
            </div>
            <!-- Start block -->
            <section class= "dark:bg-gray-900 py-3 sm:p-5 antialiased">
                <div class=" mx-auto max-w-screen-2xl px-4 sm:px-0">
                    <div class="bg-white dark:bg-gray-800 relative border border-gray-200 rounded-lg overflow-hidden">
                        <div class="flex items-center justify-between p-5">
                            <p id="tabelAnggotaTitle" class="text-xl font-semibold text-gray-900 dark:text-white">
                                Anggota Fungsi:</p>
                        </div>
                        <div
                            class="flex flex-col md:flex-row items-stretch md:items-center md:space-x-3 space-y-3 md:space-y-0 justify-between mx-4 py-4 border-t border-gray-200 dark:border-gray-700">
                            <div class="w-full md:w-1/2">
                                <form class="flex items-center">
                                    <label for="simple-search" class="sr-only">Search</label>
                                    <div class="relative w-full">
                                        <div
                                            class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                            <svg aria-hidden="true" class="w-5 h-5 text-gray-500 dark:text-gray-400"
                                                fill="currentColor" viewbox="0 0 20 20"
                                                xmlns="http://www.w3.org/2000/svg">
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
                                        <th scope="col" class="p-4">Jenis Kelamin</th>
                                        <th scope="col" class="p-4">Status</th>
                                        <th scope="col" class="p-4"></th>
                                        <th scope="col" class="p-4">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="anggotaFungsiTableBody">
                                    @foreach ($processedUsers as $item)
                                        <tr data-status="{{ strtolower($item['status']) }}"
                                            data-fungsi="{{ strtolower($item['user']->fungsi->slug ?? '') }}" class="border-t">
                                            <td class="px-4 py-3">{{ $loop->iteration }}</td>
                                            <td class="px-4 py-3 text-black">
                                                <div class="flex items-center gap-3">
                                                    <img class="w-8 h-8 rounded-full"
                                                        src="{{ $item['user']->foto ? asset('storage/' . $item['user']->foto) : asset('img/Anonymous.png') }}"
                                                        alt="{{ $item['user']->name }}">
                                                    {{ $item['user']->name }}
                                                </div>
                                            </td>
                                            <td class="px-4 py-3 text-black">{{ $item['user']->nim ?? '-' }}</td>
                                            <td class="px-4 py-3">
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
                                                <x-dropdown-action :user="$item['user']" :rowId="$loop->iteration" />
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </section>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/datepicker.min.js"></script>
        </main>
    @else
        {{-- Optional: Jika kamu mau tampilkan pesan untuk non-admin --}}
        <section class="bg-white dark:bg-gray-900">
            <div class="py-8 px-4 mx-auto max-w-screen-xl lg:pt-40 lg:px-6">
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

</x-layout>
<!-- Script Chart.js & Table Update -->
<script>
    const pieChartDataByfungsi = @json($chartData);
    const pieChartLabels = @json($statuses->pluck('nama'));
    const urlParams = new URLSearchParams(window.location.search);
    const initialFungsi = urlParams.get('fungsi') || Object.keys(pieChartDataByfungsi)[0];

    let chartInstance;

    function renderPieChart(data) {
        const pieCanvas = document.querySelector("#pie-chart-canvas");
        const emptyState = document.getElementById("empty-piechart-state");

        if (chartInstance) {
            chartInstance.destroy();
        }

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

    function filterAnggotaFungsi(selectedFungsi) {
        const searchValue = document.getElementById('simple-search').value.toLowerCase();
        const rows = document.querySelectorAll('tbody tr[data-fungsi]');
        let visibleCount = 0;

        rows.forEach(row => {
            const rowFungsi = row.getAttribute('data-fungsi');
            const name = row.querySelector('th, td')?.innerText.toLowerCase() || '';

            const matchFungsi = rowFungsi === selectedFungsi.toLowerCase();
            const matchSearch = name.includes(searchValue);

            const shouldShow = matchFungsi && matchSearch;
            row.style.display = shouldShow ? '' : 'none';

            if (shouldShow) visibleCount++;
        });

        const tbody = document.querySelector('#anggotaFungsiTableBody');
        const existingEmptyRow = document.getElementById('empty-message-row');

        if (visibleCount === 0) {
            // Jika belum ada baris kosong, tambahkan
            if (!existingEmptyRow) {
                const colCount = document.querySelectorAll('thead tr th').length;
                const emptyRow = document.createElement('tr');
                emptyRow.id = 'empty-message-row';
                emptyRow.innerHTML = `<td colspan="${colCount}" class="text-center text-gray-500 py-6">
                Tidak ada data pengguna untuk ditampilkan.
            </td>`;
                tbody.appendChild(emptyRow);
            }
        } else {
            // Hapus baris kosong jika ada baris yang ditampilkan
            if (existingEmptyRow) {
                existingEmptyRow.remove();
            }
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        renderPieChart(pieChartDataByfungsi[initialFungsi]);
        updateTable(pieChartDataByfungsi[initialFungsi]);

        // Set label dropdown ke nama fungsi yang dipilih
        const selectedText = document.querySelector(`[data-fungsi="${initialFungsi}"]`)?.textContent;
        if (selectedText) {
            document.getElementById('fungsiDropdownLabel').textContent = selectedText;
            document.getElementById('tabelKehadiranTitle').textContent = `Tabel Kehadiran: ${selectedText}`;
            document.getElementById('tabelAnggotaTitle').textContent = `Anggota Fungsi: ${selectedText}`;
        }

        // Tandai tombol fungsi yg aktif awalnya
        document.querySelectorAll('[data-fungsi]').forEach(el => el.classList.remove('active'));
        const activeEl = document.querySelector(`[data-fungsi="${initialFungsi}"]`);
        if (activeEl) activeEl.classList.add('active');

        // Event klik untuk filter fungsi dan update chart/table
        document.querySelectorAll('[data-fungsi]').forEach(item => {
            item.addEventListener('click', event => {
                // Cegah klik yang terjadi di dalam tabel (tbody)
                if (event.target.closest('tbody')) {
                    return; // jangan lanjut, ini klik di tabel anggota
                }

                // Hapus kelas active di semua tombol fungsi, lalu aktifkan yang diklik
                document.querySelectorAll('[data-fungsi]').forEach(el => el.classList.remove(
                    'active'));
                event.target.classList.add('active');

                const selectedFungsi = event.target.getAttribute('data-fungsi');
                const data = pieChartDataByfungsi[selectedFungsi];

                renderPieChart(data);
                updateTable(data);

                document.getElementById('fungsiDropdownLabel').textContent = event.target
                    .textContent;
                document.getElementById('tabelKehadiranTitle').textContent =
                    `Tabel Kehadiran: ${event.target.textContent}`;

                document.getElementById('tabelAnggotaTitle').textContent =
                    `Anggota Fungsi: ${event.target.textContent}`;

                history.replaceState(null, '', `?fungsi=${selectedFungsi}`);

                filterAnggotaFungsi(selectedFungsi);
            });
        });

        // Event input search
        document.getElementById('simple-search').addEventListener('input', function() {
            const selectedFungsi = document.querySelector('[data-fungsi].active')?.getAttribute(
                'data-fungsi') || initialFungsi;
            filterAnggotaFungsi(selectedFungsi);
        });

        // Initial filter call
        filterAnggotaFungsi(initialFungsi);

        document.querySelectorAll('.range-option').forEach(item => {
            item.addEventListener('click', function(e) {
                e.preventDefault();

                const selectedRange = this.getAttribute('data-range');
                const selectedFungsi = document.querySelector('[data-fungsi].active')
                    ?.getAttribute('data-fungsi') || initialFungsi;

                // Update URL
                const newUrl = `?fungsi=${selectedFungsi}&range=${selectedRange}`;
                window.location.href = newUrl; // reload for now (recommended for easier sync)
            });
        });

    });
</script>
