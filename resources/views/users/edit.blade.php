<x-layout :title="$title">
    <div class="pt-4 pb-21">
        <form action="{{ route('users.update', $user->slug) }}" method="POST"
            class="max-w-5xl mx-auto bg-white p-6 rounded-lg border">
            @csrf
            @method('PATCH')

            {{-- Foto profil --}}
            <div class="flex justify-center mb-6">
                <img class="w-24 h-24 rounded-full object-cover"
                    src="{{ $user->foto ? asset('storage/' . $user->foto) : asset('img/Anonymous.png') }}"
                    alt="{{ $user->name }}">
            </div>

            {{-- Nama & NIM --}}
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}"
                        class="@error('Nama')
                                bg-red-50 border-red-500 text-red-500 placeholder-red-700 focus:ring-red-500 focus:border-red-500
                            @enderror form-input w-full">
                    @error('name')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="nim" class="block text-sm font-medium text-gray-700">NIM/NISN</label>
                    <input type="text" name="nim" id="nim" value="{{ old('nim', $user->nim) }}"
                        class="@error('nim')
                                bg-red-50 border-red-500 text-red-500 placeholder-red-700 focus:ring-red-500 focus:border-red-500
                            @enderror form-input w-full">
                    @error('nim')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Jurusan & Universitas --}}
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label for="jurusan" class="block text-sm font-medium text-gray-700">Jurusan</label>
                    <input type="text" name="jurusan" id="jurusan" value="{{ old('jurusan', $user->jurusan) }}"
                        class="@error('jurusan')
                                bg-red-50 border-red-500 text-red-500 placeholder-red-700 focus:ring-red-500 focus:border-red-500
                            @enderror form-input w-full">
                    @error('jurusan')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="universitas" class="block text-sm font-medium text-gray-700">Universitas</label>
                    <input type="text" name="universitas" id="universitas"
                        value="{{ old('universitas', $user->universitas) }}"
                        class="@error('universitas')
                                bg-red-50 border-red-500 text-red-500 placeholder-red-700 focus:ring-red-500 focus:border-red-500
                            @enderror form-input w-full">
                    @error('universitas')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Email & Telepon --}}
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}"
                        class="@error('email')
                                bg-red-50 border-red-500 text-red-500 placeholder-red-700 focus:ring-red-500 focus:border-red-500
                            @enderror form-input w-full">
                    @error('email')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="telepon" class="block text-sm font-medium text-gray-700">No. Telepon</label>
                    <input type="text" name="telepon" id="telepon" value="{{ old('telepon', $user->telepon) }}"
                        class="@error('telepon')
                                bg-red-50 border-red-500 text-red-500 placeholder-red-700 focus:ring-red-500 focus:border-red-500
                            @enderror form-input w-full">
                    @error('telepon')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Alamat --}}
            <div class="mb-4">
                <label for="alamat" class="block text-sm font-medium text-gray-700">Alamat</label>
                <textarea name="alamat" id="alamat" rows="3"
                    class="@error('alamat')
                                bg-red-50 border-red-500 text-red-500 placeholder-red-700 focus:ring-red-500 focus:border-red-500
                            @enderror form-textarea w-full">{{ old('alamat', $user->alamat) }}</textarea>
                @error('alamat')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Tanggal Masuk & Keluar --}}
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label for="tanggal_masuk" class="block text-sm font-medium text-gray-700">Tanggal Masuk</label>
                    <input type="date" name="tanggal_masuk" id="tanggal_masuk"
                        value="{{ old('tanggal_masuk', \Carbon\Carbon::parse($user->tanggal_masuk)->format('Y-m-d')) }}"
                        class="@error('tanggal_masuk')
                                bg-red-50 border-red-500 text-red-500 placeholder-red-700 focus:ring-red-500 focus:border-red-500
                            @enderror form-input w-full">
                    @error('tanggal_masuk')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                    <p class="text-gray-400 pt-1">*formatnya (bulan-tanggal-tahun)</p>
                </div>
                <div>
                    <label for="tanggal_keluar" class="block text-sm font-medium text-gray-700">Tanggal Keluar</label>
                    <input type="date" name="tanggal_keluar" id="tanggal_keluar"
                        value="{{ old('tanggal_keluar', \Carbon\Carbon::parse($user->tanggal_keluar)->format('Y-m-d')) }}"
                        class="@error('tanggal_keluar')
                                bg-red-50 border-red-500 text-red-500 placeholder-red-700 focus:ring-red-500 focus:border-red-500
                            @enderror form-input w-full">
                    @error('tanggal_keluar')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Jenis Kelamin & Keahlian --}}
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label for="jenis_kelamin" class="block text-sm font-medium text-gray-700">Jenis Kelamin</label>
                    <select name="jenis_kelamin" id="jenis_kelamin"
                        class="@error('jenis_kelamin')
                                bg-red-50 border-red-500 text-red-500 placeholder-red-700 focus:ring-red-500 focus:border-red-500
                            @enderror form-select w-full">
                        <option value="">Pilih</option>
                        <option value="Laki-laki"
                            {{ old('jenis_kelamin', $user->jenis_kelamin) == 'Laki-laki' ? 'selected' : '' }}>Laki-laki
                        </option>
                        <option value="Perempuan"
                            {{ old('jenis_kelamin', $user->jenis_kelamin) == 'Perempuan' ? 'selected' : '' }}>Perempuan
                        </option>
                    </select>
                    @error('jenis_kelamin')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="keahlian" class="block text-sm font-medium text-gray-700">Keahlian</label>
                    <input type="text" name="keahlian" id="keahlian"
                        value="{{ old('keahlian', $user->keahlian) }}"
                        class="@error('keahlian')
                                bg-red-50 border-red-500 text-red-500 placeholder-red-700 focus:ring-red-500 focus:border-red-500
                            @enderror form-input w-full">
                    @error('keahlian')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Fungsi --}}
            <div class="mb-6">
                <label for="fungsi_id" class="block text-sm font-medium text-gray-700">Fungsi</label>
                <select name="fungsi_id" id="fungsi_id"
                    class="@error('fungsi_id')
                                bg-red-50 border-red-500 text-red-500 placeholder-red-700 focus:ring-red-500 focus:border-red-500
                            @enderror form-select w-full">
                    <option value="">Pilih Fungsi</option>
                    @foreach ($fungsis as $fungsi)
                        <option value="{{ $fungsi->id }}"
                            {{ old('fungsi_id', $user->fungsi_id) == $fungsi->id ? 'selected' : '' }}>
                            {{ $fungsi->nama }}
                        </option>
                    @endforeach
                </select>
                @error('fungsi_id')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>


            {{-- Tombol aksi --}}
            <div class="flex justify-end gap-4">
                <a href="{{ route('users.index') }}"
                    class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400">Cancel</a>
                <button type="submit" id="submitBtn"
                    class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 hover:cursor-pointer">Kirim</button>
                <script>
                    const form = document.querySelector('form');
                    const btn = document.getElementById('submitBtn');
                    form.addEventListener('submit', () => {
                        btn.disabled = true;
                        btn.textContent = 'Mengirim...';
                    });
                </script>
            </div>
        </form>
    </div>
</x-layout>
