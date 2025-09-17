<x-layout :title="$title">
    @if (Session::has('success'))
        <div class="flex justify-center">
            <div id="toast-success"
                class="absolute top-[4.5rem] z-10 flex items-center w-full max-w-lg p-4 mb-4 text-gray-500 bg-white rounded-lg shadow-sm dark:text-gray-400 dark:bg-gray-800"
                role="alert">
                <div
                    class="inline-flex items-center justify-center shrink-0 w-8 h-8 text-green-500 bg-green-100 rounded-lg dark:bg-green-800 dark:text-green-200">
                    <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                        viewBox="0 0 20 20">
                        <path
                            d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 8.207-4 4a1 1 0 0 1-1.414 0l-2-2a1 1 0 0 1 1.414-1.414L9 10.586l3.293-3.293a1 1 0 0 1 1.414 1.414Z" />
                    </svg>
                    <span class="sr-only">Check icon</span>
                </div>
                <div class="ms-3 text-sm font-normal">{{ Session::get('success') }}</div>
                <button type="button"
                    class="ms-auto -mx-1.5 -my-1.5 bg-white text-gray-400 hover:text-gray-900 rounded-lg focus:ring-2 focus:ring-gray-300 p-1.5 hover:bg-gray-100 inline-flex items-center justify-center h-8 w-8 dark:text-gray-500 dark:hover:text-white dark:bg-gray-800 dark:hover:bg-gray-700"
                    data-dismiss-target="#toast-success" aria-label="Close">
                    <span class="sr-only">Close</span>
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                    </svg>
                </button>
            </div>
        </div>
        <div>
            <script>
                setTimeout(() => {
                    const toast = document.getElementById('toast-success');
                    if (toast) {
                        toast.classList.add('opacity-0', 'transition-opacity', 'duration-500');

                        setTimeout(() => {
                            toast.remove();
                        }, 500);
                    }
                }, 3000);
            </script>
        </div>
    @elseif(Session::has('error'))
        <div class="flex justify-center">
            <div id="toast-error"
                class="absolute top-[4.5rem] z-10 flex items-center w-full max-w-lg p-4 mb-4 text-gray-500 bg-white rounded-lg shadow-sm dark:text-gray-400 dark:bg-gray-800"
                role="alert">
                <div class="inline-flex items-center justify-center w-8 h-8 bg-red-100 rounded-lg dark:bg-red-800">
                    <div class="w-5 h-5 bg-red-500 rounded-full flex items-center justify-center">
                        <svg class="text-white w-3.5 h-3.5 mx-auto" aria-hidden="true" fill="currentColor"
                            viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                d="M10 4a1 1 0 011 1v6a1 1 0 11-2 0V5a1 1 0 011-1zm0 10a1.5 1.5 0 100 3 1.5 1.5 0 000-3z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    <span class="sr-only">Delete icon</span>
                </div>
                <div class="ms-3 text-sm font-normal">{{ Session::get('error') }}</div>
                <button type="button"
                    class="ms-auto -mx-1.5 -my-1.5 bg-white text-gray-400 hover:text-gray-900 rounded-lg focus:ring-2 focus:ring-gray-300 p-1.5 hover:bg-gray-100 inline-flex items-center justify-center h-8 w-8 dark:text-gray-500 dark:hover:text-white dark:bg-gray-800 dark:hover:bg-gray-700"
                    data-dismiss-target="#toast-error" aria-label="Close">
                    <span class="sr-only">Close</span>
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                    </svg>
                </button>
            </div>
            <div>
                <script>
                    setTimeout(() => {
                        const toast = document.getElementById('toast-error');
                        if (toast) {
                            toast.classList.add('opacity-0', 'transition-opacity', 'duration-500');

                            setTimeout(() => {
                                toast.remove();
                            }, 500);
                        }
                    }, 3000);
                </script>
            </div>
        </div>
    @endif
    @php
        $isAdmin = Auth::user()->is_admin;
    @endphp
    @if (!$isAdmin)
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
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z">
                                        </path>
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
                                    {{ $notif->pesan }}</a>
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
    @else
        <section class="p-5 antialiased">
            <div class="mx-auto px-4">
                <div class="bg-white dark:bg-gray-800 relative border rounded-lg overflow-hidden">
                    <div class="flex flex-row items-center justify-between space-y-0 space-x-4 p-4">
                        <p class="font-semibold whitespace-nowrap">
                            Broadcast Pesan
                        </p>
                        <div class="w-auto flex flex-row items-center space-x-3 flex-shrink-0">
                            <a href="/pesan/admin/create"
                                class="flex items-center text-white bg-emerald-700 hover:bg-emerald-800 focus:ring-4 focus:ring-emerald-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-emerald-600 dark:hover:bg-emerald-700 focus:outline-none dark:focus:ring-emerald-800">
                                <svg class="h-3.5 w-3.5 mr-2" fill="currentColor" viewbox="0 0 20 20"
                                    xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                    <path clip-rule="evenodd" fill-rule="evenodd"
                                        d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" />
                                </svg>
                                Tambah Pesan
                            </a>
                        </div>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                            <thead
                                class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th scope="col" class="px-4 py-4">Nomor</th>
                                    <th scope="col" class="px-4 py-3 whitespace-nowrap">Nama Pesan</th>
                                    <th scope="col" class="px-4 py-3">Isi pesan</th>
                                    <th scope="col" class="px-4 py-3"></th>
                                    <th scope="col" class="px-4 py-3"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($notifs as $notif)
                                    <tr class="border-b dark:border-gray-700">
                                        <th scope="row"
                                            class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                            {{ $loop->iteration }}
                                        </th>
                                        <td class="px-4 py-3">{{ $notif->nama }}</td>
                                        <td class="px-4 py-3 whitespace-nowrap">{{ Str::limit($notif->pesan, 15) }}
                                        </td>
                                        <td class="px-4 py-3 max-w-[12rem] truncate">
                                            {{ $notif->created_at->diffForHumans() }}</td>
                                        <td class="px-4 py-3 flex items-center justify-end">
                                            <button id="{{ $notif->slug }}-dropdown-button"
                                                data-dropdown-toggle="{{ $notif->slug }}-dropdown"
                                                class="inline-flex items-center text-sm font-medium hover:bg-gray-100 dark:hover:bg-gray-700 p-1.5 dark:hover-bg-gray-800 text-center text-gray-500 hover:text-gray-800 rounded-lg focus:outline-none dark:text-gray-400 dark:hover:text-gray-100"
                                                type="button">
                                                <svg class="w-5 h-5" aria-hidden="true" fill="currentColor"
                                                    viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                    <path
                                                        d="M6 10a2 2 0 11-4 0 2 2 0 014 0zM12 10a2 2 0 11-4 0 2 2 0 014 0zM16 12a2 2 0 100-4 2 2 0 000 4z" />
                                                </svg>
                                            </button>
                                            <div id="{{ $notif->slug }}-dropdown"
                                                class="hidden z-10 w-44 bg-white rounded divide-y divide-gray-100 shadow dark:bg-gray-700 dark:divide-gray-600">
                                                <ul class="py-1 text-sm"
                                                    aria-labelledby="{{ $notif->slug }}-dropdown-button">
                                                    <li>
                                                        <a href="/pesan/{{ $notif->slug }}/edit"
                                                            class="flex w-full items-center py-2 px-4 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white text-gray-700 dark:text-gray-200">
                                                            <svg class="w-4 h-4 mr-2"
                                                                xmlns="http://www.w3.org/2000/svg" viewbox="0 0 20 20"
                                                                fill="currentColor" aria-hidden="true">
                                                                <path
                                                                    d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z" />
                                                                <path fill-rule="evenodd" clip-rule="evenodd"
                                                                    d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" />
                                                            </svg>
                                                            Edit
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="/pesan/{{ $notif->slug }}"
                                                            class="flex w-full items-center py-2 px-4 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white text-gray-700 dark:text-gray-200">
                                                            <svg class="w-4 h-4 mr-2"
                                                                xmlns="http://www.w3.org/2000/svg" viewbox="0 0 20 20"
                                                                fill="currentColor" aria-hidden="true">
                                                                <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                                                <path fill-rule="evenodd" clip-rule="evenodd"
                                                                    d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" />
                                                            </svg>
                                                            Show pesan
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <button type="button"
                                                            data-modal-target="deleteModal-{{ $notif->id }}"
                                                            data-modal-toggle="deleteModal-{{ $notif->id }}"
                                                            class="cursor-pointer flex w-full items-center py-2 px-4 hover:bg-gray-100 dark:hover:bg-gray-600 text-red-500 dark:hover:text-red-400">
                                                            <svg class="w-4 h-4 mr-2" viewbox="0 0 14 15"
                                                                fill="none" xmlns="http://www.w3.org/2000/svg"
                                                                aria-hidden="true">
                                                                <path fill-rule="evenodd" clip-rule="evenodd"
                                                                    fill="currentColor"
                                                                    d="M6.09922 0.300781C5.93212 0.30087 5.76835 0.347476 5.62625 0.435378C5.48414 0.523281 5.36931 0.649009 5.29462 0.798481L4.64302 2.10078H1.59922C1.36052 2.10078 1.13161 2.1956 0.962823 2.36439C0.79404 2.53317 0.699219 2.76209 0.699219 3.00078C0.699219 3.23948 0.79404 3.46839 0.962823 3.63718C1.13161 3.80596 1.36052 3.90078 1.59922 3.90078V12.9008C1.59922 13.3782 1.78886 13.836 2.12643 14.1736C2.46399 14.5111 2.92183 14.7008 3.39922 14.7008H10.5992C11.0766 14.7008 11.5344 14.5111 11.872 14.1736C12.2096 13.836 12.3992 13.3782 12.3992 12.9008V3.90078C12.6379 3.90078 12.8668 3.80596 13.0356 3.63718C13.2044 3.46839 13.2992 3.23948 13.2992 3.00078C13.2992 2.76209 13.2044 2.53317 13.0356 2.36439C12.8668 2.1956 12.6379 2.10078 12.3992 2.10078H9.35542L8.70382 0.798481C8.62913 0.649009 8.5143 0.523281 8.37219 0.435378C8.23009 0.347476 8.06631 0.30087 7.89922 0.300781H6.09922ZM4.29922 5.70078C4.29922 5.46209 4.39404 5.23317 4.56282 5.06439C4.73161 4.8956 4.96052 4.80078 5.19922 4.80078C5.43791 4.80078 5.66683 4.8956 5.83561 5.06439C6.0044 5.23317 6.09922 5.46209 6.09922 5.70078V11.1008C6.09922 11.3395 6.0044 11.5684 5.83561 11.7372C5.66683 11.906 5.43791 12.0008 5.19922 12.0008C4.96052 12.0008 4.73161 11.906 4.56282 11.7372C4.39404 11.5684 4.29922 11.3395 4.29922 11.1008V5.70078ZM8.79922 4.80078C8.56052 4.80078 8.33161 4.8956 8.16282 5.06439C7.99404 5.23317 7.89922 5.46209 7.89922 5.70078V11.1008C7.89922 11.3395 7.99404 11.5684 8.16282 11.7372C8.33161 11.906 8.56052 12.0008 8.79922 12.0008C9.03791 12.0008 9.26683 11.906 9.43561 11.7372C9.6044 11.5684 9.69922 11.3395 9.69922 11.1008V5.70078C9.69922 5.46209 9.6044 5.23317 9.43561 5.06439C9.26683 4.8956 9.03791 4.80078 8.79922 4.80078Z" />
                                                            </svg>
                                                            Hapus
                                                        </button>
                                                    </li>
                                                </ul>
                                            </div>
                                            <!-- Delete modal -->
                                            <div id="deleteModal-{{ $notif->id }}" tabindex="-1"
                                                aria-hidden="true"
                                                class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
                                                <div class="relative p-4 w-full max-w-md max-h-full">
                                                    <!-- Modal content -->
                                                    <div
                                                        class="relative p-4 text-center bg-white rounded-lg shadow dark:bg-gray-800 sm:p-5">
                                                        <button type="button"
                                                            class="text-gray-400 absolute top-2.5 right-2.5 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white"
                                                            data-modal-toggle="deleteModal-{{ $notif->id }}">
                                                            <svg aria-hidden="true" class="w-5 h-5"
                                                                fill="currentColor" viewbox="0 0 20 20"
                                                                xmlns="http://www.w3.org/2000/svg">
                                                                <path fill-rule="evenodd"
                                                                    d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                                                    clip-rule="evenodd" />
                                                            </svg>
                                                            <span class="sr-only">Tutup modal</span>
                                                        </button>
                                                        <svg class="text-gray-400 dark:text-gray-500 w-11 h-11 mb-3.5 mx-auto"
                                                            aria-hidden="true" fill="currentColor"
                                                            viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                            <path fill-rule="evenodd"
                                                                d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                                                                clip-rule="evenodd" />
                                                        </svg>
                                                        <p class="mb-4 text-gray-500 dark:text-gray-300">
                                                            Apakah Anda yakin ingin menghapus pesan ini?</p>
                                                        <div class="flex justify-center items-center space-x-4">
                                                            <button
                                                                data-modal-toggle="deleteModal-{{ $notif->id }}"
                                                                type="button"
                                                                class="cursor-pointer py-2 px-3 text-sm font-medium text-gray-500 bg-white rounded-lg border border-gray-200 hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-primary-300 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600">Tidak,
                                                                batal</button>
                                                            <form action="/pesan/{{ $notif->slug }}" method="POST">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit"
                                                                    class="cursor-pointer py-2 px-3 text-sm font-medium text-center text-white bg-red-600 rounded-lg hover:bg-red-700 focus:ring-4 focus:outline-none focus:ring-red-300 dark:bg-red-500 dark:hover:bg-red-600 dark:focus:ring-red-900">Ya,
                                                                    Saya yakin</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-gray-500 py-50">
                                            Anda belum ngebroadcast pesan apapun...
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    {{ $notifs->links() }}
                </div>
            </div>
        </section>
    @endif

</x-layout>
