<x-layout :title="$title">
    @if (Session::has('success'))
        <div class="flex justify-center">
            <div id="toast-success"
                class="absolute top-[5rem] flex items-center w-full max-w-xs p-4 mb-4 text-gray-500 bg-white rounded-lg shadow-sm dark:text-gray-400 dark:bg-gray-800"
                role="alert">
                <div
                    class="inline-flex items-center justify-center shrink-0 w-8 h-8 text-green-500 bg-green-100 rounded-lg dark:bg-green-800 dark:text-green-200">
                    <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                        viewBox="0 0 20 20">
                        <path
                            d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 8.207-4 4a1 1 0 0 1-1.414 0l-2-2a1 1 0 0 1 1.414-1.414L9 10.586l3.293-3.293a1 1 0 0 1 1.414 1.414Z" />
                    </svg>
                    <span class="sr-only">Check icon</span>
                </div>
                <div class="ms-3 text-sm font-normal">{{ Session::get('success') }}</div>
                <button type="button"
                    class="ms-auto -mx-1.5 -my-1.5 bg-white text-gray-400 hover:text-gray-900 rounded-lg focus:ring-2 focus:ring-gray-300 p-1.5 hover:bg-gray-100 inline-flex items-center justify-center h-8 w-8 dark:text-gray-500 dark:hover:text-white dark:bg-gray-800 dark:hover:bg-gray-700"
                    data-dismiss-target="#toast-success" aria-label="Close">
                    <span class="sr-only">Close</span>
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                    </svg>
                </button>
            </div>
        </div>
        <div>
            <script>
                setTimeout(() => {
                    const toast = document.getElementById('toast-success');
                    if (toast) {
                        toast.classList.add('opacity-0', 'transition-opacity', 'duration-500');

                        setTimeout(() => {
                            toast.remove();
                        }, 500);
                    }
                }, 5000);
            </script>
        </div>
    @elseif(Session::has('error'))
        <div class="flex justify-center">
            <div id="toast-error"
                class="absolute top-[4.5rem] flex items-center w-full max-w-xs p-4 mb-4 text-gray-500 bg-white rounded-lg shadow-sm dark:text-gray-400 dark:bg-gray-800"
                role="alert">
                <div class="inline-flex items-center justify-center w-8 h-8 bg-red-100 rounded-lg dark:bg-red-800">
                    <div class="w-5 h-5 bg-red-500 rounded-full flex items-center justify-center">
                        <svg class="text-white w-3.5 h-3.5 mx-auto" aria-hidden="true" fill="currentColor"
                            viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                d="M10 4a1 1 0 011 1v6a1 1 0 11-2 0V5a1 1 0 011-1zm0 10a1.5 1.5 0 100 3 1.5 1.5 0 000-3z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    <span class="sr-only">Delete icon</span>
                </div>
                <div class="ms-3 text-sm font-normal">{{ Session::get('error') }}</div>
                <button type="button"
                    class="ms-auto -mx-1.5 -my-1.5 bg-white text-gray-400 hover:text-gray-900 rounded-lg focus:ring-2 focus:ring-gray-300 p-1.5 hover:bg-gray-100 inline-flex items-center justify-center h-8 w-8 dark:text-gray-500 dark:hover:text-white dark:bg-gray-800 dark:hover:bg-gray-700"
                    data-dismiss-target="#toast-error" aria-label="Close">
                    <span class="sr-only">Close</span>
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                    </svg>
                </button>
            </div>
            <div>
                <script>
                    setTimeout(() => {
                        const toast = document.getElementById('toast-error');
                        if (toast) {
                            toast.classList.add('opacity-0', 'transition-opacity', 'duration-500');

                            setTimeout(() => {
                                toast.remove();
                            }, 500);
                        }
                    }, 5000);
                </script>
            </div>
    @endif
    @php
        $isAdmin = Auth::user()->is_admin;
        $isOwner = false;
    @endphp
    <div class="flex flex-col justify-start gap-x-15 mx-7 max-h-107 mt-3">
        @if (!$isAdmin)
            <div class="mb-3 w-full mt-4.5 flex flex-col sm:flex-row gap-3">
                <!-- Tombol Absen Sekarang -->
                <a href="/absensi/{{ Auth::user()->slug }}"
                    class="inline-flex justify-center items-center w-full sm:w-1/2 py-2 text-white text-sm bg-emerald-600 hover:bg-emerald-800 focus:ring-4 focus:ring-emerald-300 font-medium rounded-lg shadow focus:outline-none">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Absen Sekarang
                </a>

                <!-- Tombol Presensi Detail -->
                <a href="/presensi-detail/{{ Auth::user()->slug }}"
                    class="inline-flex justify-center items-center w-full sm:w-1/2 py-2 text-white text-sm bg-blue-600 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg shadow focus:outline-none">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M15 12H9m12 0A9 9 0 11 3 12a9 9 0 0118 0z" />
                    </svg>
                    Presensi Detail
                </a>
            </div>
        @endif
        <div class="flex flex-col sm:flex-row gap-x-10">
            <div class="rounded-lg py-3 mb-1.5 border sm:w-3xl border-gray-200 whitespace-nowrap">
                @if (!$isAdmin)
                    <p class="text-center font-semibold border-b border-gray-200 pb-3.5">Statistik Kehadiran Anda</p>
                @else
                    <p class="text-center font-semibold border-b border-gray-200 pb-3.5">
                        Total absensi user : <span id="totalAbsensi">{{ $totalAbsensi }}</span>
                    </p>
                @endif
                <div class="max-w-3xl bg-white rounded-lg dark:bg-gray-800 p-4 md:p-6">

                    <!-- Pie Chart Container -->
                    <div class="py-1 pb-12 relative min-h-[350px]">
                        <div id="empty-state"
                            class="absolute inset-0 flex flex-col items-center justify-center text-gray-500 text-sm text-center {{ $hasData ? 'hidden' : '' }}">
                            <svg class="w-6 h-6 text-gray-800 dark:text-white mb-2" aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M5 1v3m5-3v3m5-3v3M1 7h18M5 11h10M2 3h16a1 1 0 0 1 1 1v14a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V4a1 1 0 0 1 1-1Z" />
                            </svg>
                            Belum ada data absensi untuk ditampilkan.
                        </div>

                        <div id="pie-chart"></div> {{-- <<-- Piechart disini --}}
                        <script>
                            document.addEventListener("DOMContentLoaded", function() {
                                const labels = @json($statusCounts->pluck('nama'));
                                const series = @json($statusCounts->pluck('user_count'));
                                const isAdmin = @json($isAdmin); // ambil role dari backend

                                // Inisialisasi chart
                                const chartOptions = {
                                    chart: {
                                        type: 'pie',
                                        height: 350
                                    },
                                    series: series,
                                    labels: labels,
                                    colors: ['#10B981', '#EAB308', '#3B82F6', '#DC2626'],
                                    legend: {
                                        show: true
                                    },
                                    dataLabels: {
                                        enabled: true,
                                        formatter: (val) => `${val.toFixed(1)}%`
                                    }
                                };
                                const chart = new ApexCharts(document.querySelector("#pie-chart"), chartOptions);
                                chart.render();

                                // Handle dropdown filter
                                document.querySelectorAll(".dropdown-range").forEach(item => {
                                    item.addEventListener("click", function(e) {
                                        e.preventDefault();
                                        const range = this.dataset.range;

                                        // Update teks tombol
                                        const dropdownText = document.getElementById("dropdownButtonText");
                                        if (dropdownText) dropdownText.textContent = this.innerText.trim();

                                        // Ambil data chart baru (AJAX tetap dipakai untuk chart)
                                        fetch(`/absensi/chart/${range}`)
                                            .then(res => res.json())
                                            .then(data => {
                                                const newLabels = data.statusCounts.map(item => item.nama);
                                                const newSeries = data.statusCounts.map(item => item.user_count);

                                                chart.updateOptions({
                                                    labels: newLabels
                                                });
                                                chart.updateSeries(newSeries);

                                                // Update total absensi
                                                const totalAbsensiEl = document.getElementById("totalAbsensi");
                                                if (totalAbsensiEl) {
                                                    totalAbsensiEl.textContent = data.totalAbsensi ?? 0;
                                                }

                                                // Tampilkan / sembunyikan empty state
                                                const emptyState = document.getElementById("empty-state");
                                                if (newSeries.reduce((a, b) => a + b, 0) === 0) {
                                                    emptyState.classList.remove("hidden");
                                                } else {
                                                    emptyState.classList.add("hidden");
                                                }

                                                // ⛳️ Redirect ke dashboard untuk load ulang table (biar pagination bisa jalan)
                                                window.location.href = `/dashboard?range=${range}`;
                                            });
                                    });
                                });
                            });
                        </script>
                    </div>

                    <!-- 🔽 Dropdown Filter (Non-AJAX Version) -->
                    <div
                        class="grid grid-cols-1 items-center border-gray-200 border-t dark:border-gray-700 justify-between">
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
                                            '7' => '7 hari terakhir',
                                            '30' => '30 hari terakhir',
                                            '90' => '90 hari terakhir',
                                            'all' => 'Sepanjang waktu',
                                        ];
                                        $currentRange = request('range', 'today');
                                    @endphp
                                    {{ $labels[$currentRange] ?? 'Hari ini' }}
                                </span>
                                <svg class="w-2.5 m-2.5 ms-1.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 10 6">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" d="m1 1 4 4 4-4" />
                                </svg>
                            </button>
                            <div id="lastDaysdropdown"
                                class="z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow-sm w-44 dark:bg-gray-700">
                                <ul class="py-2 text-sm text-gray-700 dark:text-gray-200"
                                    aria-labelledby="dropdownDefaultButton">

                                    <li>
                                        <a href="?range=today"
                                            class="block px-4 py-2 hover:bg-gray-200 dark:hover:bg-gray-600 dark:hover:text-white">
                                            Hari ini
                                        </a>
                                    </li>

                                    @if ($isAdmin)
                                        <li>
                                            <a href="?range=yesterday"
                                                class="block px-4 py-2 hover:bg-gray-200 dark:hover:bg-gray-600 dark:hover:text-white">
                                                Kemarin
                                            </a>
                                        </li>
                                    @endif

                                    <li>
                                        <a href="?range=7"
                                            class="block px-4 py-2 hover:bg-gray-200 dark:hover:bg-gray-600 dark:hover:text-white">
                                            7 hari terakhir
                                        </a>
                                    </li>
                                    <li>
                                        <a href="?range=30"
                                            class="block px-4 py-2 hover:bg-gray-200 dark:hover:bg-gray-600 dark:hover:text-white">
                                            30 hari terakhir
                                        </a>
                                    </li>
                                    <li>
                                        <a href="?range=90"
                                            class="block px-4 py-2 hover:bg-gray-200 dark:hover:bg-gray-600 dark:hover:text-white">
                                            90 hari terakhir
                                        </a>
                                    </li>
                                    <li>
                                        <a href="?range=all"
                                            class="block px-4 py-2 hover:bg-gray-200 dark:hover:bg-gray-600 dark:hover:text-white">
                                            Sepanjang waktu
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <div class="rounded-lg py-3 mt-1 sm:mt-0 mb-1.5 border w-full border-gray-200 flex flex-col min-h-[600px]">
                <div class="flex flex-row items-center justify-between border-b border-gray-200 pb-3 px-4">
                    <!-- Tengah (Judul) -->
                    @if (!$isAdmin)
                        <div class="text-center font-semibold text-base">Tabel Data Kehadiran Anda</div>
                    @else
                        <div class="text-center font-semibold text-base">Tabel Data Kehadiran User</div>
                    @endif

                    <!-- Kanan (Tombol Filter) -->
                    <div>
                        <button id="filterDropdownButton" data-dropdown-toggle="filterDropdown"
                            class="flex items-center justify-center text-white bg-emerald-600 hover:bg-emerald-800 focus:ring-4 focus:ring-emerald-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-emerald-600 dark:hover:bg-emerald-700 focus:outline-none dark:focus:ring-emerald-800"
                            type="button">
                            <svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true" class="h-4 w-4 mr-1.5 -ml-1"
                                viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M3 3a1 1 0 011-1h12a1 1 0 011 1v3a1 1 0 01-.293.707L12 11.414V15a1 1 0 01-.293.707l-2 2A1 1 0 018 17v-5.586L3.293 6.707A1 1 0 013 6V3z"
                                    clip-rule="evenodd" />
                            </svg>
                            Filter
                            <svg class="-mr-1 ml-1.5 w-5 h-5" fill="currentColor" viewBox="0 0 20 20"
                                xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                <path clip-rule="evenodd" fill-rule="evenodd"
                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" />
                            </svg>
                        </button>

                        <div
                            class="w-full md:w-auto flex flex-col md:flex-row space-y-2 md:space-y-0 items-stretch md:items-center justify-end md:space-x-3 flex-shrink-0">

                            <div id="filterDropdown"
                                class="z-10 hidden px-3 pt-1 bg-white rounded-lg shadow w-80 dark:bg-gray-700 right-0 max-h-72 overflow-y-auto">
                                <div class="flex items-center justify-between pt-2 pb-2 border-b border-gray-200">
                                    <h6 class="text-sm font-medium text-black dark:text-white">Filters</h6>
                                    <div class="flex items-center space-x-3">
                                        <button type="button"
                                            class="text-sm font-medium text-emerald-600 dark:text-emerald-500 hover:underline"
                                            onclick="applyFilter()">Terapkan</button>
                                        <button type="button"
                                            class="text-sm font-medium text-emerald-600 dark:text-emerald-500 hover:underline"
                                            onclick="resetFilter()">Reset filter</button>
                                    </div>
                                </div>
                                <div id="accordion-flush" data-accordion="collapse"
                                    data-active-classes="text-black dark:text-white"
                                    data-inactive-classes="text-gray-500 dark:text-gray-400">
                                    <!-- Kehadiran -->
                                    <h2 id="kehadiran-heading">
                                        <button type="button"
                                            class="flex items-center justify-between w-full py-2 px-1.5 text-sm font-medium text-left text-gray-500 border-gray-200 dark:border-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700 border-b-1"
                                            data-accordion-target="#kehadiran-body" aria-expanded="true"
                                            aria-controls="category-body">
                                            <span>Kehadiran</span>
                                            <svg aria-hidden="true" data-accordion-icon=""
                                                class="w-5 h-5 rotate-180 shrink-0" fill="currentColor"
                                                viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
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
                                                            type="checkbox" name="status"
                                                            value="{{ $status->nama }}"
                                                            class="w-4 h-4 bg-gray-100 border-gray-300 rounded text-emerald-600 focus:ring-emerald-500 dark:focus:ring-emerald-600 dark:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500">
                                                        <label for="status-{{ Str::slug($status->nama) }}"
                                                            class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-100">{{ $status->nama }}</label>
                                                    </li>
                                                </ul>
                                            @endforeach
                                        </div>
                                    </div>

                                    <!-- Fungsi -->
                                    <h2 id="fungsi-heading">
                                        <button type="button"
                                            class="flex items-center justify-between w-full py-2 px-1.5 text-sm font-medium text-left text-gray-500 border-gray-300 dark:border-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700"
                                            data-accordion-target="#fungsi-body" aria-expanded="true"
                                            aria-controls="fungsi-body">
                                            <span>Fungsi</span>
                                            <svg aria-hidden="true" data-accordion-icon=""
                                                class="w-5 h-5 rotate-180 shrink-0" fill="currentColor"
                                                viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
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
                                                            type="checkbox" name="fungsi"
                                                            value="{{ $fungsi->nama }}"
                                                            class="w-4 h-4 bg-gray-100 border-gray-300 rounded text-emerald-600 focus:ring-emerald-500 dark:focus:ring-emerald-600 dark:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500">
                                                        <label for="fungsi-{{ Str::slug($fungsi->nama) }}"
                                                            class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-100">{{ $fungsi->nama }}</label>
                                                    </li>
                                                </ul>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex-grow overflow-y-auto max-h-[447px]">
                    {{-- Tabel Kehadiran --}}
                    <table class="min-w-full text-sm text-left text-gray-500">
                        <thead class="bg-gray-100 text-xs text-gray-700 uppercase">
                            <tr>
                                @if (!$isAdmin)
                                    <th class="p-4">ID</th>
                                    <th class="p-4">Nama</th>
                                    <th class="p-4">Tanggal</th>
                                    <th class="p-4">Jam Masuk</th>
                                    <th class="p-4">Jam Keluar</th>
                                    <th class="p-4">Status</th>
                                    <th class="p-4">Keterangan</th>
                                    <th class="p-4">Aksi</th>
                                @else
                                    <th class="p-4">ID</th>
                                    <th class="p-4">Nama</th>
                                    <th class="p-4">NIM/NISN</th>
                                    <th class="p-4">Fungsi</th>
                                    <th class="p-4">Status</th>
                                    <th class="p-4"></th>
                                    <th class="p-4">Aksi</th>
                                @endif
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-200" id="recap-body">
                            @if ($isAdmin)
                                @forelse ($processedUsers as $index => $item)
                                    <tr class="border-b">
                                        <td class="px-4 py-3">{{ $index + 1 }}</td>
                                        <td class="px-4 py-3">
                                            <div class="flex items-center gap-3 text-black">
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
                                        <td class="px-4 py-3">
                                            <x-dropdown-action :user="$item['user']" />
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center text-gray-500 pt-50">
                                            Belum ada pengguna yang absen...
                                        </td>
                                    </tr>
                                @endforelse
                            @else
                                @forelse ($absensisPaginated as $absensi)
                                    <tr class="border-b">
                                        <td class="px-4 py-3">{{ $loop->iteration }}</td>
                                        <td class="px-4 py-3">
                                            <div class="flex items-center gap-3 text-black">
                                                <img class="w-8 h-8 rounded-full"
                                                    src="{{ $absensi->user->foto ? asset('storage/' . $absensi->user->foto) : asset('img/Anonymous.png') }}"
                                                    alt="{{ $absensi->user->name }}">
                                                {{ $absensi->user->name }}
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 text-black">{{ $absensi->tanggal ?? '-' }}</td>
                                        <td class="px-4 py-3 text-black">{{ $absensi->jam_masuk ?? '-' }}</td>
                                        <td class="px-4 py-3 text-black">{{ $absensi->jam_keluar ?? '-' }}</td>
                                        <td class="px-4 py-3">
                                            <div class="flex items-center">
                                                <div
                                                    class="h-4 w-4 rounded-full inline-block mr-2 {{ $absensi->status->warna ?? 'bg-gray-300' }}">
                                                </div>
                                                {{ $absensi->status->nama ?? '-' }}
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 text-black">
                                            @if (!empty($absensi->judul))
                                                <a href="/keterangan/{{ $absensi->slug }}" class="hover:underline">
                                                    {{ Str::limit($absensi->judul, 10) }}
                                                </a>
                                            @else
                                                <span class="text-gray-500 italic">-</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3">
                                            <a href="/users/{{ $absensi->user->slug }}"
                                                class="inline-flex items-center text-blue-600 hover:text-blue-800 hover:underline gap-1.5">
                                                <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg"
                                                    fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                                        d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" />
                                                </svg>
                                                <span class="text-sm font-medium">Lihat</span>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center text-gray-500 py-50">
                                            Anda belum memiliki data absen hari ini...
                                        </td>
                                    </tr>
                                @endforelse
                            @endif

                        </tbody>
                    </table>
                    <script>
                        // Kirim info role ke JS agar bisa digunakan saat rendering
                        window.isAdmin = @json($isAdmin);
                    </script>
                    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.js"></script>
                </div>

                {{-- Pagination --}}
                @if ($isAdmin && $users->hasPages())
                    <div class="border-t pt-3.5 border-gray-200">
                        {{ $users->onEachSide(0)->links('pagination::tailwind') }}
                    </div>
                @elseif (!$isAdmin && $absensisPaginated->hasPages())
                    <div class="border-t pt-3.5 border-gray-200">
                        {{ $absensisPaginated->onEachSide(0)->links('pagination::tailwind') }}
                    </div>
                @endif

            </div>
        </div>
        <!-- Start block -->
        @if ($isAdmin)
            <section class="pt-4 pb-20 antialiased">
                <div class="mx-auto">
                    <div class="bg-white relative border border-gray-200 sm:rounded-lg overflow-hidden">
                        <div class="flex items-center justify-between p-5 border-b border-gray-200">
                            <p class="text-xl font-semibold text-gray-900 dark:text-white">Aktivitas Terkini</p>
                        </div>

                        <!-- Container marquee dengan wrapper -->
                        <div id="marquee-wrapper">
                            <div id="marquee" class="flex gap-6 pt-4 pb-4">
                                <!-- Cards injected by JS -->
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        @endif

        <style>
            #marquee-wrapper {
                overflow: hidden;
                position: relative;
                width: 100%;
                height: auto;
            }

            #marquee {
                display: flex;
                gap: 1rem;
                animation: scroll-marquee 20s linear infinite;
                will-change: transform;
            }

            #marquee:hover {
                animation-play-state: paused;
            }

            @keyframes scroll-marquee {
                0% {
                    transform: translateX(100%);
                }

                100% {
                    transform: translateX(-100%);
                }
            }

            .testimonial-card {
                min-width: 250px;
                max-width: 300px;
                flex-shrink: 0;
                border-radius: 12px;
                transition: border-color 0.3s ease;
            }

            .testimonial-card:hover {
                border-color: #065f46;
            }

            .empty-message {
                text-align: center;
                padding: 2rem;
                color: #6b7280;
                font-size: 0.875rem;
                width: 100%;
            }
        </style>

        <script>
            const testimonials = [];
            const marquee = document.getElementById('marquee');

            @if ($users->isEmpty())
                marquee.style.animation = 'none';
                marquee.style.justifyContent = 'center';

                const emptyMessage = document.createElement('p');
                emptyMessage.className = 'empty-message';
                emptyMessage.textContent = 'Belum ada pengguna yang absen saat ini.';
                marquee.appendChild(emptyMessage);
            @else
                @foreach ($users as $user)
                    testimonials.push({
                        img: "{{ $user->foto ? asset('storage/' . $user->foto) : asset('img/Anonymous.png') }}",
                        slug: "/users/{{ $user->slug }}",
                        name: "{{ $user->name }}",
                        text: "{{ $user->name }} Absen Tepat waktu! 🎊🎉"
                    });
                @endforeach

                function createCard(testimonial) {
                    const div = document.createElement('div');
                    div.className =
                        'testimonial-card bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md border border-gray-200';
                    div.innerHTML = `
            <div class="flex items-center mb-2">
                <img class="w-8 h-8 rounded-full" src="${testimonial.img}" alt="${testimonial.name}">
                <a class="pl-2 text-green-700 dark:text-green-500 font-semibold hover:underline" href="${testimonial.slug}">
                    ${testimonial.name}
                </a>
            </div>
            <p class="text-gray-800 dark:text-gray-300 text-sm leading-relaxed">${testimonial.text}</p>
        `;
                    return div;
                }

                // Isi konten utama
                testimonials.forEach(t => marquee.appendChild(createCard(t)));

                // Duplikat konten agar animasi bisa loop tanpa jeda
            @endif
        </script>
    </div>




    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/datepicker.min.js"></script>
</x-layout>

<script>
    function resetFilter() {
        window.location.href = window.location.pathname;
    }

    function applyFilter() {
        const checkedStatuses = Array.from(document.querySelectorAll('#kehadiran-body input[type="checkbox"]:checked'))
            .map(cb => cb.value);

        const checkedFunctions = Array.from(document.querySelectorAll('#fungsi-body input[type="checkbox"]:checked'))
            .map(cb => cb.value);

        const params = new URLSearchParams(window.location.search);

        // Hapus parameter lama tanpa []
        params.delete('status');
        params.delete('fungsi');

        // Tambahkan parameter status dan fungsi
        checkedStatuses.forEach(status => params.append('status', status));
        checkedFunctions.forEach(fungsi => params.append('fungsi', fungsi));

        const url = `${window.location.pathname}?${params.toString()}`;
        window.location.href = url;
    }


    // Event listener untuk tombol filter buka/tutup dropdown
    document.getElementById('filterDropdownButton').addEventListener('click', () => {
        const dd = document.getElementById('filterDropdown');
        dd.classList.toggle('hidden');
    });
</script>
