<x-layout :title="$title">
    <div class=" mx-5 mb-23 sm:mb-23 mt-5.5">
        <div class="rounded-lg py-3 mb-4 border w-full border-gray-200 flex flex-col min-h-[700px]">
            <div class="flex flex-row items-center justify-between border-b border-gray-200 pb-3 px-4">
                <div class="text-center font-semibold text-base">Detail Absensi {{ $user->name }}</div>
                <!-- Dropdown Menu Unduh (sama seperti dashboard) -->
                <div class="relative inline-block text-left" x-data="{ openMenu: null, toggle(menu) { this.openMenu = this.openMenu === menu ? null : menu } }">
                    <button @click.stop="toggle('downloadDetail')" type="button"
                        class="flex items-center justify-center py-2 px-4 text-sm font-medium text-white bg-blue-600 hover:bg-blue-800 focus:outline-none rounded-lg focus:z-10 focus:ring-4 focus:ring-blue-300 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-white dark:hover:bg-gray-700 transition-all duration-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-white" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
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
                    <div x-show="openMenu === 'downloadDetail'" x-transition
                        class="absolute right-0 z-20 mt-2 w-48 origin-top-right bg-white divide-y divide-gray-100 rounded-lg shadow-lg dark:bg-gray-700">
                        <div class="py-1">
                            <a href="{{ route('absensi.exportPdf', $user->slug) }}"
                                class="flex items-center px-5 py-3 text-base text-gray-700 hover:bg-red-50 hover:text-red-600 dark:text-gray-200 dark:hover:bg-gray-600 transition-all duration-200">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="-4 0 40 40" fill="none"
                                    class="w-5 h-5 mr-2 text-red-600" aria-hidden="true">
                                    <path
                                        d="M25.6686 26.0962C25.1812 26.2401 24.4656 26.2563 23.6984 26.145C22.875 26.0256 22.0351 25.7739 21.2096 25.403C22.6817 25.1888 23.8237 25.2548 24.8005 25.6009C25.0319 25.6829 25.412 25.9021 25.6686 26.0962ZM17.4552 24.7459C17.3953 24.7622 17.3363 24.7776 17.2776 24.7939C16.8815 24.9017 16.4961 25.0069 16.1247 25.1005L15.6239 25.2275C14.6165 25.4824 13.5865 25.7428 12.5692 26.0529C12.9558 25.1206 13.315 24.178 13.6667 23.2564C13.9271 22.5742 14.193 21.8773 14.468 21.1894C14.6075 21.4198 14.7531 21.6503 14.9046 21.8814C15.5948 22.9326 16.4624 23.9045 17.4552 24.7459ZM14.8927 14.2326C14.958 15.383 14.7098 16.4897 14.3457 17.5514C13.8972 16.2386 13.6882 14.7889 14.2489 13.6185C14.3927 13.3185 14.5105 13.1581 14.5869 13.0744C14.7049 13.2566 14.8601 13.6642 14.8927 14.2326ZM9.63347 28.8054C9.38148 29.2562 9.12426 29.6782 8.86063 30.0767C8.22442 31.0355 7.18393 32.0621 6.64941 32.0621C6.59681 32.0621 6.53316 32.0536 6.44015 31.9554C6.38028 31.8926 6.37069 31.8476 6.37359 31.7862C6.39161 31.4337 6.85867 30.8059 7.53527 30.2238C8.14939 29.6957 8.84352 29.2262 9.63347 28.8054ZM27.3706 26.1461C27.2889 24.9719 25.3123 24.2186 25.2928 24.2116C24.5287 23.9407 23.6986 23.8091 22.7552 23.8091C21.7453 23.8091 20.6565 23.9552 19.2582 24.2819C18.014 23.3999 16.9392 22.2957 16.1362 21.0733C15.7816 20.5332 15.4628 19.9941 15.1849 19.4675C15.8633 17.8454 16.4742 16.1013 16.3632 14.1479C16.2737 12.5816 15.5674 11.5295 14.6069 11.5295C13.948 11.5295 13.3807 12.0175 12.9194 12.9813C12.0965 14.6987 12.3128 16.8962 13.562 19.5184C13.1121 20.5751 12.6941 21.6706 12.2895 22.7311C11.7861 24.0498 11.2674 25.4103 10.6828 26.7045C9.04334 27.3532 7.69648 28.1399 6.57402 29.1057C5.8387 29.7373 4.95223 30.7028 4.90163 31.7107C4.87693 32.1854 5.03969 32.6207 5.37044 32.9695C5.72183 33.3398 6.16329 33.5348 6.6487 33.5354C8.25189 33.5354 9.79489 31.3327 10.0876 30.8909C10.6767 30.0029 11.2281 29.0124 11.7684 27.8699C13.1292 27.3781 14.5794 27.011 15.985 26.6562L16.4884 26.5283C16.8668 26.4321 17.2601 26.3257 17.6635 26.2153C18.0904 26.0999 18.5296 25.9802 18.976 25.8665C20.4193 26.7844 21.9714 27.3831 23.4851 27.6028C24.7601 27.7883 25.8924 27.6807 26.6589 27.2811C27.3486 26.9219 27.3866 26.3676 27.3706 26.1461ZM30.4755 36.2428C30.4755 38.3932 28.5802 38.5258 28.1978 38.5301H3.74486C1.60224 38.5301 1.47322 36.6218 1.46913 36.2428L1.46884 3.75642C1.46884 1.6039 3.36763 1.4734 3.74457 1.46908H20.263L20.2718 1.4778V7.92396C20.2718 9.21763 21.0539 11.6669 24.0158 11.6669H30.4203L30.4753 11.7218L30.4755 36.2428ZM28.9572 10.1976H24.0169C21.8749 10.1976 21.7453 8.29969 21.7424 7.92417V2.95307L28.9572 10.1976ZM31.9447 36.2428V11.1157L21.7424 0.871022V0.823357H21.6936L20.8742 0H3.74491C2.44954 0 0 0.785336 0 3.75711V36.2435C0 37.5427 0.782956 40 3.74491 40H28.2001C29.4952 39.9997 31.9447 39.2143 31.9447 36.2428Z"
                                        fill="currentColor" />
                                </svg>
                                Download PDF
                            </a>

                            <a href="{{ route('absensi.exportExcel', $user->slug) }}"
                                class="flex items-center px-5 py-3 text-base text-gray-700 hover:bg-emerald-50 hover:text-emerald-600 dark:text-gray-200 dark:hover:bg-gray-600 transition-all duration-200">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"
                                    class="w-5 h-5 mr-2 text-emerald-600" fill="currentColor" aria-hidden="true">
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

            </div>

            <div class="flex-grow overflow-y-auto max-h-[600px]">
                <table class="min-w-full text-sm text-left text-gray-500">
                    <thead class="bg-gray-100 text-xs text-gray-700 uppercase">
                        <tr>
                            <th class="p-4">ID</th>
                            <th class="p-4">Tanggal</th>
                            <th class="p-4 whitespace-nowrap">Jam Masuk</th>
                            <th class="p-4 whitespace-nowrap">Jam Keluar</th>
                            <th class="p-4">Status</th>
                            <th class="p-4">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse ($absensis as $index => $absensi)
                            <tr>
                                <td class="px-4 py-3">{{ $index + 1 }}</td>
                                <td class="px-4 py-3 text-black whitespace-nowrap">{{ $absensi->tanggal ?? '-' }}</td>
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
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-gray-500 py-10">
                                    Tidak ada data absensi untuk ditampilkan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="flex justify-between items-center border-t pt-3.5 border-gray-200 px-4">
                <div>
                    <a href="{{ url()->previous() }}"
                        class="inline-flex items-center text-blue-600 hover:text-blue-800 hover:underline text-sm font-medium">
                        ← Kembali
                    </a>
                </div>
                <div>
                    {{ $absensis->links('components.pagination.custom-detail') }}
                </div>
            </div>
        </div>
    </div>
</x-layout>
