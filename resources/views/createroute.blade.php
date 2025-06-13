{{-- filepath: resources/views/createroute.blade.php --}}
<x-layout>
    <div class="flex flex-col items-center mt-10">
        <div class="w-full max-w-2xl bg-white rounded-lg shadow-lg p-6 mb-8">
            <h2 class="text-2xl font-bold mb-4">Create a New Route</h2>
            <form method="POST" action="{{ route('routes.store') }}">
                @csrf
                <div class="mb-4">
                    <label class="block font-semibold mb-1">Name</label>
                    <input type="text" name="name" class="w-full border rounded px-3 py-2" required>
                </div>
                <div class="mb-4">
                    <label class="block font-semibold mb-1">Description</label>
                    <textarea name="description" class="w-full border rounded px-3 py-2"></textarea>
                </div>
                <div class="mb-4 flex gap-4">
                    <div class="flex-1">
                        <label class="block font-semibold mb-1">Country</label>
                        <input type="text" name="country_name" class="w-full border rounded px-3 py-2" required>
                    </div>
                    <div class="flex-1">
                        <label class="block font-semibold mb-1">City</label>
                        <input type="text" name="city_name" class="w-full border rounded px-3 py-2">
                    </div>
                </div>
                <div class="mb-4">
                    <div class="flex gap-4 mb-2">
                        <button type="button" id="set-start" class="btn">Set start position</button>
                        <button type="button" id="set-end" class="btn">Set end position</button>
                    </div>
                    <div class="flex gap-4">
                        <input type="text" id="start_location" name="start_location" class="w-full border rounded px-3 py-2" placeholder="Start coordinates" readonly required>
                        <input type="text" id="end_location" name="end_location" class="w-full border rounded px-3 py-2" placeholder="End coordinates" readonly required>
                    </div>
                </div>
                <div id="map" class="w-full h-96 rounded shadow mb-4"></div>
                <button type="submit" class="btn w-full">Create Route</button>
            </form>
        </div>
    </div>

    @push('styles')
        <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
        <style>
            .glow {
                box-shadow: 0 0 8px 2px #22c55e, 0 0 0 2px #bbf7d0;
                transition: box-shadow 0.2s;
            }
            #map {
                z-index: 1 !important;
            }
            .leaflet-container {
                z-index: 1 !important;
            }
        </style>
    @endpush

    @push('scripts')
        <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
        <script>
            let map = L.map('map').setView([56.9512, 24.1129], 9);
            L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);

            let startMarker = null;
            let endMarker = null;
            let mode = null;
            let routeLine = null;

            const setStartBtn = document.getElementById('set-start');
            const setEndBtn = document.getElementById('set-end');

            setStartBtn.addEventListener('click', function() {
                mode = 'start';
                setStartBtn.classList.add('glow');
                setEndBtn.classList.remove('glow');
            });

            setEndBtn.addEventListener('click', function() {
                mode = 'end';
                setEndBtn.classList.add('glow');
                setStartBtn.classList.remove('glow');
            });

            map.on('click', function(e) {
                if (!mode) return;
                const coords = `${e.latlng.lat.toFixed(6)},${e.latlng.lng.toFixed(6)}`;
                if (mode === 'start') {
                    if (startMarker) map.removeLayer(startMarker);
                    startMarker = L.marker(e.latlng, {title: 'Start'}).addTo(map).bindPopup('Start position').openPopup();
                    document.getElementById('start_location').value = coords;
                } else if (mode === 'end') {
                    if (endMarker) map.removeLayer(endMarker);
                    endMarker = L.marker(e.latlng, {title: 'End'}).addTo(map).bindPopup('Destination').openPopup();
                    document.getElementById('end_location').value = coords;
                }

                // Draw line if both markers are set
                if (startMarker && endMarker) {
                    if (routeLine) map.removeLayer(routeLine);
                    routeLine = L.polyline([
                        startMarker.getLatLng(),
                        endMarker.getLatLng()
                    ], {color: 'blue'}).addTo(map);
                }
            });
        </script>
    @endpush
</x-layout>