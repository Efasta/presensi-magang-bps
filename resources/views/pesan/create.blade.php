<x-layout :title="$title">
    <section class="bg-white dark:bg-gray-900 antialiased py-8">
        <div class="max-w-screen-xl mx-auto px-4 lg:px-6">

            <!-- Container untuk membuat form ke tengah -->
            <div class="flex justify-center">
                <div class="w-full max-w-2xl relative p-4 bg-white rounded-lg border dark:bg-gray-800 sm:p-5">
                    <!-- Modal header -->
                    <div
                        class="flex justify-between items-center pb-4 mb-4 rounded-t border-b sm:mb-5 dark:border-gray-600">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Tambah Pesan</h3>
                    </div>

                    <!-- Modal body -->
                    <form action="/pesan" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label for="nama" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                Nama
                            </label>
                            <input type="text" name="nama" id="nama" value="{{ Auth::user()->name }}"
                                {{-- otomatis isi nama admin --}} readonly {{-- supaya tidak bisa diubah --}}
                                class="border border-gray-300 text-gray-500 text-sm rounded-lg 
                            focus:ring-emerald-600 focus:border-emerald-600 
                            block w-full p-2.5 bg-gray-100 cursor-not-allowed
                            dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        </div>


                        <div class="mb-4">
                            <label for="pesan"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Pesan</label>
                            <textarea id="pesan" name="pesan" rows="4"
                                class="@error('pesan')
                                bg-red-50 border-red-500 text-red-500 placeholder-red-700 focus:ring-red-500 focus:border-red-500
                            @enderror block p-2.5 w-full text-sm text-gray-900 rounded-lg border border-gray-300 focus:ring-emerald-500 focus:border-emerald-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-emerald-500 dark:focus:border-emerald-500"
                                placeholder="Isi pesan di sini"></textarea>
                            @error('pesan')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Tombol Aksi -->
                        <div class="flex justify-end space-x-2">
                            <!-- Tombol kembali -->
                            <a href="{{ url()->previous() }}"
                                class="px-4 py-2 text-sm text-gray-800 bg-gray-100 border border-gray-300 rounded-lg hover:bg-gray-200">
                                ← Kembali
                            </a>

                            <!-- Tombol submit -->
                            <button type="submit"
                                class="cursor-pointer text-white inline-flex items-center bg-emerald-700 hover:bg-emerald-800 focus:ring-4 focus:outline-none focus:ring-emerald-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-emerald-600 dark:hover:bg-emerald-700 dark:focus:ring-emerald-800">
                                <svg class="mr-1 -ml-1 w-5 h-5" fill="currentColor" viewBox="0 0 20 20"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd"
                                        d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"
                                        clip-rule="evenodd" />
                                </svg>
                                Tambahkan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</x-layout>
