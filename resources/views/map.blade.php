@php
    // Ensure $route->id is available for feedback association
@endphp

<x-layout>

    <div class="w-full max-w-[1700px] mx-auto flex flex-col xl:flex-row gap-8 my-8 px-4">

        <!-- MAP COLUMN -->
        <div class="w-full xl:w-[32%] flex justify-center" id="map-column">

            <div
                id="map"
                class="
                    w-full
                    max-w-[650px]
                    h-[600px]
                    rounded-3xl
                    shadow-2xl
                    border border-blue-100
                    overflow-hidden
                "
            ></div>

        </div>

        <!-- TABLE COLUMN -->
        <div class="w-full xl:w-[68%] mb-10">

            <h2 class="text-3xl font-bold text-blue-800 mb-5">
                Pieejamie maršruti
            </h2>

            <!-- FILTER -->
            <div class="
                bg-white/90
                backdrop-blur-md
                rounded-3xl
                shadow-xl
                border border-blue-100
                p-4
                mb-5
            ">

                <form
                    method="GET"
                    action="{{ route('map') }}"
                    class="
                        flex
                        flex-wrap
                        items-center
                        gap-2
                        w-full
                    "
                >

                    <!-- FILTER -->
                    <select
                        name="filter"
                        class="w-[140px] min-w-[140px]"
                    >
                        <option value="name">Nosaukums</option>
                        <option value="city">Pilsēta</option>
                        <option value="country">Valsts</option>
                    </select>

                    <!-- SEARCH -->
                    <input
                        name="search"
                        value="{{ request('search') }}"
                        type="text"
                        class="w-[220px] min-w-[220px]"
                        placeholder="Meklēt..."
                    />

                    <!-- TAGS -->
                    <select
                        name="tag_id"
                        class="w-[160px] min-w-[160px]"
                    >
                        <option value="">Visi tēgi</option>

                        @foreach($tags as $tag)

                            <option
                                value="{{ $tag->id }}"
                                {{ request('tag_id') == $tag->id ? 'selected' : '' }}
                            >
                                {{ $tag->name }}
                            </option>

                        @endforeach

                    </select>

                    <!-- BUTTON -->
                    <button
                        type="submit"
                        class="btn"
                    >
                        Meklēt
                    </button>

                </form>

            </div>

            <!-- TABLE -->
            <div class="
                max-h-[600px]
                overflow-x-auto
                overflow-y-auto
                rounded-3xl
                shadow-2xl
                border border-blue-100
                bg-white/90
                backdrop-blur-md
            ">

                <table
                    class="
                        w-full
                        min-w-[900px]
                        table-fixed
                        bg-white
                    "
                    id="routes-table"
                >

                    <thead>

                        <tr class="border-b border-blue-100 bg-blue-50">

                            <!-- ROUTE NAME -->
                            <th class="px-3 py-3 w-[26%] text-left text-blue-800">
                                Nosaukums
                            </th>

                            <!-- CITY -->
                            <th class="px-3 py-3 w-[18%] text-left text-blue-800">
                                Pilsēta
                            </th>

                            @auth
                                @if(auth()->user()->role->name === 'qualityTeam')

                                    <th class="px-3 py-3 w-[80px] text-center text-blue-800">
                                        Atzīmēts
                                    </th>

                                @endif
                            @endauth

                            @auth
                                @if(auth()->user()->role->name === 'user')

                                    <th class="px-3 py-3 w-[70px] text-center align-middle text-blue-800">
                                        Izlase
                                    </th>

                                @endif
                            @endauth

                            <!-- ACTIONS -->
                            <th class="px-2 py-3 w-[135px] text-left text-blue-800">
                                Darbības
                            </th>

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
                                data-country="{{ $route->country_name ?? '-' }}"
                                data-city="{{ $route->city_name ?? '-' }}"
                                class="
                                    border-b border-blue-100
                                    hover:bg-blue-50/40
                                    transition
                                "
                            >

                                <!-- ROUTE NAME -->
                                <td class="
                                    px-3 py-3
                                    break-words
                                    whitespace-normal
                                    align-top
                                    text-blue-900
                                ">

                                    {{ $route->name }}

                                </td>

                                <!-- CITY -->
                                <td class="
                                    px-3 py-3
                                    break-words
                                    whitespace-normal
                                    align-top
                                    text-blue-900
                                ">

                                    {{ $route->city_name ?? '-' }}

                                </td>

                                <!-- FLAG -->
                                @auth
                                    @if(auth()->user()->role->name === 'qualityTeam')

                                        <td class="px-3 py-3 text-center align-top">

                                            <input
                                                type="checkbox"
                                                class="flag-checkbox scale-110"
                                                data-route-id="{{ $route->id }}"
                                                {{ $route->flagged ? 'checked' : '' }}
                                            >

                                        </td>

                                    @endif
                                @endauth

                                <!-- FAVORITE -->
                                @auth
                                    @if(auth()->user()->role->name === 'user')

                                        <td class="px-3 py-3 text-center align-top">

                                            <input
                                                type="checkbox"
                                                class="favorite-checkbox scale-110"
                                                data-route-id="{{ $route->id }}"
                                                {{ $route->is_favorited ? 'checked' : '' }}
                                            >

                                        </td>

                                    @endif
                                @endauth
                                <!-- ACTIONS -->
                                <td class="px-2 py-2 align-top">

                                    <div class="flex flex-nowrap gap-1">

                                        <button class="btn select-route">
                                            Parādīt
                                        </button>

                                        <button class="btn show-feedback">
                                            Atsauksmes
                                        </button>

                                        <button class="btn show-details">
                                            Detaļas
                                        </button>

                                    </div>

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>

            <!-- PAGINATION -->
            <div class="flex justify-center items-center mt-5 gap-3">

                @if ($routes->onFirstPage())

                    <span class="px-3 py-1 text-gray-400">
                        &larr;
                    </span>

                @else

                    <a
                        href="{{ $routes->previousPageUrl() }}"
                        class="
                            px-4 py-2
                            rounded-xl
                            bg-white
                            shadow
                            hover:bg-blue-50
                            transition
                        "
                    >
                        &larr;
                    </a>

                @endif

                <span class="px-4 py-2 font-semibold text-blue-800">

                    Lapa {{ $routes->currentPage() }}
                    no {{ $routes->lastPage() }}

                </span>

                @if ($routes->hasMorePages())

                    <a
                        href="{{ $routes->nextPageUrl() }}"
                        class="
                            px-4 py-2
                            rounded-xl
                            bg-white
                            shadow
                            hover:bg-blue-50
                            transition
                        "
                    >
                        &rarr;
                    </a>

                @else

                    <span class="px-3 py-1 text-gray-400">
                        &rarr;
                    </span>

                @endif

            </div>

        </div>

    </div>

    <!-- FEEDBACK MODAL -->
    <div
        id="feedback-modal"
        class="
            fixed inset-0
            bg-black/30
            backdrop-blur-sm
            flex items-center justify-center
            z-50 hidden
        "
    >

        <div class="
            bg-white
            rounded-3xl
            shadow-2xl
            p-6
            w-full
            max-w-2xl
            border border-blue-100
        ">

            <h3 class="text-xl font-bold mb-3 text-blue-800">
                Atsauksmes
            </h3>

            <ul
                id="feedback-list"
                class="mb-4 max-h-40 overflow-y-auto"
            ></ul>

            @auth

                <form
                    id="feedback-form"
                    class="flex flex-col gap-3"
                >

                    <textarea
                        id="feedback-input"
                        rows="2"
                        class="border rounded-xl p-3"
                        placeholder="Atstāj atsauksmi..."
                    ></textarea>

                    <button type="submit" class="btn">
                        Nosūtīt
                    </button>

                </form>

            @endauth

            <button id="close-feedback" class="btn mt-4">
                Aizvērt
            </button>

        </div>

    </div>

    <!-- DETAILS MODAL -->
    <div
        id="details-modal"
        class="
            fixed inset-0
            bg-black/30
            backdrop-blur-sm
            flex items-center justify-center
            z-50 hidden
        "
    >

        <div class="
            bg-white
            rounded-3xl
            shadow-2xl
            p-6
            w-full
            max-w-md
            border border-blue-100
        ">

            <h3 class="text-xl font-bold mb-4 text-blue-800">
                Maršruta informācija
            </h3>

            <p><strong>Nosaukums:</strong> <span id="d-name"></span></p>
            <p><strong>Autors:</strong> <span id="d-user"></span></p>
            <p><strong>Datums:</strong> <span id="d-date"></span></p>
            <p><strong>Valsts:</strong> <span id="d-country"></span></p>
            <p><strong>Pilsēta:</strong> <span id="d-city"></span></p>

            <p><strong>Apraksts:</strong></p>

            <p id="d-description" class="text-gray-600 mt-2"></p>

            <div id="d-photos" class="mt-4 flex gap-2 flex-wrap"></div>

            <button id="close-details" class="btn mt-4">
                Aizvērt
            </button>

        </div>

    </div>

    <!-- IMAGE MODAL -->
    <div
        id="image-modal"
        class="
            fixed inset-0
            bg-black/70
            flex items-center justify-center
            z-50 hidden
        "
    >

        <span
            id="close-image"
            class="
                absolute top-5 right-8
                text-white text-3xl
                cursor-pointer
            "
        >
            &times;
        </span>

        <img
            id="modal-image"
            class="
                max-w-[90%]
                max-h-[90%]
                rounded-2xl
                shadow-2xl
            "
        >

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