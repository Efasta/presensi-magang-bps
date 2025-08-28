<x-layout :title="$title">
    <div class="max-w-3xl sm:max-w-6xl sm:mx-auto mx-2 mt-6.5 bg-white rounded-lg border border-gray-200 p-6">
        <div class="flex items-center mb-5 space-x-5">
            <img src="{{ $absensi->user && $absensi->user->foto ? asset('storage/' . $absensi->user->foto) : asset('img/Anonymous.png') }}"
                alt="{{ $absensi->user->name ?? 'Anonymous' }}" class="w-20 h-20 rounded-full object-cover">
            <div>
                <div class="flex items-center space-x-2">
                    <h3 class="text-xl font-semibold text-gray-900">{{ $absensi->user->name ?? 'Anonymous' }}</h3>
                    <span
                        class="inline-block {{ $absensi->status->warna }} text-white text-xs px-3 py-0.5 rounded-full font-medium">
                        {{ $absensi->status->nama }}
                    </span>
                </div>
                <span class="inline-block bg-green-100 text-green-800 text-xs px-3 py-0.5 rounded-full font-medium mt-1">
                    {{ $absensi->user && $absensi->user->fungsi ? $absensi->user->fungsi->nama : '-' }}
                </span>
                <p class="text-gray-500 mt-0.5 text-sm">
                    {{ \Carbon\Carbon::parse($absensi->created_at)->translatedFormat('d F Y, H:i') }}
                </p>
            </div>
        </div>

        <h1 class="text-2xl font-bold text-gray-900 mb-3">{{ $absensi->judul ?? '-' }}</h1>

        <p class="text-gray-700 leading-relaxed mb-6">{{ $absensi->keterangan ?? '-' }}</p>

        {{-- Tampilkan PDF jika ada --}}
        @if ($absensi->gambar)
            <div>
                <span class="block mb-2 font-semibold text-gray-700 text-sm">Bukti Upload (PDF):</span>

                <a href="{{ asset('storage/' . $absensi->gambar) }}" target="_blank"
                    class="inline-block group relative w-fit my-4">
                    <canvas id="pdf-preview"
                        class="w-full border border-gray-300 rounded-lg shadow-lg transition duration-200 group-hover:brightness-90 cursor-pointer"></canvas>
                    <div
                        class="absolute inset-0 flex items-center justify-center rounded-lg text-white text-xs font-semibold transition-opacity duration-500 opacity-0 group-hover:opacity-50 bg-black">
                        Klik untuk lihat penuh
                    </div>


                </a>
            </div>

            <!-- PDF.js CDN -->
            <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.14.305/pdf.min.js"></script>
            <script>
                document.addEventListener('DOMContentLoaded', () => {
                    const url = "{{ asset('storage/' . $absensi->gambar) }}";

                    pdfjsLib.GlobalWorkerOptions.workerSrc =
                        'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.14.305/pdf.worker.min.js';

                    const loadingTask = pdfjsLib.getDocument(url);
                    loadingTask.promise.then(pdf => {
                        pdf.getPage(1).then(page => {
                            const scale = 0.2; // <-- sesuaikan angka ini untuk ukuran preview
                            const viewport = page.getViewport({
                                scale
                            });

                            const canvas = document.getElementById('pdf-preview');
                            const context = canvas.getContext('2d');
                            canvas.height = viewport.height;
                            canvas.width = viewport.width;

                            const renderContext = {
                                canvasContext: context,
                                viewport: viewport
                            };

                            page.render(renderContext);
                        });
                    }).catch(error => {
                        console.error('PDF loading error:', error);
                        const canvas = document.getElementById('pdf-preview');
                        canvas.insertAdjacentHTML('afterend',
                            '<p class="text-red-500 mt-2">Gagal memuat preview PDF.</p>');
                    });
                });
            </script>
            <style>
                #pdf-preview {
                    /* contoh kecilkan lebar maksimal */
                    width: 100%;
                    /* supaya responsif */
                    height: auto;
                    /* biar proporsional */
                }
            </style>
        @elseif(!$absensi->gambar)
            <p class="text-sm text-gray-500 italic mt-4">Tidak ada bukti file yang diunggah.</p>
            <div class="mt-8">
                <a href="/absensi"
                    class="inline-block bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 font-semibold text-sm transition">
                    ← Kembali
                </a>
            </div>
        @endif

        @php
            $isAdmin = Auth::user()->is_admin;
        @endphp
        @if (!$isAdmin)
            <div class="">
                <a href="/dashboard"
                    class="inline-block bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 font-semibold text-sm transition">
                    ← Kembali
                </a>
            @else
                <div class="">
                    <a href="/absensi"
                        class="inline-block bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 font-semibold text-sm transition">
                        ← Kembali
                    </a>
        @endif

    </div>
</x-layout>


{{-- uploads/gambar/Y0roKmgY65ozjw5T2mMRHuzrVPJKlZnLi8H6IgQE.pdf --}}
