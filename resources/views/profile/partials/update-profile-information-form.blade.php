@push('style')
    <link href="https://unpkg.com/filepond@^4/dist/filepond.css" rel="stylesheet" />
    <link href="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.css" rel="stylesheet" />
@endpush
<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Informasi Profil') }}
        </h2>
        <p class="mt-1 text-sm text-gray-600">
            {{ __('Perbarui informasi profil akun dan alamat email Anda.') }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6" enctype="multipart/form-data">
        @csrf
        @method('patch')

        <!-- Nama Lengkap -->
        <div>
            <x-input-label for="name" :value="__('Nama Lengkap')" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full autofocus"
                :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <!-- NIM/NISN (disabled) -->
        <div>
            <x-input-label for="nim" :value="__('NIM/NISN')" />
            <x-text-input id="nim" name="nim" type="text"
                class="mt-1 block w-full text-gray-500 cursor-not-allowed" :value="old('nim', $user->nim)" required autocomplete="nim"
                disabled />
            <x-input-error class="mt-2" :messages="$errors->get('nim')" />
        </div>

        <!-- Jurusan -->
        <div>
            <x-input-label for="jurusan" :value="__('Jurusan')" />
            <x-text-input id="jurusan" name="jurusan" type="text" class="mt-1 block w-full" :value="old('jurusan', $user->jurusan)"
                required autocomplete="jurusan" />
            <x-input-error class="mt-2" :messages="$errors->get('jurusan')" />
        </div>

        <!-- Universitas -->
        <div>
            <x-input-label for="universitas" :value="__('Universitas')" />
            <x-text-input id="universitas" name="universitas" type="text" class="mt-1 block w-full"
                :value="old('universitas', $user->universitas)" required autocomplete="universitas" />
            <x-input-error class="mt-2" :messages="$errors->get('universitas')" />
        </div>

        <!-- Email -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)"
                required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !$user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800">
                        {{ __('Alamat email Anda belum diverifikasi.') }}

                        <button form="send-verification"
                            class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-hidden focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            {{ __('Klik di sini untuk mengirim ulang email verifikasi.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600">
                            {{ __('Tautan verifikasi baru telah dikirim ke alamat email Anda.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <!-- No Telepon -->
        <div>
            <x-input-label for="telepon" :value="__('No. Telepon')" />
            <x-text-input id="telepon" name="telepon" type="tel" class="mt-1 block w-full" :value="old('telepon', $user->telepon)"
                required autocomplete="tel" />
            <x-input-error class="mt-2" :messages="$errors->get('telepon')" />
        </div>

        <!-- Alamat Lengkap -->
        <div>
            <x-input-label for="alamat" :value="__('Alamat Lengkap')" />
            <x-text-input id="alamat" name="alamat" type="text" class="mt-1 block w-full" :value="old('alamat', $user->alamat)"
                required autocomplete="address" />
            <x-input-error class="mt-2" :messages="$errors->get('alamat')" />
        </div>

        <!-- Tanggal Masuk & Keluar dalam satu baris -->
        <div class="grid grid-cols-2 gap-4">
            <!-- Tanggal Masuk (disabled) -->
            <div>
                <x-input-label for="tanggal_masuk" :value="__('Tanggal Masuk')" />
                <x-text-input id="tanggal_masuk" name="tanggal_masuk" type="date"
                    class="text-gray-500 mt-1 block w-full cursor-not-allowed" :value="old('tanggal_masuk', optional($user->tanggal_masuk)->format('Y-m-d'))" required
                    autocomplete="tanggal_masuk" disabled />
                <x-input-error class="mt-2" :messages="$errors->get('tanggal_masuk')" />
            </div>

            <div>
                <x-input-label for="tanggal_keluar" :value="__('Tanggal Keluar')" />
                <x-text-input id="tanggal_keluar" name="tanggal_keluar" type="date" class="mt-1 block w-full"
                    :value="old('tanggal_keluar', optional($user->tanggal_keluar)->format('Y-m-d'))" required autocomplete="tanggal_keluar" />
                <x-input-error class="mt-2" :messages="$errors->get('tanggal_keluar')" />
            </div>
        </div>

        <!-- Jenis Kelamin (disabled + hidden input agar tetap terkirim) -->
        <div>
            <x-input-label for="jenis_kelamin" :value="__('Jenis Kelamin')" />
            <select id="jenis_kelamin" name="jenis_kelamin_disabled"
                class="mt-1 block w-full rounded-md border-gray-300 text-gray-500 cursor-not-allowed" disabled>
                <option value="Laki-laki"
                    {{ old('jenis_kelamin', $user->jenis_kelamin) == 'Laki-laki' ? 'selected' : '' }}>Laki-laki
                </option>
                <option value="Perempuan"
                    {{ old('jenis_kelamin', $user->jenis_kelamin) == 'Perempuan' ? 'selected' : '' }}>Perempuan
                </option>
            </select>
            <!-- Hidden input supaya tetap terkirim -->
            <input type="hidden" name="jenis_kelamin" value="{{ old('jenis_kelamin', $user->jenis_kelamin) }}" />
            <x-input-error class="mt-2" :messages="$errors->get('jenis_kelamin')" />
        </div>

        {{-- Upload foto --}}
        <div>
            <label class="block mb-2 text-sm font-medium text-gray-800 dark:text-white" for="foto">Upload
                Foto</label>
            <input
                class="@error('foto') bg-red-50 border-red-500 text-red-500 placeholder-red-700 focus:ring-red-500 focus:border-red-500 @enderror block w-full text-sm text-gray-800 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400"
                aria-describedby="foto_help" id="foto" name="foto" type="file"
                accept="image/jpeg, image/jpg, image/png">
            <div class="mt-1 text-sm text-gray-500 dark:text-gray-300" id="foto_help">Ukuran gambar maksimal adalah
                1 MB.</div>
            @error('foto')
                <p class="mt-2 text-xs text-red-600">
                    {{ $message }}
                </p>
            @enderror
        </div>

        <div>
            <img class="w-20 h-20 rounded-full"
                src="{{ $user->foto ? asset('storage/' . $user->foto) : asset('img/Anonymous.png') }}"
                alt="{{ $user->name }}" id="foto-preview">
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>
                {{ __('Simpan') }}
            </x-primary-button>

            <a href="/users"
                class="inline-flex items-center text-white bg-red-600 hover:bg-red-700 focus:ring-4 focus:outline-none focus:ring-red-300 font-semibold rounded-lg text-sm px-4 py-2 text-center dark:bg-red-500 dark:hover:bg-red-600 dark:focus:ring-red-900 uppercase">
                Cancel
            </a>

            @if (session('status') === 'profile-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 5000)"
                    class="text-sm text-gray-600">{{ __('Tersimpan.') }}</p>
            @endif
        </div>
    </form>
</section>

@push('script')
    <script>
        const input = document.getElementById('foto');
        const previewPhoto = () => {
            const file = input.files;
            if (file) {
                const fileReader = new FileReader();
                const preview = document.getElementById('foto-preview');
                fileReader.onload = function(event) {
                    preview.setAttribute('src', event.target.result);
                }
                fileReader.readAsDataURL(file[0]);
            }
        }
        input.addEventListener("change", previewPhoto);
    </script>

    <script src="https://unpkg.com/filepond-plugin-image-transform/dist/filepond-plugin-image-transform.js"></script>
    <script src="https://unpkg.com/filepond-plugin-image-resize/dist/filepond-plugin-image-resize.js"></script>
    <script src="https://unpkg.com/filepond-plugin-file-validate-size/dist/filepond-plugin-file-validate-size.js"></script>
    <script src="https://unpkg.com/filepond-plugin-file-validate-type/dist/filepond-plugin-file-validate-type.js"></script>
    <script src="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.js"></script>
    <script src="https://unpkg.com/filepond@^4/dist/filepond.js"></script>

    <script>
        FilePond.registerPlugin(FilePondPluginImagePreview);
        FilePond.registerPlugin(FilePondPluginFileValidateType);
        FilePond.registerPlugin(FilePondPluginFileValidateSize);
        FilePond.registerPlugin(FilePondPluginImageTransform);
        FilePond.registerPlugin(FilePondPluginImageResize);


        const inputElement = document.querySelector('#foto');
        const pond = FilePond.create(inputElement, {
            acceptedFileTypes: ['image/jpeg, image/jpg, image/png'],
            maxFileSize: '1MB',
            imageResizeTargetWidth: '600',
            imageResizeMode: 'contain',
            imageResizeUpscale: false,
            server: {
                url: '/upload',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            }
        });
    </script>
@endpush
