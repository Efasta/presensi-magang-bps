<x-layout :title="$title">
    <section class="bg-white dark:bg-gray-900 antialiased">
        <div class="max-w-screen-xl px-4 py-8 mx-auto lg:px-6 sm:py-16 lg:py-24">
            <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-6">
                <div class="relative border-b-2 border-gray-400 dark:border-gray-700 pb-2 mb-4">
                    <div class="absolute left-0 top-1/2 -translate-y-1/2 flex items-center px-5">
                        <input type="checkbox" id="select_all" class="mr-2 cursor-pointer" />
                        <label for="select_all" class="text-gray-700 dark:text-gray-500 cursor-pointer select-none">
                            Select All
                        </label>

                        <!-- Action buttons -->
                        <div id="action_buttons" class="ml-4 flex gap-2 hidden">
                            <button id="btn_read"
                                class="px-3 py-1 text-sm text-gray-800 bg-gray-100 border border-gray-300 rounded hover:bg-gray-200 flex items-center gap-1">
                                <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                    fill="currentColor" aria-hidden="true">
                                    <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                                Read
                            </button>

                            <button id="btn_unread"
                                class="px-3 py-1 text-sm text-gray-800 bg-gray-100 border border-gray-300 rounded hover:bg-gray-200 flex items-center gap-1">
                                <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                    fill="currentColor" aria-hidden="true">
                                    <!-- Mata -->
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M10 5C6 5 3.2 7.6 2 10c1.2 2.4 4 5 8 5s6.8-2.6 8-5c-.8-1.6-3.6-5-8-5zm0 8a3 3 0 100-6 3 3 0 000 6z" />
                                    <!-- Lingkaran pupil -->
                                    <path d="M10 9a1 1 0 110 2 1 1 0 010-2z" />
                                    <!-- Garis miring menutupi mata -->
                                    <path d="M3 3l14 14" stroke="currentColor" stroke-width="1.5"
                                        stroke-linecap="round" />
                                </svg>


                                Unread
                            </button>

                            <button id="btn_delete"
                                class="px-3 py-1 text-sm text-gray-800 bg-gray-100 border border-gray-300 rounded hover:bg-gray-200 flex items-center gap-1">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 20 20"
                                    fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd"
                                        d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                                        clip-rule="evenodd" />
                                </svg>
                                Delete
                            </button>
                            <!-- Spinner -->
                            <div id="loading_spinner" class="hidden items-center">
                                <svg class="animate-spin h-5 w-5 text-gray-600" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                        stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                                </svg>
                            </div>
                        </div>

                    </div>

                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white text-center">
                        Inbox
                    </h2>
                </div>


                {{-- List pesan --}}
                <div class="divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach ($notifs as $notif)
                        <div
                            class="flex items-center py-3 px-5 border-b border-gray-300 {{ $notif->is_read ? 'bg-gray-100' : 'bg-white' }}">
                            <input type="checkbox" class="mr-4 message-checkbox" data-id="{{ $notif->id }}" />
                            <p
                                class="w-50 text-sm text-gray-600 dark:text-gray-500 shrink-0 sender-name {{ $notif->is_read ? '' : 'font-semibold' }}">
                                {{ $notif->nama }}
                            </p>
                            <a href="/pesan/{{ $notif->slug }}"
                                class="message-content flex-grow text-sm text-gray-800 dark:text-gray-400 truncate hover:underline cursor-pointer {{ $notif->is_read ? '' : 'font-semibold' }}">
                                {{ $notif->pesan }}
                            </a>
                            <p class="w-36 text-xs text-gray-500 dark:text-gray-600 text-right ml-4">
                                {{ $notif->created_at->format('M d, H:i') }}
                            </p>
                        </div>
                    @endforeach

                </div>
            </div>
        </div>
    </section>

    <script>
        const selectAll = document.getElementById('select_all');
        const actionButtons = document.getElementById('action_buttons');
        const btnRead = document.getElementById('btn_read');
        const btnUnread = document.getElementById('btn_unread');
        const btnDelete = document.getElementById('btn_delete');
        const loadingSpinner = document.getElementById('loading_spinner');

        // Fungsi untuk ambil semua checkbox aktif saat ini
        function getCheckboxes() {
            return document.querySelectorAll('.message-checkbox');
        }

        function showLoading() {
            loadingSpinner.classList.remove('hidden');
        }

        function hideLoading() {
            loadingSpinner.classList.add('hidden');
        }

        function toggleActionButtons() {
            const anyChecked = Array.from(getCheckboxes()).some(cb => cb.checked);
            if (anyChecked) {
                actionButtons.classList.remove('hidden'); // Tampilkan tombol
            } else {
                actionButtons.classList.add('hidden'); // Sembunyikan tombol
            }
        }

        // Saat "Select All" diubah
        selectAll.addEventListener('change', function() {
            getCheckboxes().forEach(cb => cb.checked = this.checked);
            toggleActionButtons();
        });

        // Pasang event change di tiap checkbox
        getCheckboxes().forEach(cb => {
            cb.addEventListener('change', toggleActionButtons);
        });

        // Fungsi tombol Read
        btnRead.addEventListener('click', () => {
            const selectedIds = [];
            getCheckboxes().forEach(cb => {
                if (cb.checked) {
                    selectedIds.push(cb.dataset.id);
                }
            });

            if (selectedIds.length === 0) return;

            showLoading();

            fetch("{{ route('notifikasi.read') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        ids: selectedIds
                    })
                })
                .then(response => response.json())
                .then(data => {
                    hideLoading();
                    if (data.status === 'success') {
                        getCheckboxes().forEach(cb => {
                            if (cb.checked) {
                                const messageDiv = cb.closest('div.flex.items-center.py-3.border-b');
                                if (messageDiv) {
                                    messageDiv.classList.add('bg-gray-100');
                                    messageDiv.classList.remove('bg-white');
                                    const messageContent = messageDiv.querySelector('.message-content');
                                    const senderName = messageDiv.querySelector('.sender-name');
                                    if (messageContent) messageContent.classList.remove(
                                    'font-semibold');
                                    if (senderName) senderName.classList.remove('font-semibold');
                                }
                                cb.checked = false;
                            }
                        });
                        selectAll.checked = false;
                        toggleActionButtons();
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Gagal update notifikasi.');
                });
        });

        // Fungsi tombol Unread
        btnUnread.addEventListener('click', () => {
            const selectedIds = [];
            getCheckboxes().forEach(cb => {
                if (cb.checked) {
                    selectedIds.push(cb.dataset.id);
                }
            });

            if (selectedIds.length === 0) return;

            showLoading();

            fetch("{{ route('notifikasi.unread') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        ids: selectedIds
                    })
                })
                .then(response => response.json())
                .then(data => {
                    hideLoading();
                    if (data.status === 'success') {
                        getCheckboxes().forEach(cb => {
                            if (cb.checked) {
                                const messageDiv = cb.closest('div.flex.items-center.py-3.border-b');
                                if (messageDiv) {
                                    messageDiv.classList.remove('bg-gray-100');
                                    messageDiv.classList.add('bg-white');
                                    const messageContent = messageDiv.querySelector('.message-content');
                                    const senderName = messageDiv.querySelector('.sender-name');
                                    if (messageContent) messageContent.classList.add('font-semibold');
                                    if (senderName) senderName.classList.add('font-semibold');
                                }
                                cb.checked = false;
                            }
                        });
                        selectAll.checked = false;
                        toggleActionButtons();
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Gagal update notifikasi.');
                });
        });

        // Fungsi tombol Delete
        btnDelete.addEventListener('click', () => {
            const selectedIds = [];
            getCheckboxes().forEach(cb => {
                if (cb.checked) {
                    selectedIds.push(cb.dataset.id);
                }
            });

            if (selectedIds.length === 0) return;

            if (!confirm('Apakah kamu yakin ingin menghapus pesan yang terpilih?')) return;

            showLoading();

            fetch("{{ route('notifikasi.delete') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        ids: selectedIds
                    })
                })
                .then(response => response.json())
                .then(data => {
                    hideLoading();
                    if (data.status === 'success') {
                        getCheckboxes().forEach(cb => {
                            if (cb.checked) {
                                const messageDiv = cb.closest('div.flex.items-center.py-3.border-b');
                                if (messageDiv) {
                                    messageDiv.remove();
                                }
                            }
                        });
                        selectAll.checked = false;
                        toggleActionButtons();
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Gagal menghapus notifikasi.');
                });
        });
    </script>

    <style>
        .read {
            /* bisa kosong, karena styling di class html */
        }
    </style>


</x-layout>
