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

                <!-- Back Button -->
                <div class="flex justify-end">
                    <a href="/pesan" class="px-4 py-2 text-sm text-gray-800 bg-gray-100 border border-gray-300 rounded-lg hover:bg-gray-200">
                        ← Kembali ke pesan
                    </a>
                </div>
            </div>
        </div>
    </section>
</x-layout>
