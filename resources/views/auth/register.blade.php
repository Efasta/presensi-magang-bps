<x-guest-layout>

    <form method="POST" action="{{ route('register') }}" class="w-full">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
            <!-- Nama Lengkap -->
            <div>
                <x-input-label for="name" :value="__('Nama Lengkap')" />
                <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')"
                    required autofocus autocomplete="name" />
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>

            <!-- NIM/NISN -->
            <div>
                <x-input-label for="nim" :value="__('NIM/NISN')" />
                <x-text-input id="nim" class="block mt-1 w-full" type="text" name="nim" :value="old('nim')"
                    required autofocus autocomplete="nim" />
                <x-input-error :messages="$errors->get('nim')" class="mt-2" />
            </div>

            <!-- Jurusan -->
            <div>
                <x-input-label for="jurusan" :value="__('Jurusan')" />
                <x-text-input id="jurusan" class="block mt-1 w-full" type="text" name="jurusan" :value="old('jurusan')"
                    required autofocus autocomplete="jurusan" />
                <x-input-error :messages="$errors->get('jurusan')" class="mt-2" />
            </div>

            <!-- Universitas -->
            <div>
                <x-input-label for="universitas" :value="__('Universitas')" />
                <x-text-input id="universitas" class="block mt-1 w-full" type="text" name="universitas"
                    :value="old('universitas')" required autofocus autocomplete="universitas" />
                <x-input-error :messages="$errors->get('universitas')" class="mt-2" />
            </div>

            <!-- Email -->
            <div>
                <x-input-label for="email" :value="__('Email')" />
                <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')"
                    required autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <!-- Telepon -->
            <div>
                <x-input-label for="telepon" :value="__('No. Telepon')" />
                <x-text-input id="telepon" class="block mt-1 w-full" type="text" name="telepon" :value="old('telepon')"
                    required autofocus autocomplete="telepon" />
                <x-input-error :messages="$errors->get('telepon')" class="mt-2" />
            </div>

            <!-- Alamat (Textarea, memanjang dua kolom) -->
            <div class="md:col-span-2">
                <x-input-label for="alamat" :value="__('Alamat')" />
                <textarea id="alamat" name="alamat"
                    class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md" rows="3"
                    required>{{ old('alamat') }}</textarea>
                <x-input-error :messages="$errors->get('alamat')" class="mt-2" />
            </div>

            <!-- Tanggal Masuk -->
            <div>
                <x-input-label for="tanggal_masuk" :value="__('Tanggal Masuk')" />
                <x-text-input id="tanggal_masuk" class="block mt-1 w-full text-gray-500" type="date"
                    name="tanggal_masuk" :value="old('tanggal_masuk')" required autofocus autocomplete="tanggal_masuk" />
                <x-input-error :messages="$errors->get('tanggal_masuk')" class="mt-2" />
            </div>

            <!-- Tanggal Keluar -->
            <div>
                <x-input-label for="tanggal_keluar" :value="__('Tanggal Keluar')" />
                <x-text-input id="tanggal_keluar" class="block mt-1 w-full text-gray-500" type="date"
                    name="tanggal_keluar" :value="old('tanggal_keluar')" required autofocus autocomplete="tanggal_keluar" />
                <x-input-error :messages="$errors->get('tanggal_keluar')" class="mt-2" />
            </div>

            <!-- Jenis Kelamin -->
            <div>
                <x-input-label for="jenis_kelamin" :value="__('Jenis Kelamin')" />
                <select id="jenis_kelamin" name="jenis_kelamin"
                    class="block mt-1 w-full text-gray-500 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md">
                    <option value="">Pilih</option>
                    <option value="Laki-laki" @selected(old('jenis_kelamin') == 'Laki-laki')>Laki-laki</option>
                    <option value="Perempuan" @selected(old('jenis_kelamin') == 'Perempuan')>Perempuan</option>
                </select>
                <x-input-error :messages="$errors->get('jenis_kelamin')" class="mt-2" />
            </div>

            <!-- Keahlian -->
            <div>
                <x-input-label for="keahlian" :value="__('Keahlian')" />
                <x-text-input id="keahlian" class="block mt-1 w-full" type="text" name="keahlian" :value="old('keahlian')"
                    required autofocus autocomplete="keahlian" />
                <x-input-error :messages="$errors->get('keahlian')" class="mt-2" />
            </div>

            <!-- Fungsi -->
            <div class="md:col-span-2">
                <x-input-label for="fungsi_id" :value="__('Fungsi')" />
                <select id="fungsi_id" name="fungsi_id" required
                    class="block mt-1 w-full text-gray-500 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md">
                    <option value="">Pilih Fungsi</option>
                    @foreach ($fungsiList as $fungsi)
                        <option value="{{ $fungsi->id }}" @selected(old('fungsi_id') == $fungsi->id)>
                            {{ $fungsi->nama }}
                        </option>
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('fungsi_id')" class="mt-2" />
            </div>


            <!-- Password -->
            <div>
                <x-input-label for="password" :value="__('Password')" />
                <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required
                    autocomplete="new-password" />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <!-- Confirm Password -->
            <div>
                <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
                <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password"
                    name="password_confirmation" required autocomplete="new-password" />
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
            </div>
        </div>

        <!-- Tombol -->
        <div class="flex items-center justify-end mt-6">
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-hidden focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                href="{{ route('login') }}">
                {{ __('Sudah terdaftar?') }}
            </a>

            <x-primary-button class="ms-4">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>

</x-guest-layout>
