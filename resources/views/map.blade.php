@php
    // Ensure $route->id is available for feedback association
@endphp
<x-layout>
    <div class="flex gap-8 my-8">
        <!-- Map column -->
        <div class="w-2/5" id="map-column">
            <div id="map" class="w-full h-[500px] rounded shadow"></div>
        </div>
        <!-- Table column -->
        <div class="w-3/5">
            <h2 class="text-xl font-bold mb-4">Available Routes</h2>
            <div class="max-h-[400px] overflow-y-auto rounded shadow">
                <table class="min-w-full bg-white" id="routes-table">
                    <thead>
                        <tr>
                            <th class="px-4 py-2 bg-white sticky top-0 z-10">Name</th>
                            <th class="px-4 py-2 bg-white sticky top-0 z-10">City</th>
                            <th class="px-4 py-2 bg-white sticky top-0 z-10">Start</th>
                            <th class="px-4 py-2 bg-white sticky top-0 z-10">End</th>
                            <th class="px-4 py-2 bg-white sticky top-0 z-10"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($routes as $route)
                            <tr data-start="{{ $route->start_location }}" data-end="{{ $route->end_location }}" data-route-id="{{ $route->id }}">
                                <td class="px-4 py-2">{{ $route->name }}</td>
                                <td class="px-4 py-2">{{ $route->city_name ?? '-' }}</td>
                                <td class="px-4 py-2">{{ $route->start_location }}</td>
                                <td class="px-4 py-2">{{ $route->end_location }}</td>
                                <td class="px-4 py-2 flex gap-2">
                                    <button class="btn select-route">Show on Map</button>
                                    <button class="btn show-feedback">Feedbacks</button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Feedback Modal -->
    <div id="feedback-modal" class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-md">
            <h3 class="text-lg font-bold mb-2">Feedbacks</h3>
            <ul id="feedback-list" class="mb-4 max-h-40 overflow-y-auto"></ul>
            <form id="feedback-form" class="flex flex-col gap-2">
                <textarea name="feedback" id="feedback-input" rows="2" class="border rounded p-2" placeholder="Leave your feedback..."></textarea>
                <button type="submit" class="btn">Submit Feedback</button>
            </form>
            <button id="close-feedback" class="btn mt-2">Close</button>
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
                const coordMatch = location.match(/^\s*(-?\d+(\.\d+)?),\s*(-?\d+(\.\d+)?)\s*$/);
                if (coordMatch) {
                    return [parseFloat(coordMatch[1]), parseFloat(coordMatch[3])];
                }
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

            // Feedback modal logic
            let currentRouteId = null;
            const mapColumn = document.getElementById('map-column');
            const feedbackModal = document.getElementById('feedback-modal');
            const feedbackList = document.getElementById('feedback-list');
            const feedbackForm = document.getElementById('feedback-form');
            const feedbackInput = document.getElementById('feedback-input');
            const closeFeedback = document.getElementById('close-feedback');

            document.querySelectorAll('.show-feedback').forEach(btn => {
                btn.addEventListener('click', async function() {
                    const row = this.closest('tr');
                    currentRouteId = row.getAttribute('data-route-id');
                    feedbackModal.classList.remove('hidden');
                    mapColumn.classList.add('hidden');
                    feedbackInput.value = '';
                    await loadFeedbacks(currentRouteId);
                });
            });

            closeFeedback.addEventListener('click', () => {
                feedbackModal.classList.add('hidden');
                mapColumn.classList.remove('hidden');
            });

            feedbackForm.addEventListener('submit', async function(e) {
                e.preventDefault();
                const feedback = feedbackInput.value.trim();
                if (!feedback) return;
                // Save feedback via AJAX
                await fetch(`/routes/${currentRouteId}/feedback`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ feedback })
                });
                feedbackInput.value = '';
                await loadFeedbacks(currentRouteId);
            });

            async function loadFeedbacks(routeId) {
                feedbackList.innerHTML = '<li>Loading...</li>';
                const res = await fetch(`/routes/${routeId}/feedback`);
                const data = await res.json();
                feedbackList.innerHTML = '';
                if (data.length === 0) {
                    feedbackList.innerHTML = '<li class="text-gray-500">No feedbacks yet.</li>';
                } else {
                    data.forEach(fb => {
                        feedbackList.innerHTML += `<li class="border-b py-1">${fb.feedback}</li>`;
                    });
                }
            }
        </script>
    @endpush
</x-layout>
