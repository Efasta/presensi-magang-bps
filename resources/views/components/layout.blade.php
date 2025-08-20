<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $title }}</title>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    @vite('resources/css/app.css')
    <link rel="stylesheet" href="https://rsms.me/inter/inter.css" />
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
</head>

<body>
    <x-navbar />

    <x-sidebar />

    <div class=" sm:ml-64">
        <div class="pt-4">
            <x-header :title="$title" />
        </div>

        <main>
            <div class="rounded">
                {{ $slot }}
            </div>
        </main>

    </div>
    <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>

</body>

<footer class="border-t border-gray-200 sm:ml-64 fixed bottom-0 w-full bg-white z-50">
    <div class="max-w-screen-xl mx-auto px-4 py-6 text-right text-gray-500 text-sm font-light">
        &copy; {{ date('Y') }} BPS Provinsi Sulawesi Selatan. All rights reserved.
    </div>
</footer>

</html>
