<x-layout :title="$title">

    <!-- Chart.js & Flowbite CDN -->
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script src="https://unpkg.com/flowbite@1.6.5/dist/flowbite.min.js"></script>

    <main class="pt-3 pb-17.5 lg:pb-15">
        <div class=" px-4 2xl:px-0 mx-auto max-w-screen-2xl">
            <article class="mx-auto w-full">

                <!-- Tabel & Chart Container -->
                <div class="flex flex-col lg:flex-row bg-white dark:bg-gray-800 p-6 rounded-lg border-1 border-gray-200">

                    <div class="w-full md:w-1/2">
                        <div class="flex justify-between items-center mb-4">
                            <h2 id="tabelKehadiranTitle" class="text-xl font-semibold text-gray-800 dark:text-white">Tabel
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
                            class="absolute sm:top-0 right-0 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-700 inline-flex items-center shadow-sm">
                            <span id="fungsiDropdownLabel">IPDS</span>
                            <svg class="w-3 h-3 ml-2" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 10 6">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="M1 1l4 4 4-4" />
                            </svg>
                        </button>

                        <!-- Judul di atas grafik, rata tengah -->
                        <h2 class="text-xl font-semibold mt-11 2xl:mt-1.5 mb-4 text-gray-800 dark:text-white text-center">
                            Grafik Kehadiran
                        </h2>

                        <!-- Grafik -->
                        <div id="pie-chart-canvas" class="w-full max-w-lg mx-auto"></div>
                    </div>

                </div>

            </article>
        </div>
        <!-- Start block -->
        <section class= "dark:bg-gray-900 py-3 sm:p-5 antialiased">
            <div class=" mx-auto max-w-screen-2xl px-4 sm:px-0">
                <div class="bg-white dark:bg-gray-800 relative border border-gray-200 rounded-lg overflow-hidden">
                    <div class="flex items-center justify-between p-5">
                        <p id="tabelAnggotaTitle" class="text-xl font-semibold text-gray-900 dark:text-white">Anggota Fungsi:</p>
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
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-emerald-500 focus:border-emerald-500 block w-full pl-10 p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-emerald-500 dark:focus:border-emerald-500" autocomplete="off" autofocus name="search">
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
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $user)
                                    <tr data-status="{{ strtolower($user->absensi->status->nama) }}"
                                        data-fungsi="{{ strtolower($user->fungsi->slug) }}"
                                        class="border-b border-gray-200 dark:border-gray-600 dark:hover:bg-gray-700">
                                        <td class="px-4 py-3 font-medium text-gray-900">
                                            {{ $loop->iteration }}</td>
                                        <th scope="row"
                                            class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                            <div class="flex items-center mr-3 gap-3">
                                                <img class="w-8 h-8 rounded-full" src="{{ $user->foto ? asset('storage/' . $user->foto) : asset('img/Anonymous.png') }}"
                                                    alt="iMac Front Image" class="h-8 w-auto mr-3">
                                                {{ $user->name }}
                                            </div>
                                        </th>
                                        <td class="px-6 py-4 font-medium text-gray-900">{{ $user->nim }}</td>
                                        <td class="px-4 py-3">
                                            <a href="/fungsi?fungsi={{ $user->fungsi->slug }}"
                                                class="{{ $user->fungsi->warna }} text-xs font-medium px-2 py-0.5 rounded dark:bg-emerald-900 dark:text-emerald-300 hover:underline">{{ $user->fungsi->nama }}</a>
                                        </td>
                                        <td class="px-4 py-3">{{ $user->jenis_kelamin }}</td>
                                        <td
                                            class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                            <div class="flex items-center">
                                                <div class="h-4 w-4 rounded-full inline-block mr-2 {{ $user->absensi->status->warna }}">
                                                </div>
                                                {{ $user->absensi->status->nama }}
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 flex items-center justify-self-end">
                                            <button id="users-{{ $user->card->id }}-dropdown-button"
                                                data-dropdown-toggle="users-{{ $user->card->id }}-dropdown"
                                                class="inline-flex items-center text-sm font-medium hover:bg-gray-100 dark:hover:bg-gray-700 p-1.5 dark:hover-bg-gray-800 text-center text-gray-500 hover:text-gray-800 rounded-lg focus:outline-none dark:text-gray-400 dark:hover:text-gray-100"
                                                type="button">
                                                <svg class="w-5 h-5" aria-hidden="true" fill="currentColor"
                                                    viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                    <path
                                                        d="M6 10a2 2 0 11-4 0 2 2 0 014 0zM12 10a2 2 0 11-4 0 2 2 0 014 0zM16 12a2 2 0 100-4 2 2 0 000 4z" />
                                                </svg>
                                            </button>
                                            <div id="users-{{ $user->card->id }}-dropdown"
                                                class="hidden z-10 w-44 bg-white rounded divide-y divide-gray-100 shadow dark:bg-gray-700 dark:divide-gray-600">
                                                <ul class="py-1 text-sm"
                                                    aria-labelledby="users-{{ $user->card->id }}-dropdown-button">
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
                        <h3 class="mb-5 text-lg font-normal text-gray-500 dark:text-gray-400">Are you sure
                            you want to
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
    </main>

    <!-- Script Chart.js & Table Update -->
    <script>
        const pieChartDataByfungsi = @json($chartData);
        const pieChartLabels = @json($statuses->pluck('nama'));
        const urlParams = new URLSearchParams(window.location.search);
        const initialFungsi = urlParams.get('fungsi') || Object.keys(pieChartDataByfungsi)[0];

        let chartInstance;

        function renderPieChart(data) {
            if (chartInstance) {
                chartInstance.destroy();
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
            chartInstance = new ApexCharts(document.querySelector("#pie-chart-canvas"), options);
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

            rows.forEach(row => {
                const rowFungsi = row.getAttribute('data-fungsi');
                const name = row.querySelector('th')?.innerText.toLowerCase() || '';

                const matchFungsi = rowFungsi === selectedFungsi.toLowerCase();
                const matchSearch = name.includes(searchValue);

                row.style.display = (matchFungsi && matchSearch) ? '' : 'none';
            });
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
        });
    </script>



</x-layout>
