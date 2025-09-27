<x-layout :title="$title">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

    <div class="px-6 pt-3 mb-35 sm:pt-8.5 sm:mb-0 relative">
        <!-- Map -->
        <div id="map" class="w-full h-[661px] rounded-lg border pointer-events-auto z-0"></div>

        <!-- Tombol Absen -->
        <div
            class="max-w-xs sm:max-w-sm absolute bottom-4 left-10 bg-white shadow-lg py-4 px-[30px] rounded-lg z-32 flex flex-col space-y-2">

            <p class="text-sm text-gray-600">
                Jl. H. Bau No.6, Kunjung Mae, Kec. Mariso, Kota Makassar, Sulawesi Selatan 90125
            </p>

            <!-- Form absensi -->
            @if ($isWeekend)
                <!-- Jika hari ini Sabtu atau Minggu -->
                <button type="button" class="bg-gray-400 text-white px-4 py-2 rounded cursor-not-allowed" disabled>
                    Hari ini hari {{ $namaHari }}!
                </button>
            @elseif ($absensi && in_array($absensi->status_id, ['3', '2']))
                <!-- Sudah mengajukan izin atau sakit -->
                <button type="button" class="bg-gray-400 text-white px-4 py-2 rounded cursor-not-allowed" disabled>
                    Kamu Sudah {{ $absensi->status->nama }} Hari Ini
                </button>
            @elseif (!$absensi)
                <!-- Belum absen masuk -->
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
                <!-- Sudah absen masuk tapi belum absen pulang -->
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
                <!-- Sudah absen masuk dan pulang -->
                <button type="button" class="bg-gray-400 text-white px-4 py-2 rounded cursor-not-allowed" disabled>
                    Sudah Absen Masuk & Pulang
                </button>
            @endif
        </div>
    </div>

    <script>
        const kantorLat = -5.1488763012991425;
        const kantorLng = 119.41054079290649;
        const radius = 50; // meter
        let map;

        function initMap(userLat = null, userLng = null) {
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

            if (userLat && userLng) {
                L.marker([userLat, userLng], {
                        title: 'Lokasi Anda'
                    }).addTo(map)
                    .bindPopup('Lokasi Anda');

                map.setView([userLat, userLng], 17);
            }
        }

        function getUserLocation() {
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
                    initMap(); // tetap tampilkan peta kantor
                });
            } else {
                alert("Browser tidak mendukung GPS.");
                initMap(); // fallback
            }
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

        // Intercept submit jika di luar waktu
        document.addEventListener('DOMContentLoaded', () => {
            const absenMasukBtn = document.getElementById('btnAbsenMasuk');
            const absenPulangBtn = document.getElementById('btnAbsenPulang');

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
        });
    </script>

</x-layout>
