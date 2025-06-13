@php
    // Ensure $route->id is available for feedback association
@endphp
<x-layout>
    <div class="flex justify-center items-center gap-8 my-8">
        <!-- Map column -->
        <div class="flex-1 flex justify-center" id="map-column">
            <div id="map" class="w-[500px] h-[500px] rounded shadow"></div>
        </div>
        <!-- Table/filter column -->
        <div class="w-[600px] mb-10 max-w-full">
            <h2 class="text-xl font-bold">Available Routes</h2>
            <div class="flex items-center gap-2">
                <select id="filter-type" class="border rounded px-2 py-1">
                    <option value="name">Name</option>
                    <option value="city">City</option>
                    <option value="country">Country</option>
                    <option value="flagged">Flagged</option>
                </select>
                <input id="filter-input" type="text" class="border rounded px-2 py-1" placeholder="Search..." />
            </div>
            <div class="max-h-[400px] overflow-y-auto rounded shadow">
                <table class="min-w-full bg-white" id="routes-table">
                    <thead>
                        <tr class="border-b border-gray-200">
                            <th class="px-4 py-2 bg-white sticky top-0 z-10">Name</th>
                            <th class="px-4 py-2 bg-white sticky top-0 z-10">City</th>
                            @auth
                                <th class="px-4 py-2 bg-white sticky top-0 z-10">Flagged</th>
                            @endauth 
                            <th class="px-4 py-2 bg-white sticky top-0 z-10"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($routes as $route)
                            <tr data-start="{{ $route->start_location }}" data-end="{{ $route->end_location }}" data-route-id="{{ $route->id }}" class="border-b border-gray-200">
                                <td class="px-4 py-2">{{ $route->name }}</td>
                                <td class="px-4 py-2">{{ $route->city_name ?? '-' }}</td>
                                <td class="px-4 py-2 text-center">
                                    @auth
                                    <input type="checkbox" class="flag-checkbox" data-route-id="{{ $route->id }}" {{ $route->flagged ? 'checked' : '' }}>
                                    @endauth
                                </td>
                                <td class="px-4 py-2 flex gap-2">
                                    <button class="btn select-route">Show on Map</button>
                                    <button class="btn show-feedback"
                                        data-route-name="{{ $route->name }}"
                                    >
                                        Feedbacks
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination controls -->
            <div class="flex justify-center items-center mt-4 gap-2">
                @if ($routes->onFirstPage())
                    <span class="px-3 py-1 text-gray-400">&larr;</span>
                @else
                    <a href="{{ $routes->previousPageUrl() }}" class="px-3 py-1 rounded bg-gray-100 hover:bg-gray-200">&larr;</a>
                @endif

                <span class="px-3 py-1 font-semibold">Page {{ $routes->currentPage() }} of {{ $routes->lastPage() }}</span>

                @if ($routes->hasMorePages())
                    <a href="{{ $routes->nextPageUrl() }}" class="px-3 py-1 rounded bg-gray-100 hover:bg-gray-200">&rarr;</a>
                @else
                    <span class="px-3 py-1 text-gray-400">&rarr;</span>
                @endif
            </div>
        </div>
    </div>

    <!-- Feedback Modal -->
    <div id="feedback-modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-2xl">
            <h3 class="text-lg font-bold mb-2">Feedbacks</h3>
            <ul id="feedback-list" class="mb-4 max-h-40 overflow-y-auto"></ul>
            @auth
            <form id="feedback-form" class="flex flex-col gap-2">
                <textarea name="feedback" id="feedback-input" rows="2" class="border rounded p-2" placeholder="Leave your feedback..."></textarea>
                <button type="submit" class="btn">Submit Feedback</button>
            </form>
            @endauth
            <button id="close-feedback" class="btn mt-2">Close</button>
        </div>
    </div>

    <div id="route-description" class="mb-4 text-gray-700"></div>

    @push('styles')
        <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
        <style>
            #map,
            .leaflet-container {
                z-index: 0 !important;
            }
        </style>
    @endpush

    @push('scripts')
        <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
        @vite('resources/js/map.js')
    @endpush
</x-layout>
