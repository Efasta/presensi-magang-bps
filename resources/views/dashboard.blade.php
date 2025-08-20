<x-layout :title="$title">
    {{-- {{ \Carbon\Carbon::parse($card->tanggal_masuk)->format('d-m-Y') }} --}}
    <div class="flex flex-col justify-start gap-x-15 mx-7 max-h-107 mt-3">
        <div class="flex flex-col sm:flex-row gap-x-10">
            <div class="rounded-lg py-3 mb-1.5 border sm:w-3xl border-gray-200 whitespace-nowrap">
                <p class="text-center font-semibold border-b border-gray-200 pb-3.5">Total Users : {{ count($absensis) }}
                </p>

                <div class="max-w-3xl bg-white rounded-lg dark:bg-gray-800 p-4 md:p-6">

                    <div class="flex justify-between items-start w-full">
                        <div class="flex-col items-center">
                            <button id="dateRangeButton" data-dropdown-toggle="dateRangeDropdown"
                                data-dropdown-ignore-click-outside-class="datepicker" type="button"
                                class="inline-flex items-center text-blue-700 dark:text-blue-600 font-medium hover:underline">31-Juli-2025<svg
                                    class="w-3 h-3 ms-  2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 10 6">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" d="m1 1 4 4 4-4" />
                                </svg>
                            </button>
                            <div id="dateRangeDropdown"
                                class="z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow-sm w-80 lg:w-96 dark:bg-gray-700 dark:divide-gray-600">
                                <div class="p-3" aria-labelledby="dateRangeButton">
                                    <div date-rangepicker datepicker-autohide class="flex items-center">
                                        <div class="relative">
                                            <div
                                                class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                                                <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                                                    xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                                                    viewBox="0 0 20 20">
                                                    <path
                                                        d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z" />
                                                </svg>
                                            </div>
                                            <input name="start" type="text"
                                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                                placeholder="Start date">
                                        </div>
                                        <span class="mx-2 text-gray-500 dark:text-gray-400">to</span>
                                        <div class="relative">
                                            <div
                                                class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                                                <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                                                    xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                                                    viewBox="0 0 20 20">
                                                    <path
                                                        d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z" />
                                                </svg>
                                            </div>
                                            <input name="end" type="text"
                                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                                placeholder="End date">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Line Chart -->
                    <div class="py-1 pb-12" id="pie-chart">
                        <script>
                            document.addEventListener("DOMContentLoaded", function() {
                                const labels = @json($statusCounts->pluck('nama'));
                                const series = @json($statusCounts->pluck('user_count'));

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
                                        formatter: function(val) {
                                            return `${val.toFixed(1)}%`;
                                        }
                                    }
                                };

                                const chart = new ApexCharts(document.querySelector("#pie-chart"), chartOptions);
                                chart.render();
                            });
                        </script>
                    </div>

                    <div
                        class="grid grid-cols-1 items-center border-gray-200 border-t dark:border-gray-700 justify-between">
                        <div class="flex justify-between items-center pt-5">
                            <!-- Button -->
                            <button id="dropdownDefaultButton" data-dropdown-toggle="lastDaysdropdown"
                                data-dropdown-placement="bottom"
                                class="text-sm font-medium text-gray-500 dark:text-gray-400 hover:text-gray-900 text-center inline-flex items-center dark:hover:text-white"
                                type="button">
                                Last 7 days
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
                                        <a href="#"
                                            class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Yesterday</a>
                                    </li>
                                    <li>
                                        <a href="#"
                                            class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Today</a>
                                    </li>
                                    <li>
                                        <a href="#"
                                            class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Last
                                            7 days</a>
                                    </li>
                                    <li>
                                        <a href="#"
                                            class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Last
                                            30 days</a>
                                    </li>
                                    <li>
                                        <a href="#"
                                            class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Last
                                            90 days</a>
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
                    <div class="text-center font-semibold text-base">Tabel Data</div>

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
                                                            type="checkbox" value="{{ $status->nama }}"
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
                                                            type="checkbox" value="{{ $fungsi->nama }}"
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

                <div class="flex-grow overflow-y-auto max-h-[470px]">
                    {{-- Tabel Kehadiran --}}
                    <table class="min-w-full text-sm text-left text-gray-500">
                        <thead class="bg-gray-100 text-xs text-gray-700 uppercase">
                            <tr>
                                <th class="px-6 py-3">ID</th>
                                <th class="px-6 py-3">Nama</th>
                                <th class="px-6 py-3">NIM/NISN</th>
                                <th class="px-6 py-3">Fungsi</th>
                                <th scope="col" class="p-4">Status</th>
                                <th class="px-6 py-3">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach ($users as $user)
                                <tr data-status="{{ strtolower($user->absensi->status->nama ?? '') }}"
                                    data-fungsi="{{ strtolower($user->fungsi->nama ?? '') }}">
                                    <td
                                        class="px-6
                                    py-4 font-medium text-gray-900">
                                        {{ $loop->iteration }}</td>
                                    <th scope="row"
                                        class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                        <div class="flex items-center mr-3 gap-3">
                                            <img class="w-8 h-8 rounded-full" src="{{ $user->foto ? asset('storage/' . $user->foto) : asset('img/Anonymous.png') }}"
                                                alt="{{ $user->name }}" class="h-8 w-auto mr-3">
                                            <a class="hover:underline" href="/users/{{ $user->slug }}">
                                                {{ $user->name }}
                                            </a>
                                        </div>
                                    </th>
                                    <td class="px-6 py-4 font-medium text-gray-900">{{ $user->nim }}</td>
                                    <td class="px-6 py-4">
                                        <a href="/fungsi?fungsi={{ $user->fungsi->slug }}"
                                            class="{{ $user->fungsi->warna }} font-medium px-2 py-0.5 rounded hover:underline">
                                            {{ $user->fungsi->nama }}
                                        </a>
                                    </td>
                                    <td class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                        <div class="flex items-center">
                                            <div
                                                class="h-4 w-4 rounded-full inline-block mr-2 {{ $user->absensi->status->warna }}">
                                            </div>
                                            {{ $user->absensi->status->nama }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        @php
                                            $isAdmin = Auth::user()->is_admin;
                                            $isOwner = Auth::user()->id === $user->id;
                                        @endphp

                                        @if ($isAdmin || $isOwner)
                                            <a href="/users/{{ $user->slug }}"
                                                class="inline-flex items-center text-blue-600 hover:text-blue-800 hover:underline gap-1.5">
                                                <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg"
                                                    viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                    <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                                        d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" />
                                                </svg>
                                                <span class="text-sm font-medium">Lihat</span>
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @if ($users->hasPages())
                    <div class="border-t pt-3.5 border-gray-200">
                        {{ $users->onEachSide(0)->links('pagination::tailwind') }}
                    </div>
                @endif
            </div>
        </div>
        <!-- Start block -->
        <section class="pt-4 pb-20 antialiased">
            <div class="mx-auto">
                <div class="bg-white relative border border-gray-200 sm:rounded-lg overflow-hidden">
                    <div class="flex items-center justify-between p-5 border-b border-gray-200">
                        <p class="text-xl font-semibold text-gray-900 dark:text-white">Aktivitas Terkini</p>
                    </div>

                    <!-- Container marquee -->
                    <div class="overflow-hidden">
                        <div id="marquee" class="flex gap-6 pt-4 pb-4 will-change-transform">
                            <!-- Card cards akan di-inject dari JS -->
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <style>
            #marquee {
                animation: marquee 10s linear infinite;
            }

            #marquee {
                animation: marquee 10s linear infinite;
                will-change: transform;
            }

            #marquee:hover {
                animation-play-state: paused;
            }

            @keyframes marquee {
                0% {
                    transform: translateX(0%);
                }

                100% {
                    transform: translateX(-50%);
                }
            }

            .testimonial-card {
                min-width: 250px;
                max-width: 300px;
                flex-shrink: 0;
                border-radius: 12px;
            }

            .testimonial-card:hover {
                border-color: #065f46;
                /* transition: border-color 0.3s ease; */
            }
        </style>

        <script>
            const testimonials = [
                @foreach ($users as $user)
                    {
                        img: "{{ $user->foto ? asset('storage/' . $user->foto) : asset('img/Anonymous.png') }}",
                        slug: "/users/{{ $user->slug }}",
                        name: "{{ $user->name }}",
                        text: "Lorem ipsum dolor, sit amet consectetur adipisicing elit. Quaerat vitae deleniti est repudiandae! Nulla cupiditate fugit atque! Nulla ducimus"
                    },
                @endforeach
            ];

            const marquee = document.getElementById('marquee');

            // Buat card HTML untuk setiap testimonial ${testimonial.name}
            function createCard(testimonial) {
                const div = document.createElement('div');
                div.className =
                    'testimonial-card bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md border border-gray-200';
                div.innerHTML = `
                <div class="flex items-center">
                    <img class="w-8 h-8 rounded-full mb-2" src="${testimonial.img}"
                        alt="${testimonial.name}" class="h-8 w-auto mr-3">
                        <a class="pl-2 text-green-700 dark:text-green-500 font-semibold mb-2 hover:underline" href="${testimonial.slug}">
                        ${testimonial.name}
                        </a>
                </div>
      <p class="text-gray-800 dark:text-gray-30 text-sm leading-relaxed">${testimonial.text}</p>
    `;
                return div;
            }

            // Render dua kali supaya loop animasi seamless
            testimonials.forEach(t => marquee.appendChild(createCard(t)));
            testimonials.forEach(t => marquee.appendChild(createCard(t)));
        </script>



    </div>
    </div>
    </section>
    <!-- End block -->

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
                        fill="none" stroke="currentColor" viewbox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
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
    </div>
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

        // Buat URL baru dengan parameter
        const params = new URLSearchParams();

        checkedStatuses.forEach(status => params.append('status[]', status));
        checkedFunctions.forEach(fungsi => params.append('fungsi[]', fungsi));

        const url = `${window.location.pathname}?${params.toString()}`;
        window.location.href = url;
    }

    // Event listener untuk tombol filter buka/tutup dropdown
    document.getElementById('filterDropdownButton').addEventListener('click', () => {
        const dd = document.getElementById('filterDropdown');
        dd.classList.toggle('hidden');
    });
</script>
