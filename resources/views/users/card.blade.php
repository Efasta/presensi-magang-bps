<x-layout :title="$title">

    <div class="mt-17 mb-34">
        <div
            class="max-w-270 h-auto mx-5 my-6 p-6 xl:mx-auto bg-white border-4 border-gray-200 rounded-xl shadow-xl flex flex-col md:flex-row gap-x-15">

            {{-- FOTO USER --}}
            <div
                class="w-full md:w-auto flex flex-col justify-start pr-5 text-gray-800 font-semibold order-1 md:order-2 sm:border-l pl-6 pt-9">
                <img src="{{ $user->foto ? asset('storage/' . $user->foto) : asset('img/Anonymous.png') }}"
                    alt="{{ $user->name }}"
                    class="w-65 h-72 object-cover border-2 border-black rounded-lg shadow-sm mx-auto md:ml-8 mb-7">

                {{-- Biodata tambahan --}}
                @php
                    $biodataTambahan = [
                        'Jenis Kelamin' => $user->jenis_kelamin,
                        'Keahlian' => $user->keahlian,
                        'Fungsi' => $user->fungsi->nama,
                    ];
                @endphp

                @foreach ($biodataTambahan as $label => $value)
                    <div class="flex">
                        <div class="w-32 h-12 text-gray-500">{{ $label }}</div>
                        <div class="mr-2">:</div>
                        <div class="underline">{{ $value }}</div>
                    </div>
                @endforeach
            </div>

            {{-- BIODATA UTAMA --}}
            <div
                class="w-full md:w-auto flex flex-col justify-start gap-2 pl-5 pt-3 text-gray-800 font-semibold order-2 md:order-1">
                @php
                    $biodata = [
                        'Nama' => $user->name,
                        'NIM/NISN' => $user->nim,
                        'Jurusan' => $user->jurusan,
                        'Universitas/Sekolah' => $user->universitas,
                        'Email' => $user->email,
                        'Telepon' => $user->telepon,
                        'Alamat' => $user->alamat,
                        'Tanggal Masuk' => $user->tanggal_masuk ? $user->tanggal_masuk->format('d-m-Y') : '',
                        'Tanggal Keluar' => $user->tanggal_keluar ? $user->tanggal_keluar->format('d-m-Y') : '',
                    ];
                @endphp

                @foreach ($biodata as $label => $value)
                    <div class="flex">
                        <div class="w-55 h-10 text-gray-500">{{ $label }}</div>
                        <div class="mr-2">:</div>
                        <div class="underline break-words">{{ $value }}</div>
                    </div>
                @endforeach

                {{-- Tombol Aksi --}}
                <div class="flex mt-6 w-full">
                    @php
                        $isAdmin = Auth::user()->is_admin;
                        
                        $isAlumni = \App\Models\Absensi::where('user_id', $user->id)
                            ->where('status_id', 5)
                            ->exists();
                    @endphp
                    {{-- Tombol Kembali --}}
                    @if (!$isAdmin)
                    <a href="/dashboard"
                        class="inline-block px-4 py-2 text-sm bg-blue-600 text-white font-semibold rounded hover:bg-blue-700 transition">
                        ← Kembali ke Dashboard
                    </a>
                    @else
                    <a href="{{ url()->previous() }}"
                        class="inline-block px-4 py-2 text-sm bg-blue-600 text-white font-semibold rounded hover:bg-blue-700 transition">
                        ← Kembali
                    </a>
                    @endif

                    {{-- Tombol Edit & Delete (dalam flex) --}}
                    @if(!$isAdmin && !$isAlumni)
                    <div class="flex space-x-3 ml-auto">
                        {{-- Edit --}}
                        <a href="/profile"
                            class="py-2 px-3 flex items-center text-sm font-medium text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300">
                            <svg class="h-4 w-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z" />
                                <path fill-rule="evenodd"
                                    d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"
                                    clip-rule="evenodd" />
                            </svg>
                            Edit
                        </a>

                        {{-- Delete --}}
                        <a href="/profile?open_delete=1"
                            class="flex items-center text-red-700 hover:text-white border border-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-3 py-2 text-center">
                            <svg class="h-4 w-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                                    clip-rule="evenodd" />
                            </svg>
                            Delete
                        </a>
                    </div>
                    @endif

                </div>
            </div>
        </div>
    </div>

</x-layout>
