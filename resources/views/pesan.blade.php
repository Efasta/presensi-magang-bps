<x-layout :title="$title">
    <section class="bg-white dark:bg-gray-900 antialiased">
        <div class="max-w-screen-xl px-4 mt-6 mb-20 sm:mb-20 mx-auto lg:px-6">
            <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-6">
                <div class="border-b-2 border-gray-400 dark:border-gray-700 pb-4 mb-4">

                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white text-center mb-4">
                        Inbox
                    </h2>

                    {{-- ✅ Pindahkan tombol aksi ke kanan atas --}}
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between px-5">
                        {{-- Checkbox Select All --}}
                        <div class="flex items-center mb-2 md:mb-0">
                            <input type="checkbox" id="select_all" class="mr-2 cursor-pointer" />
                            <label for="select_all" class="text-gray-700 dark:text-gray-500 cursor-pointer select-none">
                                Select All
                            </label>
                        </div>

                        {{-- Tombol aksi di kanan --}}
                        <div id="action_buttons" class="flex gap-2 hidden">
                            <button type="button" onclick="submitBulk('read')"
                                class="cursor-pointer px-3 py-1 text-sm text-gray-800 bg-gray-100 border border-gray-300 rounded hover:bg-gray-200 flex items-center gap-1">
                                <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                    fill="currentColor" aria-hidden="true">
                                    <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                                Read
                            </button>

                            <button type="button" onclick="submitBulk('unread')"
                                class="cursor-pointer px-3 py-1 text-sm text-gray-800 bg-gray-100 border border-gray-300 rounded hover:bg-gray-200 flex items-center gap-1">
                                <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                    fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M10 5C6 5 3.2 7.6 2 10c1.2 2.4 4 5 8 5s6.8-2.6 8-5c-.8-1.6-3.6-5-8-5zm0 8a3 3 0 100-6 3 3 0 000 6z" />
                                    <path d="M10 9a1 1 0 110 2 1 1 0 010-2z" />
                                    <path d="M3 3l14 14" stroke="currentColor" stroke-width="1.5"
                                        stroke-linecap="round" />
                                </svg>
                                Unread
                            </button>

                            <button type="button" onclick="submitBulk('delete')"
                                class="cursor-pointer px-3 py-1 text-sm text-gray-800 bg-gray-100 border border-gray-300 rounded hover:bg-gray-200 flex items-center gap-1">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 20 20"
                                    fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd"
                                        d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                                        clip-rule="evenodd" />
                                </svg>
                                Delete
                            </button>
                        </div>
                    </div>
                </div>

                {{-- 💡 Form utama --}}
                <form id="bulkActionForm" action="{{ route('notifikasi.bulk') }}" method="POST">
                    @csrf
                    <input type="hidden" name="action" id="bulkActionType">

                    {{-- List pesan --}}
                    <div class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse ($notifs as $notif)
                            <div
                                class="flex items-center py-3 px-5 border-b border-gray-300 {{ $notif->is_read ? 'bg-gray-100' : 'bg-white' }}">
                                <input type="checkbox" name="ids[]" value="{{ $notif->id }}"
                                    class="mr-4 message-checkbox" />
                                <p
                                    class="w-50 text-sm text-gray-600 dark:text-gray-500 shrink-0 sender-name {{ $notif->is_read ? '' : 'font-semibold' }}">
                                    {{ $notif->nama }}
                                </p>
                                <a href="/pesan/{{ $notif->slug }}"
                                    class="message-content flex-grow text-sm text-gray-800 dark:text-gray-400 truncate hover:underline cursor-pointer {{ $notif->is_read ? '' : 'font-semibold' }}">
                                    {!! strip_tags($notif->pesan, '<b><i><u><strong><em>') !!}
                                </a>
                                <p class="w-36 text-xs text-gray-500 dark:text-gray-600 text-right ml-4">
                                    {{ $notif->created_at->format('M d, H:i') }}
                                </p>
                            </div>
                        @empty
                            <p class="text-center text-gray-500 py-50">
                                Anda belum menerima pesan apapun...
                            </p>
                        @endforelse
                    </div>
                </form>

            </div>

            @if ($notifs->hasPages())
                {{ $notifs->onEachSide(0)->links('components.pagination.custom-pesan') }}
            @endif
        </div>
    </section>


    <script>
        const selectAll = document.getElementById('select_all');
        const actionButtons = document.getElementById('action_buttons');
        const checkboxes = document.querySelectorAll('.message-checkbox');

        selectAll.addEventListener('change', function() {
            checkboxes.forEach(cb => cb.checked = this.checked);
            toggleButtons();
        });

        checkboxes.forEach(cb => {
            cb.addEventListener('change', toggleButtons);
        });

        function toggleButtons() {
            const anyChecked = Array.from(checkboxes).some(cb => cb.checked);
            if (anyChecked) {
                actionButtons.classList.remove('hidden');
            } else {
                actionButtons.classList.add('hidden');
            }
        }

        function submitBulk(action) {
            if (action === 'delete' && !confirm('Apakah kamu yakin ingin menghapus pesan terpilih?')) return;
            document.getElementById('bulkActionType').value = action;
            document.getElementById('bulkActionForm').submit();
        }
    </script>

    <style>
        .read {
            /* bisa kosong, karena styling di class html */
        }
    </style>
</x-layout>
