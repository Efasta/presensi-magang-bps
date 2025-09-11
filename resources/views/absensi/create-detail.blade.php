<x-layout :title="$title">
    @push('style')
        <link href="https://unpkg.com/filepond@^4/dist/filepond.css" rel="stylesheet" />
    @endpush
    <div class="flex justify-center items-start sm:mt-[19px] sm:mb-22 mt-4.5 mb-22 sm:mx-0 mx-4">
        <div class="w-full max-w-4xl bg-white p-8 rounded-lg border border-gray-200">
            <h2 class="text-3xl font-bold text-emerald-700 mb-5 text-center">
                Formulir Pengajuan Izin / Sakit
            </h2>

            <form method="POST" action="{{ route('absensi.storeDetail') }}" enctype="multipart/form-data">
                @csrf

                {{-- Baris: Judul & Status --}}
                <div class="grid md:grid-cols-2 gap-6 mb-6">
                    {{-- Judul --}}
                    <div>
                        <label for="judul" class="block text-sm font-medium text-gray-700 mb-2">Judul</label>
                        <input type="text" id="judul" name="judul" value="{{ old('judul') }}"
                            placeholder="Contoh: Izin Tidak Masuk karena Urusan Keluarga"
                            class="@error('judul')
                                bg-red-50 border-red-500 text-red-500 placeholder-red-700 focus:ring-red-500 focus:border-red-500
                            @enderror w-full px-4 py-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-emerald-500">
                        @error('judul')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Status --}}
                    <div>
                        <label for="status_id" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                        <select id="status_id" name="status_id"
                            class="@error('status_id')
                                bg-red-50 border-red-500 text-red-700 placeholder-red-700 focus:ring-red-500 focus:border-red-500
                            @enderror w-full px-4 py-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-emerald-500">
                            <option value="" disabled {{ old('status_id') ? '' : 'selected' }}>Pilih status izin
                                atau sakit</option>
                            @foreach (App\Models\Status::whereIn('nama', ['Izin', 'Sakit'])->get() as $status)
                                <option value="{{ $status->id }}"
                                    {{ old('status_id') == $status->id ? 'selected' : '' }}>
                                    {{ ucfirst($status->nama) }}
                                </option>
                            @endforeach
                        </select>
                        @error('status_id')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <input type="hidden" name="tanggal" value="{{ now()->toDateString() }}">

                <div class="mb-4">
                    <label for="tanggal_selesai" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Selesai
                        (maksimal 30 hari)</label>
                    <input type="date" name="tanggal_selesai" id="tanggal_selesai"
                        class="@error('tanggal_selesai')
                                bg-red-50 border-red-500 text-red-700 placeholder-red-700 focus:ring-red-500 focus:border-red-500
                            @enderror w-full px-4 py-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-emerald-500"
                        min="{{ now()->toDateString() }}">
                    @error('tanggal_selesai')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Keterangan --}}
                <div class="mb-6">
                    <label for="keterangan" class="block text-sm font-medium text-gray-700 mb-2">Keterangan</label>
                    <textarea id="keterangan" name="keterangan" rows="5"
                        placeholder="Jelaskan alasan lengkap mengapa Anda izin atau sakit..."
                        class="@error('keterangan')
                                bg-red-50 border-red-500 text-red-500 placeholder-red-700 focus:ring-red-500 focus:border-red-500
                            @enderror w-full px-4 py-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-emerald-500">{{ old('keterangan') }}</textarea>
                    @error('keterangan')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Upload Bukti --}}
                <div class="mb-8">
                    <label for="gambar" class="block text-sm font-medium text-gray-700 mb-2">Upload Bukti
                        (Opsional)</label>
                    <input
                        class="@error('gambar') bg-red-50 border-red-500 text-red-500 placeholder-red-700 focus:ring-red-500 focus:border-red-500 @enderror block w-full text-sm text-gray-800 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400"
                        aria-describedby="gambar_help" id="gambar" name="gambar" type="file"
                        accept="application/pdf">
                    <input type="hidden" name="gambar_path" id="gambar_path">
                    <div class="mt-1 text-sm text-gray-500 dark:text-gray-300" id="gambar_help">File yang diterima
                        adalah PDF dan tak lebih besar dari 10MB. <br> !PERHATIAN! (Jangan keluar dari halaman ini
                        setelah mengupload file dan belum mengirim.)</div>
                    @error('gambar')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Tombol --}}
                <div class="flex justify-end gap-4">
                    <a href="/dashboard"
                        class="bg-gray-500 text-white px-6 py-2 rounded-md hover:bg-gray-600 transition">Cancel</a>
                    <button type="submit"
                        class="bg-emerald-600 text-white px-8 py-2 rounded-md hover:bg-emerald-700 transition font-semibold hover:cursor-pointer">Kirim</button>
                </div>
            </form>
        </div>
    </div>
    @push('script')
        <script src="https://unpkg.com/filepond-plugin-file-validate-size/dist/filepond-plugin-file-validate-size.js"></script>
        <script src="https://unpkg.com/filepond-plugin-file-validate-type/dist/filepond-plugin-file-validate-type.js"></script>
        <script src="https://unpkg.com/filepond@^4/dist/filepond.js"></script>

        <script>
            FilePond.registerPlugin(FilePondPluginFileValidateType);
            FilePond.registerPlugin(FilePondPluginFileValidateSize);

            const inputElement = document.querySelector('#gambar');
            const pond = FilePond.create(inputElement, {
                acceptedFileTypes: ['application/pdf'],
                labelFileTypeNotAllowed: 'Hanya file PDF yang diizinkan.',
                fileValidateTypeLabelExpectedTypes: 'Format file yang diperbolehkan: PDF',
                maxFileSize: '10MB',
                labelMaxFileSizeExceeded: 'Ukuran file terlalu besar.',
                labelMaxFileSize: 'Ukuran maksimum: 10MB',
                server: {
                    process: {
                        url: '/upload-absensi',
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        onload: (response) => {
                            // Set path yang dikembalikan ke input hidden
                            document.querySelector('#gambar_path').value = response;
                            return response;
                        }, // response adalah path nama file, misalnya "tmp/abcd123.pdf"
                    },
                    revert: (filename, load, error) => {
                        fetch('/revert-absensi', {
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'Content-Type': 'application/json'
                                },
                                body: JSON.stringify({
                                    filename: filename
                                })
                            })
                            .then(res => {
                                if (res.ok) {
                                    load(); // Berhasil hapus
                                } else {
                                    error('Gagal menghapus file');
                                }
                            })
                            .catch(() => {
                                error('Gagal menghapus file');
                            });
                    }
                },
                labelIdle: 'Drag & drop file PDF Anda atau <span class="filepond--label-action">Telusuri</span>',
                labelFileProcessing: 'Mengupload...',
                labelFileProcessingComplete: 'Upload selesai',
                labelTapToUndo: 'Ketuk untuk batal',
                labelFileProcessingError: 'Gagal mengupload',
                labelFileRemoveError: 'Gagal menghapus file',
            });
        </script>
    @endpush
</x-layout>
