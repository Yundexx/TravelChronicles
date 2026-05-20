<x-layout>

    @vite('resources/js/profile.js')

    <div class="flex flex-col items-center mb-6 mt-8">

        <div class="flex flex-col items-center mb-6">

            <img
                src="{{ !empty(auth()->user()->avatar)
                    ? asset('storage/' . auth()->user()->avatar)
                    : asset('images/default-avatar.png') }}"
                class="
                    w-32 h-32
                    rounded-full
                    object-cover
                    mb-4
                    border-4 border-white
                    shadow-2xl
                "
            >

        </div>

        <!-- AVATAR FORM -->
        <form
            action="{{ route('profile.avatar') }}"
            method="POST"
            enctype="multipart/form-data"
            class="
                w-full
                max-w-xl
                bg-white/90
                backdrop-blur-md
                rounded-3xl
                shadow-2xl
                border border-blue-100
                p-5
            "
        >

            @csrf

            <input
                type="file"
                name="avatar"
                required
                class="mb-3"
            >

            <button class="btn w-full">
                Augšupielādēt
            </button>

        </form>

    </div>

    <!-- MAIN CONTENT -->
    <div class="flex flex-col items-center">

        <!-- PROFILE CARD -->
        <div class="
            bg-white/90
            backdrop-blur-md
            rounded-3xl
            shadow-2xl
            border border-blue-100
            p-8
            w-full
            max-w-5xl
            mb-8
        ">

            <h2 class="text-3xl font-bold mb-6 text-blue-800">
                Profils
            </h2>

            <div class="divide-y divide-blue-100">

                <!-- NAME -->
                <div class="flex items-center py-5">

                    <span class="font-semibold w-40 text-blue-800">
                        Vārds
                    </span>

                    <span class="flex-1 pl-4 text-blue-900">
                        {{ auth()->user()->name }}
                    </span>

                </div>

                <!-- EMAIL -->
                <div class="flex items-center py-5">

                    <span class="font-semibold w-40 text-blue-800">
                        E-pasts
                    </span>

                    <span class="flex-1 pl-4 text-blue-900">
                        {{ auth()->user()->email }}
                    </span>

                </div>

                <!-- BIO -->
                <div class="py-5">

                    <div class="flex items-start">

                        <span class="font-semibold w-40 text-blue-800 pt-3">
                            Apraksts
                        </span>

                        <div class="flex-1 pl-4">

                            <form
                                method="POST"
                                action="{{ route('profile.bio') }}"
                                class="flex flex-col gap-3"
                            >

                                @csrf

                                <textarea
                                    name="bio"
                                    rows="4"
                                    maxlength="1000"
                                    placeholder="Pastāsti kaut ko par sevi..."
                                    class="
                                        w-full
                                        rounded-2xl
                                        border border-blue-200
                                        bg-white/80
                                        p-4
                                        text-blue-900
                                        shadow-sm
                                        focus:border-blue-400
                                        focus:ring-2
                                        focus:ring-blue-100
                                        transition
                                        resize-none
                                    "
                                >{{ auth()->user()->bio }}</textarea>

                                <div class="flex justify-between items-center">

                                    <span class="text-sm text-blue-400">
                                        Maksimums: 1000 simboli
                                    </span>

                                    <button class="btn">
                                        Saglabāt
                                    </button>

                                </div>

                            </form>

                        </div>

                    </div>

                </div>

            </div>

        </div>

        <!-- ROUTES -->
        <div class="
            bg-white/90
            backdrop-blur-md
            rounded-3xl
            shadow-2xl
            border border-blue-100
            p-6
            w-full
            max-w-5xl
            mb-10
        ">

            <h3 class="
                text-2xl
                font-bold
                pb-4
                mb-5
                border-b border-blue-100
                text-blue-800
            ">
                Tavi maršruti
            </h3>

            <div class="overflow-x-auto rounded-2xl">

                <table class="w-full table-fixed bg-white">

                    <thead>

                        <tr class="border-b border-blue-100">

                            <th class="px-4 py-3 text-left text-blue-800 w-[35%]">
                                Nosaukums
                            </th>

                            <th class="px-4 py-3 text-left text-blue-800 w-[25%]">
                                Pilsēta
                            </th>

                            <th class="px-4 py-3 text-left text-blue-800 w-[18%]">
                                Izveidots
                            </th>

                            <th class="px-4 py-3 text-left text-blue-800 w-[22%]">
                            </th>

                        </tr>

                    </thead>

                    <tbody>

                        @forelse($routes as $route)

                            <tr
                                class="
                                    border-b border-dashed border-blue-100
                                    hover:bg-blue-50/50
                                    transition
                                "
                                data-route-id="{{ $route->id }}"
                                data-description="{{ $route->description }}"
                                data-created="{{ $route->created_at }}"
                                data-user="{{ $route->user->name ?? 'Unknown' }}"
                                data-points='@json($route->points)'
                                data-photos='@json($route->photos)'
                            >

                                <!-- ROUTE NAME -->
                                <td class="
                                    px-4 py-4
                                    w-[35%]
                                    break-words
                                    whitespace-normal
                                    align-top
                                    text-blue-900
                                ">

                                    {{ $route->name }}

                                </td>

                                <!-- CITY -->
                                <td class="
                                    px-4 py-4
                                    w-[25%]
                                    break-words
                                    whitespace-normal
                                    align-top
                                    text-blue-900
                                ">

                                    {{ $route->city_name ?? '-' }}

                                </td>

                                <!-- DATE -->
                                <td class="
                                    px-4 py-4
                                    align-top
                                    text-blue-900
                                ">

                                    {{ $route->created_at->format('Y-m-d') }}

                                </td>

                                <!-- ACTIONS -->
                                <td class="px-4 py-4 align-top">

                                    <div class="flex flex-wrap gap-2">

                                        <button class="btn show-details">
                                            Skatīt
                                        </button>

                                        <form
                                            method="POST"
                                            action="{{ route('routes.destroy', $route->id) }}"
                                            onsubmit="return confirm('Vai tiešām vēlies dzēst šo maršrutu?');"
                                            class="inline"
                                        >

                                            @csrf
                                            @method('DELETE')

                                            <button
                                                type="submit"
                                                class="btn bg-red-600 hover:bg-red-700"
                                            >
                                                Dzēst
                                            </button>

                                        </form>

                                    </div>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="4"
                                    class="
                                        px-4 py-6
                                        text-center
                                        text-blue-500
                                    "
                                >
                                    Nav izveidotu maršrutu.
                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

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
            max-w-3xl
            border border-blue-100
        ">

            <h3 class="text-xl font-bold mb-4 text-blue-800">
                Maršruta detaļas
            </h3>

            <p><strong>Nosaukums:</strong> <span id="d-name"></span></p>
            <p><strong>Autors:</strong> <span id="d-user"></span></p>
            <p><strong>Datums:</strong> <span id="d-date"></span></p>

            <p><strong>Apraksts:</strong></p>

            <p id="d-description" class="text-gray-600 mt-2"></p>

            <div id="d-photos" class="mt-4 flex gap-2 flex-wrap"></div>

            <!-- MAP -->
            <div
                id="details-map"
                class="
                    w-full
                    h-72
                    rounded-2xl
                    overflow-hidden
                    shadow-lg
                    border border-blue-100
                    mt-5
                "
            ></div>

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
    @endpush

    @push('scripts')
        <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    @endpush

</x-layout>