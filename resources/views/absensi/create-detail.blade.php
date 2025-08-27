<x-layout :title="$title">
    <div class="flex justify-center items-start py-10">
        <div class="w-full max-w-4xl bg-white p-8 rounded-lg border border-gray-200">
            <h2 class="text-3xl font-bold text-emerald-700 mb-10 text-center">
                Formulir Pengajuan Izin / Sakit
            </h2>

            <form method="POST" action="{{ route('absensi.storeDetail') }}" enctype="multipart/form-data">
                @csrf

                {{-- Baris: Judul & Status --}}
                <div class="grid md:grid-cols-2 gap-6 mb-6">
                    {{-- Judul --}}
                    <div>
                        <label for="judul" class="block text-base font-medium text-gray-700 mb-2">Judul</label>
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
                        <label for="status_id" class="block text-base font-medium text-gray-700 mb-2">Status</label>
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

                {{-- Keterangan --}}
                <div class="mb-6">
                    <label for="keterangan" class="block text-base font-medium text-gray-700 mb-2">Keterangan</label>
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
                    <label for="gambar" class="block text-base font-medium text-gray-700 mb-2">Upload Bukti
                        (Opsional)</label>
                    <input type="file" id="gambar" name="gambar" accept="image/*"
                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-emerald-500" />
                    @error('gambar')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Tombol --}}
                <div class="flex justify-end gap-4">
                    <a href="/dashboard"
                        class="bg-gray-500 text-white px-6 py-2 rounded-md hover:bg-gray-600 transition">Cancel</a>
                    <button type="submit"
                        class="bg-emerald-600 text-white px-8 py-2 rounded-md hover:bg-emerald-700 transition font-semibold">Kirim</button>
                </div>
            </form>
        </div>
    </div>
</x-layout>
