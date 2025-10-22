<x-layout :title="$title">
    @if (Session::has('success'))
        <div class="flex justify-center">
            <div id="toast-success"
                class="absolute top-[4.5rem] z-10 flex items-center w-full max-w-lg p-4 mb-4 text-gray-500 bg-white rounded-lg shadow-sm dark:text-gray-400 dark:bg-gray-800"
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
                }, 3000);
            </script>
        </div>
    @elseif(Session::has('error'))
        <div class="flex justify-center">
            <div id="toast-error"
                class="absolute top-[4.5rem] z-10 flex items-center w-full max-w-lg p-4 mb-4 text-gray-500 bg-white rounded-lg shadow-sm dark:text-gray-400 dark:bg-gray-800"
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
                    }, 3000);
                </script>
            </div>
        </div>
    @endif
    @php
        $isAdmin = Auth::user()->is_admin;
        $isOwner = false;
    @endphp
    @if (isset($isAlumni) && $isAlumni && session('show_alumni_popup'))
        <div id="alumni-popup"
            class="fixed inset-0 z-100 flex items-center justify-center bg-opacity-50 backdrop-blur-sm ">
            <div
                class="bg-white dark:bg-gray-800 rounded-2xl border w-[90%] max-w-md p-6 text-center relative animate-fadeIn">
                <!-- Tombol close -->
                <button onclick="closePopup()"
                    class="absolute top-3 right-3 text-gray-500 hover:text-gray-800 dark:hover:text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>

                <div class="flex flex-col items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 17.211 17.211"
                        class="w-12 h-12 text-emerald-600 mb-3 fill-current">
                        <g>
                            <g>
                                <path
                                    d="M14.148,7.781l-5.579,3.327L3.097,7.781l-0.455,2.841c0.002-0.004,0.006-0.007,0.011-0.013c-0.065,0.109-0.102,0.222-0.102,0.337c0,1.003,4.418,4.094,5.821,4.094c1.404,0,6.323-3.091,6.323-4.094c0-0.109-0.036-0.214-0.092-0.532L14.148,7.781z" />
                                <path
                                    d="M17.211,4.873L8.635,2.17l-7.66,2.443C0.867,4.63,0.765,4.658,0.684,4.706l0,0l0,0c-0.098,0.058-0.165,0.142-0.17,0.266C0.473,5.468,0,11.17,0,11.17l0.514,1.708l0.477-1.708L0.789,5.294l7.823,4.813L17.211,4.873z M8.015,5.118H0.783l0.756-0.413h6.385C8.007,4.56,8.203,4.458,8.431,4.458c0.304,0,0.55,0.181,0.55,0.403c0,0.223-0.246,0.404-0.55,0.404C8.261,5.267,8.115,5.207,8.015,5.118z" />
                            </g>
                        </g>
                    </svg>

                    <h2 class="text-lg font-semibold text-gray-800 dark:text-white mb-2">
                        Selamat! 🎉
                    </h2>
                    <p class="text-gray-600 dark:text-gray-300 mb-4 text-sm leading-relaxed">
                        Masa periode magang kamu telah berakhir.<br>
                        Kamu kini resmi menjadi <b>Alumni Magang BPS Provinsi Sulawesi Selatan.</b><br>
                        Terima kasih atas kontribusimu selama ini 🙏
                    </p>

                    <!-- Tombol atau link info -->
                    <button onclick="toggleInfo()"
                        class="cursor-pointer flex items-center justify-center gap-1 text-emerald-600 hover:text-emerald-700 text-sm font-medium focus:outline-none">

                        <!-- SVG baru -->
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" class="w-4 h-4 fill-current">
                            <g transform="translate(42.666667, 42.666667)">
                                <path
                                    d="M213.333333,0 C95.51296,0 0,95.51168 0,213.333333 C0,331.153707 95.51296,426.666667 213.333333,426.666667 C331.154987,426.666667 426.666667,331.153707 426.666667,213.333333 C426.666667,95.51168 331.154987,0 213.333333,0 Z M213.333333,384 C119.227947,384 42.6666667,307.43872 42.6666667,213.333333 C42.6666667,119.227947 119.227947,42.6666667 213.333333,42.6666667 C307.44,42.6666667 384,119.227947 384,213.333333 C384,307.43872 307.44,384 213.333333,384 Z M240.04672,128 C240.04672,143.46752 228.785067,154.666667 213.55008,154.666667 C197.698773,154.666667 186.713387,143.46752 186.713387,127.704107 C186.713387,112.5536 197.99616,101.333333 213.55008,101.333333 C228.785067,101.333333 240.04672,112.5536 240.04672,128 Z M192.04672,192 L234.713387,192 L234.713387,320 L192.04672,320 L192.04672,192 Z">
                                </path>
                            </g>
                        </svg>

                        Klik di sini untuk keterangan lebih lanjut
                    </button>


                    <!-- Pesan tambahan (disembunyikan dulu) -->
                    <div id="info-text"
                        class="hidden mt-4 text-sm text-gray-700 dark:text-gray-200 bg-gray-50 dark:bg-gray-700 p-3 rounded-lg">
                        <p class="mb-2 font-medium text-gray-800 dark:text-white">Masa PKL/Magang kamu telah
                            mencapai
                            batas.</p>
                        <p class="text-gray-600 dark:text-gray-300">
                            Dari tanggal <b>{{ $tanggal_masuk }}</b> sampai <b>{{ $tanggal_keluar }}</b>.
                        </p>
                        <hr class="my-2 border-gray-300 dark:border-gray-600">
                        <p class="text-gray-600 dark:text-gray-300 leading-relaxed">
                            Jika kamu ingin memperpanjang masa PKL/Magang, maka konfirmasi dengan admin secara
                            langsung,
                            sesuai dengan fungsi yang kamu tempati.
                        </p>
                    </div>

                    <button onclick="closePopup()"
                        class="cursor-pointer mt-5 bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-lg shadow text-sm font-medium transition">
                        Tutup
                    </button>
                </div>
            </div>
        </div>

        <script>
            function toggleInfo() {
                const info = document.getElementById('info-text');
                info.classList.toggle('hidden');
            }

            function closePopup() {
                const popup = document.getElementById('alumni-popup');
                popup.classList.add('opacity-0', 'transition', 'duration-500');
                setTimeout(() => popup.remove(), 500);

                fetch('/hide-alumni-popup', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                });
            }

            document.addEventListener("DOMContentLoaded", () => {
                const popup = document.getElementById('alumni-popup');
                popup.classList.add('opacity-100', 'transition', 'duration-500');
            });
        </script>
    @endif


    <div
        class="flex flex-col justify-start gap-x-15 mx-7 max-h-107 mt-6 @if (!$isAdmin) sm:mt-2  @else sm:mt-13 @endif">
        @if (!$isAdmin)
            <div class="mb-3 w-full mt-5.5 flex flex-col sm:flex-row gap-3">
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
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
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
                    <p class="text-center font-semibold border-b border-gray-200 pb-3.5">Statistik Kehadiran Anda
                    </p>
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
                                const baseLabels = ['Hadir', 'Sakit', 'Izin', 'Absen'];
                                const baseColors = ['#10B981', '#EAB308', '#3B82F6', '#DC2626'];
                                const rawData = @json($statusCounts);

                                const dataMap = {};
                                rawData.forEach(item => {
                                    dataMap[item.nama] = item.user_count;
                                });

                                const series = baseLabels.map(label => dataMap[label] || 0);

                                const chartOptions = {
                                    chart: {
                                        type: 'pie',
                                        height: 350,
                                        events: {
                                            dataPointSelection: function(event, chartContext, config) {
                                                // Ketika sebuah segment diklik, bisa kita highlight
                                                console.log('Segment clicked:', config.w.config.labels[config.dataPointIndex]);
                                            }
                                        }
                                    },
                                    series: series,
                                    labels: baseLabels,
                                    colors: baseColors,
                                    legend: {
                                        show: true,
                                        position: 'bottom'
                                    },
                                    dataLabels: {
                                        enabled: true,
                                        formatter: (val) => `${val.toFixed(1)}%`
                                    }
                                };

                                const chart = new ApexCharts(document.querySelector("#pie-chart"), chartOptions);
                                chart.render();

                                // Reset highlight saat klik di area kosong
                                document.querySelector("#pie-chart").addEventListener('click', function(e) {
                                    const elem = e.target;
                                    if (!elem.closest('.apexcharts-pie-series')) {
                                        chart.updateOptions({
                                            colors: baseColors // reset warna ke original
                                        });
                                    }
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
                                            '7' => '1 minggu terakhir',
                                            '30' => '1 bulan terakhir',
                                            '60' => '2 bulan terakhir',
                                            'all' => 'Sepanjang waktu',
                                        ];
                                        // pakai defaultRange yang sudah dihitung di controller
                                        $currentRange = $defaultRange;
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
                                            1 minggu terakhir
                                        </a>
                                    </li>
                                    <li>
                                        <a href="?range=30"
                                            class="block px-4 py-2 hover:bg-gray-200 dark:hover:bg-gray-600 dark:hover:text-white">
                                            1 bulan terakhir
                                        </a>
                                    </li>
                                    <li>
                                        <a href="?range=60"
                                            class="block px-4 py-2 hover:bg-gray-200 dark:hover:bg-gray-600 dark:hover:text-white">
                                            2 bulan terakhir
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

            <div
                class="rounded-lg py-3 sm:mt-0 sm:mb-[7px] mt-3 mb-23.5 border w-full border-gray-200 flex flex-col min-h-[600px]">
                <div class="flex flex-row items-center justify-between border-b border-gray-200 pb-3 px-4">
                    <!-- Tengah (Judul) -->
                    @if (!$isAdmin)
                        <div class="text-center font-semibold text-base">Tabel Data Kehadiran Anda</div>
                    @else
                        <div class="text-center font-semibold text-base">Tabel Data Kehadiran User</div>
                    @endif

                    <!-- Kanan (Tombol Filter) -->
                    <div class="flex items-center gap-2 relative">

                        @if (!$isAdmin)
                            <!-- Tombol Dropdown menu unduh -->
                            <div class="relative inline-block text-left">
                                <button id="menuDropdownButton" type="button"
                                    class="flex items-center justify-center py-2 px-4 text-sm font-medium text-white bg-blue-600 hover:bg-blue-800 focus:outline-none rounded-lg focus:z-10 focus:ring-4 focus:ring-blue-300 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-white dark:hover:bg-gray-700 transition-all duration-200">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-white"
                                        fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M12 4v12m0 0l-4-4m4 4l4-4M4 20h16" />
                                    </svg>
                                    Menu Unduh
                                    <svg class="-mr-1 ml-1.5 w-5 h-5" fill="currentColor" viewBox="0 0 20 20"
                                        xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" />
                                    </svg>
                                </button>

                                <!-- Isi Dropdown -->
                                <div id="menuDropdown"
                                    class="hidden absolute top-12 right-0 w-48 bg-white shadow shadow-gray-200 divide-y divide-gray-100 rounded-lg shadow-lg dark:bg-gray-700 dark:shadow-gray-600">
                                    <div class="py-1">
                                        <a href="{{ route('absensi.exportPdf', Auth::user()->slug) }}"
                                            class="flex items-center px-5 py-3 text-base text-gray-700 hover:bg-red-50 hover:text-red-600 dark:text-gray-200 dark:hover:bg-gray-600 transition-all duration-200">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="-4 0 40 40"
                                                fill="none" class="w-5 h-5 mr-2 text-red-600" aria-hidden="true">
                                                <path
                                                    d="M25.6686 26.0962C25.1812 26.2401 24.4656 26.2563 23.6984 26.145C22.875 26.0256 22.0351 25.7739 21.2096 25.403C22.6817 25.1888 23.8237 25.2548 24.8005 25.6009C25.0319 25.6829 25.412 25.9021 25.6686 26.0962ZM17.4552 24.7459C17.3953 24.7622 17.3363 24.7776 17.2776 24.7939C16.8815 24.9017 16.4961 25.0069 16.1247 25.1005L15.6239 25.2275C14.6165 25.4824 13.5865 25.7428 12.5692 26.0529C12.9558 25.1206 13.315 24.178 13.6667 23.2564C13.9271 22.5742 14.193 21.8773 14.468 21.1894C14.6075 21.4198 14.7531 21.6503 14.9046 21.8814C15.5948 22.9326 16.4624 23.9045 17.4552 24.7459ZM14.8927 14.2326C14.958 15.383 14.7098 16.4897 14.3457 17.5514C13.8972 16.2386 13.6882 14.7889 14.2489 13.6185C14.3927 13.3185 14.5105 13.1581 14.5869 13.0744C14.7049 13.2566 14.8601 13.6642 14.8927 14.2326ZM9.63347 28.8054C9.38148 29.2562 9.12426 29.6782 8.86063 30.0767C8.22442 31.0355 7.18393 32.0621 6.64941 32.0621C6.59681 32.0621 6.53316 32.0536 6.44015 31.9554C6.38028 31.8926 6.37069 31.8476 6.37359 31.7862C6.39161 31.4337 6.85867 30.8059 7.53527 30.2238C8.14939 29.6957 8.84352 29.2262 9.63347 28.8054ZM27.3706 26.1461C27.2889 24.9719 25.3123 24.2186 25.2928 24.2116C24.5287 23.9407 23.6986 23.8091 22.7552 23.8091C21.7453 23.8091 20.6565 23.9552 19.2582 24.2819C18.014 23.3999 16.9392 22.2957 16.1362 21.0733C15.7816 20.5332 15.4628 19.9941 15.1849 19.4675C15.8633 17.8454 16.4742 16.1013 16.3632 14.1479C16.2737 12.5816 15.5674 11.5295 14.6069 11.5295C13.948 11.5295 13.3807 12.0175 12.9194 12.9813C12.0965 14.6987 12.3128 16.8962 13.562 19.5184C13.1121 20.5751 12.6941 21.6706 12.2895 22.7311C11.7861 24.0498 11.2674 25.4103 10.6828 26.7045C9.04334 27.3532 7.69648 28.1399 6.57402 29.1057C5.8387 29.7373 4.95223 30.7028 4.90163 31.7107C4.87693 32.1854 5.03969 32.6207 5.37044 32.9695C5.72183 33.3398 6.16329 33.5348 6.6487 33.5354C8.25189 33.5354 9.79489 31.3327 10.0876 30.8909C10.6767 30.0029 11.2281 29.0124 11.7684 27.8699C13.1292 27.3781 14.5794 27.011 15.985 26.6562L16.4884 26.5283C16.8668 26.4321 17.2601 26.3257 17.6635 26.2153C18.0904 26.0999 18.5296 25.9802 18.976 25.8665C20.4193 26.7844 21.9714 27.3831 23.4851 27.6028C24.7601 27.7883 25.8924 27.6807 26.6589 27.2811C27.3486 26.9219 27.3866 26.3676 27.3706 26.1461ZM30.4755 36.2428C30.4755 38.3932 28.5802 38.5258 28.1978 38.5301H3.74486C1.60224 38.5301 1.47322 36.6218 1.46913 36.2428L1.46884 3.75642C1.46884 1.6039 3.36763 1.4734 3.74457 1.46908H20.263L20.2718 1.4778V7.92396C20.2718 9.21763 21.0539 11.6669 24.0158 11.6669H30.4203L30.4753 11.7218L30.4755 36.2428ZM28.9572 10.1976H24.0169C21.8749 10.1976 21.7453 8.29969 21.7424 7.92417V2.95307L28.9572 10.1976ZM31.9447 36.2428V11.1157L21.7424 0.871022V0.823357H21.6936L20.8742 0H3.74491C2.44954 0 0 0.785336 0 3.75711V36.2435C0 37.5427 0.782956 40 3.74491 40H28.2001C29.4952 39.9997 31.9447 39.2143 31.9447 36.2428Z"
                                                    fill="currentColor" />
                                            </svg>
                                            Download PDF
                                        </a>

                                        <a href="{{ route('absensi.exportExcel', Auth::user()->slug) }}"
                                            class="flex items-center px-5 py-3 text-base text-gray-700 hover:bg-emerald-50 hover:text-emerald-600 dark:text-gray-200 dark:hover:bg-gray-600 transition-all duration-200">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"
                                                class="w-5 h-5 mr-2 text-emerald-600" fill="currentColor"
                                                aria-hidden="true">
                                                <g>
                                                    <g>
                                                        <g>
                                                            <path
                                                                d="M447.168,134.56c-0.535-1.288-1.318-2.459-2.304-3.445l-128-128c-2.003-1.988-4.709-3.107-7.531-3.115H74.667
                                                            C68.776,0,64,4.776,64,10.667v490.667C64,507.224,68.776,512,74.667,512h362.667c5.891,0,10.667-4.776,10.667-10.667V138.667
                                                            C447.997,137.256,447.714,135.86,447.168,134.56z M320,36.416L411.584,128H320V36.416z M426.667,490.667H85.333V21.333h213.333
                                                            v117.333c0,5.891,4.776,10.667,10.667,10.667h117.333V490.667z" />
                                                            <path d="M128,181.333v256c0,5.891,4.776,10.667,10.667,10.667h234.667c5.891,0,10.667-4.776,10.667-10.667v-256
                                                            c0-5.891-4.776-10.667-10.667-10.667H138.667C132.776,170.667,128,175.442,128,181.333z M320,192h42.667v42.667H320V192z
                                                            M320,256h42.667v42.667H320V256z M320,320h42.667v42.667H320V320z M320,384h42.667v42.667H320V384z M213.333,192h85.333v42.667
                                                            h-85.333V192z M213.333,256h85.333v42.667h-85.333V256z M213.333,320h85.333v42.667h-85.333V320z M213.333,384h85.333v42.667
                                                            h-85.333V384z M149.333,192H192v42.667h-42.667V192z M149.333,256H192v42.667h-42.667V256z M149.333,320H192v42.667h-42.667V320z
                                                            M149.333,384H192v42.667h-42.667V384z" />
                                                        </g>
                                                    </g>
                                                </g>
                                            </svg>
                                            Download Excel
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div class="relative inline-block">
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

                            <!-- Badge -->
                            <span id="filterBadge"
                                class="absolute -top-1 -right-1 inline-flex items-center justify-center min-w-[20px] h-[20px] text-[11px] font-bold leading-none text-white bg-red-600 rounded-full px-[5px] py-[1px] hidden">
                            </span>
                        </div>

                        <div
                            class="w-full md:w-auto flex flex-col md:flex-row space-y-2 md:space-y-0 items-stretch md:items-center justify-end md:space-x-3 flex-shrink-0">

                            <div id="filterDropdown"
                                class="absolute z-10 hidden px-3 pt-1 bg-white rounded-lg shadow w-80 dark:bg-gray-700 right-0 top-12 max-h-72 overflow-y-auto">
                                <div class="flex items-center justify-between pt-2 pb-2 border-b border-gray-200">

                                    <h6 class="text-sm font-medium text-black dark:text-white">Filters</h6>
                                    <div class="flex items-center space-x-3">
                                        <button type="button"
                                            class="text-sm font-medium text-emerald-600 dark:text-emerald-500 hover:underline cursor-pointer"
                                            onclick="applyFilter()">Terapkan</button>
                                        <button type="button"
                                            class="text-sm font-medium text-emerald-600 dark:text-emerald-500 hover:underline cursor-pointer"
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

                                    @if ($isAdmin)
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
                                                @php
                                                    $selectedFungsis = (array) request()->input('fungsi');
                                                @endphp

                                                @foreach ($fungsis as $fungsi)
                                                    <ul class="space-y-2 mb-2">
                                                        <li class="flex items-center">
                                                            <input id="fungsi-{{ Str::slug($fungsi->nama) }}"
                                                                type="checkbox" name="fungsi[]"
                                                                value="{{ $fungsi->nama }}"
                                                                {{ in_array($fungsi->nama, $selectedFungsis) ? 'checked' : '' }}
                                                                class="w-4 h-4 bg-gray-100 border-gray-300 rounded text-emerald-600 focus:ring-emerald-500 dark:focus:ring-emerald-600 dark:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500">
                                                            <label for="fungsi-{{ Str::slug($fungsi->nama) }}"
                                                                class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-100">{{ $fungsi->nama }}</label>
                                                        </li>
                                                    </ul>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex-grow overflow-y-auto overflow-x-auto max-h-[500px] ">
                    {{-- Tabel Kehadiran --}}
                    <div class="w-full overflow-x-auto">
                        <table class="min-w-full text-sm text-left text-gray-500">
                            <thead class="bg-gray-100 text-xs text-gray-700 uppercase">
                                <tr>
                                    @if (!$isAdmin)
                                        <th class="p-4 whitespace-nowrap">ID</th>
                                        <th class="p-4 whitespace-nowrap">Tanggal</th>
                                        <th class="p-4 whitespace-nowrap">Jam Masuk</th>
                                        <th class="p-4 whitespace-nowrap">Jam Keluar</th>
                                        <th class="p-4 whitespace-nowrap">Status</th>
                                        <th class="p-4 whitespace-nowrap">Keterangan</th>
                                    @else
                                        <th class="p-4 whitespace-nowrap">ID</th>
                                        <th class="p-4 whitespace-nowrap">Nama</th>
                                        <th class="p-4 whitespace-nowrap">NIM/NISN</th>
                                        <th class="p-4 whitespace-nowrap">Fungsi</th>
                                        <th class="p-4 whitespace-nowrap">Status</th>
                                        <th class="p-4 whitespace-nowrap"></th>
                                        <th class="p-4 text-center whitespace-nowrap">Aksi</th>
                                    @endif
                                </tr>
                            </thead>

                            <tbody class="divide-y divide-gray-200">
                                @if ($isAdmin)
                                    @forelse ($processedUsers as $index => $item)
                                        <tr class="border-b">
                                            <td class="px-4 py-3 whitespace-nowrap text-center text-black">
                                                {{ $loop->iteration + $processedUsers->firstItem() - 1 }}</td>
                                            <td class="px-4 py-3 whitespace-nowrap">
                                                <div class="flex items-center gap-3 text-black">
                                                    <img class="w-8 h-8 rounded-full"
                                                        src="{{ $item['user']->foto ? asset('storage/' . $item['user']->foto) : asset('img/Anonymous.png') }}"
                                                        alt="{{ $item['user']->name }}">
                                                    {{ $item['user']->name }}
                                                </div>
                                            </td>
                                            <td class="pl-6 py-3 whitespace-nowrap text-black">
                                                {{ $item['user']->nim ?? '-' }}
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap">
                                                <a href="/fungsi?fungsi={{ $item['user']->fungsi->slug ?? '' }}"
                                                    class="{{ $item['user']->fungsi->warna ?? 'bg-gray-100' }} font-medium px-2 py-0.5 rounded hover:underline">
                                                    {{ $item['user']->fungsi->nama ?? 'Umum' }}
                                                </a>
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div
                                                        class="h-4 w-4 rounded-full inline-block mr-2 {{ $item['status_color'] }}">
                                                    </div>
                                                    {{ $item['status'] }}
                                                </div>
                                            </td>
                                            <td class="py-3 whitespace-nowrap text-black">
                                                {{ $item['count'] }}x
                                            </td>
                                            <td class="py-3 whitespace-nowrap text-center">
                                                <x-dropdown-action :user="$item['user']" :rowId="$loop->iteration" />
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center text-gray-500 py-50">
                                                Belum ada pengguna yang absen...
                                            </td>
                                        </tr>
                                    @endforelse
                                @else
                                    @forelse ($absensisPaginated as $absensi)
                                        <tr>
                                            <td class="px-4 py-3 whitespace-nowrap text-black">
                                                {{ $loop->iteration + $absensisPaginated->firstItem() - 1 }}</td>
                                            <td class="px-4 py-3 whitespace-nowrap text-black">
                                                {{ $absensi->tanggal ?? '-' }}</td>
                                            <td class="px-4 py-3 whitespace-nowrap text-black">
                                                {{ $absensi->jam_masuk ?? '-' }}</td>
                                            <td class="px-4 py-3 whitespace-nowrap text-black">
                                                {{ $absensi->jam_keluar ?? '-' }}</td>
                                            <td class="px-4 py-3 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div
                                                        class="h-4 w-4 rounded-full inline-block mr-2 {{ $absensi->status->warna ?? 'bg-gray-300' }}">
                                                    </div>
                                                    {{ $absensi->status->nama ?? '-' }}
                                                </div>
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap text-black">
                                                @if (!empty($absensi->judul))
                                                    <a href="/keterangan/{{ $absensi->slug }}"
                                                        class="hover:underline">
                                                        {{ Str::limit($absensi->judul, 10) }}
                                                    </a>
                                                @else
                                                    <span class="text-gray-500 italic">-</span>
                                                @endif
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
                    </div>
                    <script>
                        // Kirim info role ke JS agar bisa digunakan saat rendering
                        window.isAdmin = @json($isAdmin);
                    </script>
                </div>

                {{-- Pagination --}}
                @if ($isAdmin && $processedUsers->hasPages())
                    <div class="border-t pt-3.5 border-gray-200">
                        {{ $processedUsers->onEachSide(0)->links('pagination::tailwind') }}
                    </div>
                @elseif (!$isAdmin && $absensisPaginated->hasPages())
                    <div class="border-t pt-3.5 border-gray-200">
                        {{ $absensisPaginated->onEachSide(0)->links('pagination::tailwind') }}
                    </div>
                @endif

            </div>
        </div>
    </div>

    @push('script')
        <script>
            // === Fungsi Reset & Apply Filter ===
            function resetFilter() {
                window.location.href = window.location.pathname;
            }

            function applyFilter() {
                const checkedStatuses = Array.from(document.querySelectorAll('#kehadiran-body input[type="checkbox"]:checked'))
                    .map(cb => cb.value);
                const checkedFunctions = Array.from(document.querySelectorAll('#fungsi-body input[type="checkbox"]:checked'))
                    .map(cb => cb.value);

                if (checkedStatuses.length === 0 && checkedFunctions.length === 0) {
                    window.location.href = window.location.pathname;
                    return;
                }

                const params = new URLSearchParams();
                checkedStatuses.forEach(status => params.append('status[]', status));
                checkedFunctions.forEach(fungsi => params.append('fungsi[]', fungsi));
                params.set('page', 1);

                const dropdown = document.getElementById('filterDropdown');
                if (dropdown) dropdown.classList.add('hidden');

                const url = `${window.location.pathname}?${params.toString()}`;
                window.location.href = url;
            }

            // === Toggle Dropdown Filter ===
            document.addEventListener('DOMContentLoaded', function() {
                const filterButton = document.getElementById('filterDropdownButton');
                const filterDropdown = document.getElementById('filterDropdown');
                const badge = document.getElementById('filterBadge');
                const checkboxes = document.querySelectorAll('#filterDropdown input[type="checkbox"]');

                if (filterButton && filterDropdown) {
                    filterButton.addEventListener('click', () => {
                        const menuDropdown = document.getElementById('menuDropdown');
                        if (menuDropdown) menuDropdown.classList.add('hidden');

                        filterDropdown.classList.toggle('hidden');
                    });

                    document.addEventListener('click', function(event) {
                        if (!filterButton.contains(event.target) && !filterDropdown.contains(event.target)) {
                            filterDropdown.classList.add('hidden');
                        }
                    });
                }

                // === Update Filter Badge ===
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

                // === Dropdown Menu (Download PDF) ===
                const menuButton = document.getElementById('menuDropdownButton');
                const menuDropdown = document.getElementById('menuDropdown');

                if (menuButton && menuDropdown) {
                    menuButton.addEventListener('click', (e) => {
                        e.stopPropagation(); // cegah trigger penutupan dari listener global

                        // Tutup dropdown filter saat menu unduh dibuka
                        const filterDropdown = document.getElementById('filterDropdown');
                        if (filterDropdown) filterDropdown.classList.add('hidden');

                        menuDropdown.classList.toggle('hidden');
                        menuDropdown.classList.toggle('animate-fade-in');
                    });

                    // Tutup dropdown jika klik di luar
                    window.addEventListener('click', (e) => {
                        if (!menuButton.contains(e.target) && !menuDropdown.contains(e.target)) {
                            menuDropdown.classList.add('hidden');
                        }
                    });
                }
            });
        </script>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/datepicker.min.js"></script>
    @endpush

</x-layout>
