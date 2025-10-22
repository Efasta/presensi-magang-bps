<x-layout :title="$title">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

    <div class="px-6" style="margin-top:0.75rem; margin-bottom:8.75rem;">
        <!-- Wrapper relative agar absolute bekerja -->
        <div class="relative w-full h-[661px]">
            <!-- Map -->
            <div id="map" class="w-full h-full rounded-lg border pointer-events-auto z-0"></div>

            <!-- Tombol Absen -->
            <div class="max-w-xs sm:max-w-sm bg-white shadow-lg py-4 px-[30px] rounded-lg flex flex-col space-y-2"
                style="position:absolute; bottom:1rem; left:1.5rem; z-index:30;">

                <p class="text-sm text-gray-600">
                    Jl. H. Bau No.6, Kunjung Mae, Kec. Mariso, Kota Makassar, Sulawesi Selatan 90125
                </p>

                <!-- Form absensi -->
                @if ($isAlumni)
                    <button type="button" class="bg-gray-400 text-white px-4 py-2 rounded cursor-not-allowed" disabled>
                        Alumni tidak dapat melakukan absensi
                    </button>
                @elseif ($isWeekend)
                    <button type="button" class="bg-gray-400 text-white px-4 py-2 rounded cursor-not-allowed" disabled>
                        Hari ini {{ $namaHari }}, absen tidak tersedia
                    </button>
                @elseif ($absensi && in_array($absensi->status_id, ['3', '2']))
                    <button type="button" class="bg-gray-400 text-white px-4 py-2 rounded cursor-not-allowed" disabled>
                        Kamu Sudah {{ $absensi->status->nama }} Hari Ini
                    </button>
                @elseif (!$absensi)
                    <form action="{{ url('/absensi') }}" method="POST">
                        @csrf
                        <input type="hidden" name="latitude" id="latInput">
                        <input type="hidden" name="longitude" id="lngInput">
                        <button id="btnAbsenMasuk" type="submit"
                            class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                            Absen Sekarang
                        </button>
                    </form>
                @elseif (!$absensi->jam_keluar || $absensi->jam_keluar === '-')
                    <form action="{{ url('/absensi/pulang') }}" method="POST">
                        @csrf
                        <input type="hidden" name="latitude" id="latInput">
                        <input type="hidden" name="longitude" id="lngInput">
                        <button id="btnAbsenPulang" type="submit"
                            class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">
                            Absen Pulang
                        </button>
                    </form>
                @else
                    <button type="button" class="bg-gray-400 text-white px-4 py-2 rounded cursor-not-allowed" disabled>
                        Sudah Absen Masuk & Pulang
                    </button>
                @endif
            </div>
        </div>
    </div>

    <style>
        @media (min-width: 640px) {
            .px-6 {
                margin-top: 2.125rem !important;
                margin-bottom: 0 !important;
            }
        }
    </style>

    <script>
        const kantorLat = -5.1488763012991425;
        const kantorLng = 119.41054079290649;
        const radius = 50; // meter
        let map;

        function initMap(userLat = null, userLng = null) {
            if (map) {
                map.remove(); // reset kalau map sudah ada
            }

            map = L.map('map').setView([kantorLat, kantorLng], 17);

            // Load OpenStreetMap tiles
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(map);

            // Marker Kantor
            L.marker([kantorLat, kantorLng]).addTo(map)
                .bindPopup('Lokasi Kantor')
                .openPopup();

            // Lingkaran radius kantor
            L.circle([kantorLat, kantorLng], {
                color: '#10B981',
                fillColor: '#10B981',
                fillOpacity: 0.2,
                radius: radius
            }).addTo(map);

            // Tampilkan marker user hanya kalau weekday
            @if (!$isWeekend)
                if (userLat && userLng) {
                    L.marker([userLat, userLng], {
                            title: 'Lokasi Anda'
                        })
                        .addTo(map)
                        .bindPopup('Lokasi Anda');
                    map.setView([userLat, userLng], 17);
                }
            @endif
        }

        function getUserLocation() {
            @if ($isWeekend)
                // Weekend → langsung tampilkan kantor saja
                initMap();
            @else
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(function(pos) {
                        const lat = pos.coords.latitude;
                        const lng = pos.coords.longitude;

                        // Set nilai ke form input hidden
                        document.getElementById("latInput").value = lat;
                        document.getElementById("lngInput").value = lng;

                        initMap(lat, lng);
                    }, function(error) {
                        alert("Gagal mendapatkan lokasi: " + error.message);
                        initMap(); // fallback kantor
                    });
                } else {
                    alert("Browser tidak mendukung GPS.");
                    initMap(); // fallback kantor
                }
            @endif
        }

        window.onload = () => getUserLocation();

        function isWithinAllowedTime() {
            const now = new Date();
            const makassarTime = new Date(now.toLocaleString("en-US", {
                timeZone: "Asia/Makassar"
            }));

            const hour = makassarTime.getHours();
            const minute = makassarTime.getMinutes();

            // Jam 07:00 - 23:59 => valid
            return (hour >= 7 && hour <= 23);
        }

        function showBlockedAlert() {
            alert("Absensi hanya dapat dilakukan antara pukul 07:00 hingga 00:00 WITA.");
        }

        function getDistance(lat1, lng1, lat2, lng2) {
            const R = 6371000; // radius bumi dalam meter
            const dLat = (lat2 - lat1) * Math.PI / 180;
            const dLng = (lng2 - lng1) * Math.PI / 180;
            const a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
                Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
                Math.sin(dLng / 2) * Math.sin(dLng / 2);
            const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
            return R * c;
        }

        function isInsideRadius(userLat, userLng) {
            const distance = getDistance(userLat, userLng, kantorLat, kantorLng);
            return distance <= radius;
        }

        // Intercept submit jika di luar waktu / radius
        document.addEventListener('DOMContentLoaded', () => {
            const absenMasukBtn = document.getElementById('btnAbsenMasuk');
            const absenPulangBtn = document.getElementById('btnAbsenPulang');

            @if (!$isWeekend)
                if (absenMasukBtn) {
                    absenMasukBtn.closest('form').addEventListener('submit', function(e) {
                        const lat = parseFloat(document.getElementById("latInput").value);
                        const lng = parseFloat(document.getElementById("lngInput").value);

                        if (!isWithinAllowedTime()) {
                            e.preventDefault();
                            showBlockedAlert();
                        } else if (!isInsideRadius(lat, lng)) {
                            e.preventDefault();
                            alert("Kamu berada di luar radius kantor (50m).");
                        }
                    });
                }

                if (absenPulangBtn) {
                    absenPulangBtn.closest('form').addEventListener('submit', function(e) {
                        const lat = parseFloat(document.getElementById("latInput").value);
                        const lng = parseFloat(document.getElementById("lngInput").value);

                        if (!isWithinAllowedTime()) {
                            e.preventDefault();
                            showBlockedAlert();
                        } else if (!isInsideRadius(lat, lng)) {
                            e.preventDefault();
                            alert("Kamu berada di luar radius kantor (50m).");
                        }
                    });
                }
            @endif
        });
    </script>


</x-layout>
