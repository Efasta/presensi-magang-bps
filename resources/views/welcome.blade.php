<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Selamat Datang!</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    @vite('resources/css/app.css')
</head>

<body class="bg-white font-[Inter] text-gray-800 min-h-screen flex flex-col">

    <!-- Header -->
    <header class="w-full py-4 px-8 flex items-center justify-between shadow-sm">
        <div class="flex items-center space-x-3">
            <a href="/" class="flex items-center space-x-3">
                <x-application-logo class="h-10 w-auto fill-current text-gray-500" />
                <div class="text-sm sm:text-lg font-semibold text-gray-900">
                    BPS Provinsi Sulawesi Selatan
                </div>
            </a>
        </div>
        <nav class="flex items-center space-x-4">
            @if (Route::has('login'))
                @auth
                    <a href="{{ url('/dashboard') }}"
                        class="px-5 py-2 border border-gray-300 rounded-md text-sm text-gray-700 hover:bg-gray-100">
                        Dashboard
                    </a>
                @else
                    <a href="{{ route('register') }}"
                        class="px-5 py-2 border border-emerald-600 text-emerald-600 rounded-md text-sm font-semibold hover:bg-emerald-50 transition">
                        Daftar
                    </a>
                    <a href="{{ route('login') }}"
                        class="px-5 py-2 bg-emerald-600 text-white rounded-md text-sm font-semibold hover:bg-emerald-700 transition">
                        Masuk
                    </a>
                @endauth
            @endif
        </nav>
    </header>


    <!-- Hero Section -->
    <section class="flex-grow flex items-center justify-center px-6 py-12">
        <div class="flex flex-col lg:flex-row items-center gap-10 max-w-6xl w-full">
            <!-- Teks Selamat Datang -->
            <div class="lg:text-left flex-1">
                <h1 class="text-4xl sm:text-5xl font-bold leading-tight mb-4">
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-green-500 to-amber-500">
                        Halo, Selamat Datang!
                    </span>
                </h1>
                <h2 class="text-2xl sm:text-3xl font-semibold text-gray-800 mb-6">
                    Ini adalah sistem absensi online untuk anak magang di Badan Pusat Statistik Provinsi Sulawesi Selatan
                </h2>
                <p class="text-gray-600 text-base sm:text-lg mb-8">
                    Daftar sekarang untuk mengisi data kehadiran anda pada lembaga pemerintah ini.
                </p>
                <a href="/register"
                    class="inline-block px-6 py-3 bg-emerald-600 text-white rounded-md font-medium text-sm hover:bg-emerald-700 transition">
                    Daftar Sekarang
                </a>
            </div>

            <!-- Gambar -->
            <div class="flex-1 h-full flex items-center justify-center">
                <img src="{{ asset('img/Gedung BPS.jpeg') }}" alt="Gedung BPS"
                    class="w-full h-[750px] object-cover rounded-lg shadow-lg">
            </div>
        </div>
    </section>


</body>

</html>
