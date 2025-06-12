<x-layout>
    <div class="flex gap-8 my-8">
        <!-- Map column -->
        <div class="w-2/5">
            <div id="map" class="w-full h-[500px] rounded shadow"></div>
        </div>
        <!-- Table column -->
        <div class="w-3/5">
            <h2 class="text-xl font-bold mb-4">Available Routes</h2>
            <div class="max-h-[400px] overflow-y-auto rounded shadow">
                <table class="min-w-full bg-white" id="routes-table">
                    <thead>
                        <tr>
                            <th class="px-4 py-2">Name</th>
                            <th class="px-4 py-2">Start</th>
                            <th class="px-4 py-2">End</th>
                            <th class="px-4 py-2"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($routes as $route)
                            <tr data-start="{{ $route->start_location }}" data-end="{{ $route->end_location }}">
                                <td class="px-4 py-2">{{ $route->name }}</td>
                                <td class="px-4 py-2">{{ $route->start_location }}</td>
                                <td class="px-4 py-2">{{ $route->end_location }}</td>
                                <td class="px-4 py-2">
                                    <button class="btn select-route">Show on Map</button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @push('styles')
        <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    @endpush

    @push('scripts')
        <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
        <script>
            let map = L.map('map').setView([56.9512, 24.1129], 9);
            let markers = [];
            let line = null;

            L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);

            function clearMap() {
                markers.forEach(m => map.removeLayer(m));
                markers = [];
                if (line) {
                    map.removeLayer(line);
                    line = null;
                }
            }

            async function getCoords(location) {
                // Check if location is in "lat, lng" format
                const coordMatch = location.match(/^\s*(-?\d+(\.\d+)?),\s*(-?\d+(\.\d+)?)\s*$/);
                if (coordMatch) {
                    return [parseFloat(coordMatch[1]), parseFloat(coordMatch[3])];
                }
                // Otherwise, geocode as before
                const url = `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(location)}`;
                const res = await fetch(url);
                const data = await res.json();
                if (data && data.length > 0) {
                    return [parseFloat(data[0].lat), parseFloat(data[0].lon)];
                }
                return null;
            }

            document.querySelectorAll('.select-route').forEach(btn => {
                btn.addEventListener('click', async function() {
                    const row = this.closest('tr');
                    const start = row.getAttribute('data-start');
                    const end = row.getAttribute('data-end');
                    clearMap();

                    const startCoords = await getCoords(start);
                    const endCoords = await getCoords(end);

                    if (startCoords && endCoords) {
                        markers.push(L.marker(startCoords).addTo(map).bindPopup('Start: ' + start).openPopup());
                        markers.push(L.marker(endCoords).addTo(map).bindPopup('End: ' + end).openPopup());
                        line = L.polyline([startCoords, endCoords], {color: 'blue'}).addTo(map);
                        map.fitBounds([startCoords, endCoords], {padding: [50, 50]});
                    } else {
                        alert('Could not find one or both locations.');
                    }
                });
            });
        </script>
    @endpush
</x-layout>
