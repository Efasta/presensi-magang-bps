<x-layout :title="$title">
    <div class=" mx-5 mb-23 sm:mb-0 my-9">
        <div class="rounded-lg py-3 mb-4 border w-full border-gray-200 flex flex-col min-h-[700px]">
            <div class="flex flex-row items-center justify-between border-b border-gray-200 pb-3 px-4">
                <div class="text-center font-semibold text-base">Detail Absensi {{ $user->name }}</div>
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
                    <a href="/users"
                        class="inline-flex items-center text-blue-600 hover:text-blue-800 hover:underline text-sm font-medium">
                        ← Kembali Ke Users
                    </a>
                </div>
                <div>
                    {{ $absensis->links('components.pagination.custom-detail') }}
                </div>
            </div>
        </div>
    </div>
</x-layout>
