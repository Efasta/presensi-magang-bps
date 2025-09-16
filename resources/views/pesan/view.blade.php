<x-layout :title="$title">
    <section class="bg-white dark:bg-gray-900 antialiased">
        <div class="max-w-screen-xl px-4 py-8 mx-auto lg:px-6 sm:py-16 lg:py-24">
            <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-6 relative">
                <!-- Header -->
                <div class="relative border-b-2 border-gray-400 dark:border-gray-700 pb-2 mb-4">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white text-center">
                        Message Details
                    </h2>
                </div>

                <!-- Content -->
                <div class="flex items-center space-x-4 mb-6">
                    <!-- Sender's Photo -->
                    <img class="w-14 h-14 rounded-full object-cover" src="{{ asset($notif->foto) }}" alt="Sender's Photo">

                    <!-- Sender's Info -->
                    <div>
                        <p class="text-xl font-semibold text-gray-800 dark:text-gray-100">
                            {{ $notif->nama }}
                        </p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            {{ $notif->created_at->format('M d, Y, H:i') }}
                        </p>
                    </div>
                </div>

                <!-- Message Content -->
                <div class="bg-gray-100 dark:bg-gray-800 p-6 rounded-lg shadow-sm mb-6">
                    <p class="text-lg text-gray-800 dark:text-gray-200">
                        {{ $notif->pesan }}
                    </p>
                </div>

                @php
                    $isAdmin = Auth::user()->is_admin;
                @endphp

                <div class="flex justify-end space-x-2">
                    @if ($isAdmin)
                        {{-- Edit --}}
                        <a href="/pesan/{{ $notif->slug }}/edit"
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
                        <form action="/pesan/{{ $notif->slug }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="cursor-pointer flex items-center text-red-700 hover:text-white border border-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-3 py-2 text-center">
                                <svg class="h-4 w-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                                        clip-rule="evenodd" />
                                </svg>
                                Delete
                            </button>
                        </form>
                    @endif

                    <!-- Tombol Kembali -->
                    <a href="/pesan"
                        class="px-4 py-2 text-sm text-gray-800 bg-gray-100 border border-gray-300 rounded-lg hover:bg-gray-200">
                        ← Kembali ke pesan
                    </a>
                </div>

            </div>
        </div>
    </section>
</x-layout>
