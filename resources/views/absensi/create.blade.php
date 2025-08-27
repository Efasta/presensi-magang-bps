<x-layout :title="$title">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

    <div class="p-6 relative">
        <!-- Map -->
        <div id="map" class="w-full h-[661px] rounded-lg border pointer-events-auto"></div>

        <!-- Tombol Absen -->
        <div
            class="absolute bottom-10 left-10 bg-white shadow-lg py-4 px-[30px] rounded-lg z-[999] flex flex-col space-y-2">
            <p class="text-sm text-gray-600 max-w-sm">
                Jl. H. Bau No.6, Kunjung Mae, Kec. Mariso, Kota Makassar, Sulawesi Selatan 90125
            </p>

            <!-- Form absensi -->
            @if (!$absensi)
                <!-- Belum absen masuk -->
                <form action="{{ url('/absensi') }}" method="POST">
                    @csrf
                    <input type="hidden" name="latitude" id="latInput" value="0">
                    <input type="hidden" name="longitude" id="lngInput" value="0">
                    <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                        Absen Sekarang
                    </button>
                </form>
            @elseif (!$absensi->jam_keluar || $absensi->jam_keluar === '-')
                <!-- Sudah absen masuk tapi belum absen pulang -->
                <form action="{{ url('/absensi/pulang') }}" method="POST">
                    @csrf
                    <input type="hidden" name="latitude" id="latInput" value="0">
                    <input type="hidden" name="longitude" id="lngInput" value="0">
                    <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">
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
    </script>

</x-layout>
