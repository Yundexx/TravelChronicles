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
        <div class="w-full mb-10">
            <h2 class="text-xl font-bold">Pieejamie maršruti</h2>

            <div class="bg-white rounded shadow p-3 mb-3 w-full">
                <form method="GET" action="{{ route('map') }}" class="flex items-center gap-2 text-sm w-full">

                    <select name="filter" class="border rounded px-2 py-1 h-8">
                        <option value="name">Nosaukums</option>
                        <option value="city">Pilsēta</option>
                        <option value="country">Valsts</option>
                    </select>

                    <input 
                        name="search"
                        value="{{ request('search') }}"
                        type="text"
                        class="border rounded px-2 py-1 h-8"
                        placeholder="Meklēt..." 
                    />

                    <select name="tag_id" class="border rounded px-2 py-1 h-8">
                        <option value="">Visi tēgi</option>
                        @foreach($tags as $tag)
                            <option value="{{ $tag->id }}" {{ request('tag_id') == $tag->id ? 'selected' : '' }}>
                                {{ $tag->name }}
                            </option>
                        @endforeach
                    </select>
                    
                    <button type="submit" class="btn h-8 px-3 text-sm">
                        Meklēt
                    </button>

                </form>
            </div>

            <div class="max-h-[400px] overflow-y-auto rounded shadow">
                <table class="min-w-full bg-white" id="routes-table">
                    <thead>
                        <tr class="border-b border-gray-200">
                            <th class="px-4 py-2">Nosaukums</th>
                            <th class="px-4 py-2">Valsts</th>
                            <th class="px-4 py-2">Pilsēta</th>

                            @auth
                                @if(auth()->user()->role->name === 'qualityTeam')
                                    <th class="px-4 py-2">Atzīmēts</th>
                                @endif
                            @endauth
                            
                            @auth
                            <th class="px-4 py-2">Izlase</th>
                            @endauth
                            <th class="px-4 py-2">Darbības</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($routes as $route)
                        <tr 
                            data-points='@json($route->points)' 
                            data-photos='@json($route->photos)' 
                            data-route-id="{{ $route->id }}" 
                            data-description="{{ $route->description }}"
                            data-created="{{ $route->created_at }}"
                            data-user="{{ $route->user->name ?? 'Nezināms' }}"
                            class="border-b border-gray-200"
                        >
                            <td class="px-4 py-2">{{ $route->name }}</td>
                            <td class="px-4 py-2">{{ $route->country_name ?? '-' }}</td>
                            <td class="px-4 py-2">{{ $route->city_name ?? '-' }}</td>

                            @auth
                                @if(auth()->user()->role->name === 'qualityTeam')
                                    <td class="px-4 py-2 text-center">
                                        <input 
                                            type="checkbox"
                                            class="flag-checkbox"
                                            data-route-id="{{ $route->id }}"
                                            {{ $route->flagged ? 'checked' : '' }}
                                        >
                                    </td>
                                @endif
                            @endauth
                            @auth
                                <td class="px-4 py-2 text-center">
                                    
                                        @if(auth()->user()->role->name === 'user')
                                            <input 
                                                type="checkbox"
                                                class="favorite-checkbox"
                                                data-route-id="{{ $route->id }}"
                                                {{ $route->is_favorited ? 'checked' : '' }}
                                            >
                                        @endif
                                    
                                </td>
                            @endauth
                            <td class="px-4 py-2 flex gap-2">
                                <button class="btn select-route">Parādīt kartē</button>
                                <button class="btn show-feedback">Atsauksmes</button>
                                <button class="btn show-details">Detaļas</button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="flex justify-center items-center mt-4 gap-2">
                @if ($routes->onFirstPage())
                    <span class="px-3 py-1 text-gray-400">&larr;</span>
                @else
                    <a href="{{ $routes->previousPageUrl() }}" class="px-3 py-1 rounded bg-gray-100 hover:bg-gray-200">&larr;</a>
                @endif

                <span class="px-3 py-1 font-semibold">
                    Lapa {{ $routes->currentPage() }} no {{ $routes->lastPage() }}
                </span>

                @if ($routes->hasMorePages())
                    <a href="{{ $routes->nextPageUrl() }}" class="px-3 py-1 rounded bg-gray-100 hover:bg-gray-200">&rarr;</a>
                @else
                    <span class="px-3 py-1 text-gray-400">&rarr;</span>
                @endif
            </div>
        </div>
    </div>

    <!-- Feedback Modal -->
    <div id="feedback-modal" class="fixed inset-0 bg-black/30 backdrop-blur-sm flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-2xl">
            <h3 class="text-lg font-bold mb-2">Atsauksmes</h3>
            <ul id="feedback-list" class="mb-4 max-h-40 overflow-y-auto"></ul>

            @auth
            <form id="feedback-form" class="flex flex-col gap-2">
                <textarea id="feedback-input" rows="2" class="border rounded p-2" placeholder="Atstāj atsauksmi..."></textarea>
                <button type="submit" class="btn">Nosūtīt</button>
            </form>
            @endauth

            <button id="close-feedback" class="btn mt-2">Aizvērt</button>
        </div>
    </div>

    <!-- Details Modal -->
    <div id="details-modal" class="fixed inset-0 bg-black/30 backdrop-blur-sm flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-md">
            <h3 class="text-lg font-bold mb-4">Maršruta informācija</h3>

            <p><strong>Nosaukums:</strong> <span id="d-name"></span></p>
            <p><strong>Autors:</strong> <span id="d-user"></span></p>
            <p><strong>Datums:</strong> <span id="d-date"></span></p>
            <p><strong>Apraksts:</strong></p>
            <p id="d-description" class="text-gray-600 mt-2"></p>
            
            <div id="d-photos" class="mt-4 flex gap-2 flex-wrap"></div>

            <button id="close-details" class="btn mt-4">Aizvērt</button>
        </div>
    </div>

    <!-- Image Modal -->
    <div id="image-modal" class="fixed inset-0 bg-black/70 flex items-center justify-center z-50 hidden">
        <span id="close-image" class="absolute top-5 right-8 text-white text-3xl cursor-pointer">&times;</span>
        <img id="modal-image" class="max-w-[90%] max-h-[90%] rounded shadow-lg">
    </div>

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