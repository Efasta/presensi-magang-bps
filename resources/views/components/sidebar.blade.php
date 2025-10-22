<aside id="logo-sidebar"
    class="fixed top-0 left-0 z-40 w-64 h-screen pt-20 transition-transform -translate-x-full bg-white border-r border-gray-200 sm:translate-x-0 dark:border-gray-700"
    aria-label="Sidebar">
    <div class="h-full px-3 pb-4 overflow-y-auto bg-white">

        <p id="today-date" class="text-center font-semibold text-white pt-1.5 bg-blue-600 rounded-t">
        </p>
        <div id="sidebar-clock" class="text-center text-white bg-blue-600 rounded-b pb-1.5 mb-4 text-xl font-semibold">

        </div>

        <ul class="space-y-2 font-medium">
            <x-side-link href="/dashboard" :current="request()->is('dashboard')">
                <svg class="shrink-0 w-5 h-5 transition duration-75" aria-hidden="true"
                    xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 22 21">
                    <path
                        d="M16.975 11H10V4.025a1 1 0 0 0-1.066-.998 8.5 8.5 0 1 0 9.039 9.039.999.999 0 0 0-1-1.066h.002Z" />
                    <path
                        d="M12.5 0c-.157 0-.311.01-.565.027A1 1 0 0 0 11 1.02V10h8.975a1 1 0 0 0 1-.935c.013-.188.028-.374.028-.565A8.51 8.51 0 0 0 12.5 0Z" />
                </svg>
                <span class="ms-3">Dashboard</span></x-side-link>
            <x-side-link href="/absensi" :current="request()->is('absensi')">
                <svg class="shrink-0 w-5 h-5 transition duration-75" aria-hidden="true"
                    xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                    <path
                        d="M18 2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2ZM2 18V7h6.7l.4-.409A4.309 4.309 0 0 1 15.753 7H18v11H2Z" />
                    <path
                        d="M8.139 10.411 5.289 13.3A1 1 0 0 0 5 14v2a1 1 0 0 0 1 1h2a1 1 0 0 0 .7-.288l2.886-2.851-3.447-3.45ZM14 8a2.463 2.463 0 0 0-3.484 0l-.971.983 3.468 3.468.987-.971A2.463 2.463 0 0 0 14 8Z" />
                </svg>
                <span class="flex-1 ms-3 whitespace-nowrap">Absensi</span>
            </x-side-link>
            <!-- DROPDOWN: Manajemen Users -->
            <li x-data="{
                open: JSON.parse(localStorage.getItem('usersMenuOpen') ?? 'true'),
                init: false,
                toggle() {
                    this.open = !this.open;
                    localStorage.setItem('usersMenuOpen', JSON.stringify(this.open));
                }
            }" x-init="init = true">
                <button @click="toggle"
                    class="flex items-center w-full p-2 text-gray-600 hover:text-gray-900 focus:text-gray-900 rounded-lg hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700 transition duration-75">
                    <svg class="shrink-0 w-6 h-6 transition duration-75" viewBox="0 0 512 512"
                        xmlns="http://www.w3.org/2000/svg" fill="currentColor">
                        <path d="M157.604,321.598c7.26-2.232,10.041-6.696,10.6-10.046c-0.559-4.469-3.143-6.279-3.986-14.404
                                    c-0.986-9.457,6.91-32.082,9.258-36.119c-0.32-0.772-0.65-1.454-0.965-2.247c-11.002-6.98-22.209-19.602-27.359-42.416
                                c-2.754-12.197-0.476-24.661,6.121-35.287c0,0-7.463-52.071,3.047-86.079c-9.818-4.726-20.51-3.93-35.164-2.466
                                c-11.246,1.126-12.842,3.516-21.48,2.263c-9.899-1.439-17.932-4.444-20.348-5.654c-1.392-0.694-14.449,10.89-18.084,20.35
                                c-11.531,29.967-8.435,50.512-5.5,66.057c-0.098,1.592-0.224,3.178-0.224,4.787l2.68,11.386c0.01,0.12,0,0.232,0.004,0.346
                                c-5.842,5.24-9.363,12.815-7.504,21.049c3.828,16.934,12.07,23.802,20.186,26.777c5.383,15.186,10.606,24.775,16.701,31.222
                                c1.541,7.027,2.902,16.57,1.916,26.032C83.389,336.78,0,315.904,0,385.481c0,9.112,25.951,23.978,88.818,28.259
                                c-0.184-1.342-0.31-2.695-0.31-4.078C88.508,347.268,129.068,330.379,157.604,321.598z" />
                        <path d="M424.5,297.148c-0.986-9.457,0.371-18.995,1.912-26.011c6.106-6.458,11.328-16.052,16.713-31.246
                                c8.113-2.977,16.35-9.848,20.174-26.774c1.77-7.796-1.293-15.006-6.59-20.2c3.838-12.864,18.93-72.468-26.398-84.556
                                c-15.074-18.839-28.258-18.087-50.871-15.827c-11.246,1.126-12.844,3.516-21.477,2.263c-1.89-0.275-3.682-0.618-5.41-0.984
                                c1.658,2.26,3.238,4.596,4.637,7.092c15.131,27.033,11.135,61.27,6.381,82.182c5.67,10.21,7.525,21.944,4.963,33.285
                                c-5.15,22.8-16.352,35.419-27.348,42.4c-0.551,1.383-2.172,4.214,0.06,7.006c2.039,3.305,2.404,2.99,4.627,5.338
                                c1.539,7.027,2.898,16.57,1.91,26.032c-0.812,7.85-14.352,14.404-10.533,17.576c3.756,1.581,8.113,3.234,13,5.028
                                c28.025,10.29,74.928,27.516,74.928,89.91c0,1.342-0.117,2.659-0.291,3.96C486.524,409.195,512,394.511,512,385.481
                                C512,315.904,428.613,336.78,424.5,297.148z" />
                        <path d="M301.004,307.957c-1.135-10.885,0.432-21.867,2.201-29.956c7.027-7.423,13.047-18.476,19.244-35.968
                                c9.34-3.427,18.826-11.335,23.23-30.826c2.028-8.976-1.494-17.276-7.586-23.256c4.412-14.81,21.785-83.437-30.398-97.353
                                c-17.354-21.692-32.539-20.825-58.57-18.222c-12.951,1.294-14.791,4.048-24.731,2.603c-11.4-1.657-20.646-5.117-23.428-6.508
                                c-1.602-0.803-16.637,12.538-20.826,23.428c-13.27,34.5-9.705,58.159-6.33,76.056c-0.111,1.833-0.264,3.658-0.264,5.511
                                l3.092,13.11c0.01,0.135,0,0.264,0.004,0.399c-6.726,6.03-10.777,14.752-8.636,24.232c4.402,19.498,13.894,27.404,23.238,30.828
                                c6.199,17.485,12.207,28.533,19.231,35.956c1.773,8.084,3.34,19.076,2.205,29.966c-4.738,45.626-100.744,21.593-100.744,101.706
                                c0,12.355,41.4,33.902,144.906,33.902c103.506,0,144.906-21.547,144.906-33.902C401.748,329.549,305.742,353.583,301.004,307.957z
                                M240.039,430.304l-26.276-106.728l32.324,13.453l-1.738,15.619l5.135-0.112L240.039,430.304z M276.209,430.304l-9.447-77.768
                                l5.135,0.112l-1.738-15.619l32.324-13.453L276.209,430.304z" />
                    </svg>

                    <span class="flex-1 ms-3 text-left whitespace-nowrap">Manajemen Users</span>
                    <svg class="w-3 h-3 transition-transform duration-200" :class="open ? 'rotate-180' : ''"
                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>

                <ul x-show="open" x-transition class="space-y-2 pl-5">
                    <!-- Users Aktif -->
                    <li>
                        <x-side-link href="/users" :current="request()->is('users')">
                            <svg class="shrink-0 w-6 h-6 transition duration-75" height="800px" width="800px"
                                viewBox="0 0 512 512" xmlns="http://www.w3.org/2000/svg">
                                <path fill="currentColor" d="M364.032,355.035c-3.862-1.446-8.072-3.436-12.35-5.794l-71.57,98.935l-5.09-64.814h-38.033l-5.091,64.814
                                    l-71.569-98.935c-4.408,2.466-8.656,4.487-12.361,5.794c-37.478,13.193-129.549,51.136-123.607,122.21
                                    C25.787,494.301,119.582,512,256.006,512c136.413,0,230.208-17.699,231.634-34.755
                                    C493.583,406.102,401.273,368.961,364.032,355.035z" />
                                <path fill="currentColor" d="M171.501,208.271c5.21,29.516,13.966,55.554,25.494,68.38c0,15.382,0,26.604,0,35.587
                                        c0,0.902-0.158,1.852-0.416,2.833l40.41,19.462v28.545h38.033v-28.545l40.39-19.452c-0.258-0.981-0.416-1.932-0.416-2.843
                                        c0-8.983,0-20.205,0-35.587c11.548-12.826,20.304-38.864,25.514-68.38c12.143-4.338,19.096-11.281,27.762-41.658
                                        c9.231-32.358-13.876-31.258-13.876-31.258c18.69-61.873-5.922-120.022-47.124-115.753c-28.426-49.73-123.627,11.36-153.48,7.102
                                        c0,17.055,7.112,29.842,7.112,29.842c-10.379,19.69-6.378,58.951-3.446,78.809c-1.704-0.03-22.602,0.188-13.728,31.258
                                        C152.405,196.99,159.338,203.934,171.501,208.271z" />
                            </svg>
                            <span class="ms-3">Users Aktif</span>
                        </x-side-link>
                    </li>

                    <!-- Alumni Magang -->
                    <li>
                        <x-side-link href="/alumni" :current="request()->is('alumni')">
                            <svg class="shrink-0 w-6 h-6 transition duration-75" fill="currentColor"
                                viewBox="0 0 512 512" xmlns="http://www.w3.org/2000/svg">
                                <path fill="currentColor" d="M366.042,378.266c-26.458-9.72-49.309-18.113-51.793-42.026c-1.149-11.024-0.214-23.982,2.702-37.507
                                c9.144-9.798,16.72-23.936,24.484-45.691c15.458-5.955,25.31-19.192,30.109-40.442c2.461-10.885-1.058-22.073-9.655-30.807
                                c0.773-13.206,0.095-13.928-0.402-14.456l-0.542-0.536H151.497v14.914c-9.897,9.115-13.61,19.503-11.038,30.885
                                c4.794,21.242,14.648,34.48,30.12,40.442c7.762,21.754,15.332,35.885,24.464,45.675c2.06,9.518,4.158,23.61,2.71,37.523
                                c-2.484,23.913-25.336,32.306-51.795,42.026c-36.32,13.338-77.484,28.462-77.484,88.641C68.474,485.634,126.653,512,256,512
                                c129.347,0,187.526-26.366,187.526-45.093C443.526,406.729,402.362,391.605,366.042,378.266z M233.908,484.578L203.021,359.12
                                l37.47,15.598l-2.302,20.66l6.572-0.148L233.908,484.578z M277.101,395.378l-2.302-20.66l37.47-15.598l-30.887,125.458
                                l-10.854-89.348L277.101,395.378z" />
                                <path fill="currentColor" d="M91.083,82.779l54.864,24.13v36.397h222.66v-36.397l22.395-9.852v51.234c-4.75,0.753-8.389,4.728-8.389,9.495
                                c0,4.146,2.741,7.74,6.704,9.053l-6.378,40.217c-0.421,2.663,0.34,5.357,2.081,7.392c1.739,2.042,4.28,3.214,6.972,3.214h16.792
                                c2.692,0,5.233-1.172,6.968-3.214c1.745-2.034,2.506-4.728,2.085-7.392l-6.374-40.217c3.969-1.312,6.714-4.907,6.714-9.053
                                c0-4.767-3.643-8.742-8.397-9.495V88.804l13.686-6.017c2.696-1.172,4.439-3.789,4.439-6.654c0-2.85-1.739-5.458-4.433-6.646
                                L272.931,3.284C267.987,1.102,262.72,0,257.273,0c-5.446,0-10.712,1.102-15.652,3.284L91.081,69.487
                                c-2.692,1.188-4.431,3.796-4.431,6.646C86.649,79.006,88.392,81.614,91.083,82.779z" />
                            </svg>
                            <span class="ms-3">Alumni Magang</span>
                        </x-side-link>
                    </li>
                </ul>
            </li>
            <x-side-link href="/fungsi" :current="request()->is('fungsi')">
                <svg class="shrink-0 w-5 h-5 transition duration-75" aria-hidden="true"
                    xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                    <path
                        d="M19.728 10.686c-2.38 2.256-6.153 3.381-9.875 3.381-3.722 0-7.4-1.126-9.571-3.371L0 10.437V18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-7.6l-.272.286Z" />
                    <path
                        d="m.135 7.847 1.542 1.417c3.6 3.712 12.747 3.7 16.635.01L19.605 7.9A.98.98 0 0 1 20 7.652V6a2 2 0 0 0-2-2h-3V3a3 3 0 0 0-3-3H8a3 3 0 0 0-3 3v1H2a2 2 0 0 0-2 2v1.765c.047.024.092.051.135.082ZM10 10.25a1.25 1.25 0 1 1 0-2.5 1.25 1.25 0 0 1 0 2.5ZM7 3a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v1H7V3Z" />
                </svg>
                <span class="flex-1 ms-3 whitespace-nowrap">Fungsi</span>
            </x-side-link>

            <!-- DROPDOWN: Inbox -->
            <li x-data="{ open: JSON.parse(localStorage.getItem('inboxMenuOpen') ?? 'true') }" x-init="$watch('open', v => localStorage.setItem('inboxMenuOpen', JSON.stringify(v)))">
                <button @click="open = !open"
                    class="flex items-center w-full p-2 text-gray-600 hover:text-gray-900 rounded-lg hover:bg-gray-100 transition duration-75">
                    <svg class="w-5 h-5" aria-hidden="true"
                        xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 16">
                        <path
                            d="M19 0H1a1 1 0 0 0-1 1v2a1 1 0 0 0 1 1h18a1 1 0 0 0 1-1V1a1 1 0 0 0-1-1ZM2 6v8a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V6H2Zm11 3a1 1 0 0 1-1 1H8a1 1 0 0 1-1-1V8a1 1 0 0 1 2 0h2a1 1 0 0 1 2 0v1Z" />
                    </svg>
                    <span class="flex-1 ms-3 text-left whitespace-nowrap">Inbox</span>
                    <svg class="w-3 h-3 transition-transform duration-200" :class="open ? 'rotate-180' : ''"
                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>

                <ul x-show="open" x-transition class="space-y-2 pl-5">
                    <li>
                        <x-side-link href="/pesan" :current="request()->is('pesan')">
                            <svg class="shrink-0 w-5 h-5 transition duration-75" xmlns="http://www.w3.org/2000/svg"
                                fill="currentColor" viewBox="0 0 20 18">
                                <path
                                    d="M18 0H2a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h3.546l3.2 3.659a1 1 0 0 0 1.506 0L13.454 14H18a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2Zm-8 10H5a1 1 0 0 1 0-2h5a1 1 0 1 1 0 2Zm5-4H5a1 1 0 0 1 0-2h10a1 1 0 1 1 0 2Z" />
                            </svg>
                            <span class="ms-3">Pesan</span>
                        </x-side-link>
                    </li>
                    <li>
                        <x-side-link href="/broadcast" :current="request()->is('broadcast')">
                            <svg class="shrink-0 w-5 h-5 transition duration-75" aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 18 19">
                                <path
                                    d="M15 1.943v12.114a1 1 0 0 1-1.581.814L8 11V5l5.419-3.871A1 1 0 0 1 15 1.943ZM7 4H2a2 2 0 0 0-2 2v4a2 2 0 0 0 2 2v5a2 2 0 0 0 2 2h1a2 2 0 0 0 2-2V4ZM4 17v-5h1v5H4ZM16 5.183v5.634a2.984 2.984 0 0 0 0-5.634Z" />
                            </svg>
                            <span class="ms-3">Broadcast</span>
                        </x-side-link>
                    </li>
                </ul>
            </li>

            <li>
                <form method="POST"action="/logout">
                    @csrf
                    <button type="submit"
                        class="w-full text-start flex items-center p-2 text-red-600 rounded-lg hover:bg-red-100 hover:text-red-800 transition duration-100 ease-in cursor-pointer">
                        <svg class="shrink-0 w-5 h-5 transition duration-75" aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 16 16">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                stroke-width="2" d="M4 8h11m0 0-4-4m4 4-4 4m-5 3H3a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h3" />
                        </svg>
                        <span class="flex-1 ms-3 whitespace-nowrap">Sign Out</span>
                    </button>
                </form>
            </li>
        </ul>
    </div>
</aside>

<!-- JavaScript untuk tanggal -->
<script>
    document.addEventListener("DOMContentLoaded", function() {
        function formatTanggalIndonesia(tanggal) {
            const options = {
                day: 'numeric',
                month: 'long',
                year: 'numeric'
            };
            return tanggal.toLocaleDateString('id-ID', options);
        }

        const today = new Date();
        const formattedDate = formatTanggalIndonesia(today);
        document.getElementById('today-date').textContent = `${formattedDate}`;
    });
</script>

{{-- JavaScript untuk jam --}}
<script>
    function updateSidebarClock() {
        const now = new Date();
        const hours = String(now.getHours()).padStart(2, '0');
        const minutes = String(now.getMinutes()).padStart(2, '0');
        const seconds = String(now.getSeconds()).padStart(2, '0');
        const time = `${hours}:${minutes}:${seconds}`;
        document.getElementById('sidebar-clock').textContent = time;
    }

    updateSidebarClock();
    setInterval(updateSidebarClock, 1000);
</script>
