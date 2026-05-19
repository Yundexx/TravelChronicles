<x-layout>
    @vite('resources/js/profile.js')
    <div class="flex flex-col items-center mb-4">

        <div class="flex flex-col items-center mb-6">
            <img 
                src="{{ asset('storage/' . auth()->user()->avatar) }}"
                class="w-28 h-28 rounded-full object-cover mb-3 border-4 border-white shadow-lg"
            >
        </div>

        <form action="{{ route('profile.avatar') }}" method="POST" enctype="multipart/form-data" class="max-w-screen-sm bg-white rounded-lg shadow p-3">
            @csrf
            <input type="file" name="avatar" required class="mb-2">
            <button class="btn bg-blue-500 text-white px-3 py-1 rounded">
                Augšupielādēt
            </button>
        </form>

    </div>

    <div class="flex flex-col items-center mt-10">
        <!-- User Card -->
        <div class="bg-white rounded-lg shadow-lg p-8 w-full max-w-2xl mb-8">
            <h2 class="text-2xl font-bold mb-4">Profils</h2>

            <div class="divide-y divide-dashed divide-gray-300">
                <div class="flex items-center py-2">
                    <span class="font-semibold w-32">Vārds</span>
                    <span class="flex-1 relative pl-4">
                        <span class="absolute left-0 top-1/2 w-full border-t border-dashed border-gray-300 -z-10" style="transform: skewY(-12deg);"></span>
                        <span class="relative bg-white pr-2">{{ auth()->user()->name }}</span>
                    </span>
                </div>

                <div class="flex items-center py-2">
                    <span class="font-semibold w-32">E-pasts</span>
                    <span class="flex-1 relative pl-4">
                        <span class="absolute left-0 top-1/2 w-full border-t border-dashed border-gray-300 -z-10" style="transform: skewY(-12deg);"></span>
                        <span class="relative bg-white pr-2">{{ auth()->user()->email }}</span>
                    </span>
                </div>

                <div class="flex items-center py-2">
                    <span class="font-semibold w-32">Apraksts</span>
                    <span class="flex-1 relative pl-4">
                        <span class="absolute left-0 top-1/2 w-full border-t border-dashed border-gray-300 -z-10" style="transform: skewY(-12deg);"></span>
                        <span class="relative bg-white pr-2">
                            {{ auth()->user()->bio ?? 'Nav pievienots apraksts.' }}
                        </span>
                    </span>
                </div>
            </div>
        </div>

        <!-- User's Routes Table -->
        <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-2xl">
            <h3 class="text-xl border-b-1 font-bold pb-4">Tavi maršruti</h3>

            <table class="min-w-full bg-white">
                <thead>
                    <tr>
                        <th class="px-4 py-2 text-left">Nosaukums</th>
                        <th class="px-4 py-2 text-left">Pilsēta</th>
                        <th class="px-4 py-2 text-left">Izveidots</th>
                        <th class="px-4 py-2 text-left"></th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($routes as $route)
                        <tr 
                            class="border-b border-dashed border-gray-300"
                            data-route-id="{{ $route->id }}"
                            data-description="{{ $route->description }}"
                            data-created="{{ $route->created_at }}"
                            data-user="{{ $route->user->name ?? 'Unknown' }}"
                            data-points='@json($route->points)'
                            data-photos='@json($route->photos)'
                        >

                                <td class="px-4 py-2 relative">
                                    <span class="absolute left-0 top-1/2 w-full border-t border-dashed border-gray-300 -z-10"
                                        style="transform: skewY(-12deg);"></span>
                                    <span class="relative bg-white pr-2">{{ $route->name }}</span>
                                </td>

                                <td class="px-4 py-2 relative">
                                    <span class="absolute left-0 top-1/2 w-full border-t border-dashed border-gray-300 -z-10"
                                        style="transform: skewY(-12deg);"></span>
                                    <span class="relative bg-white pr-2">{{ $route->city_name ?? '-' }}</span>
                                </td>

                                <td class="px-4 py-2 relative">
                                    <span class="absolute left-0 top-1/2 w-full border-t border-dashed border-gray-300 -z-10"
                                        style="transform: skewY(-12deg);"></span>
                                    <span class="relative bg-white pr-2">{{ $route->created_at->format('Y-m-d') }}</span>
                                </td>

                                <td class="px-4 py-2 flex gap-2">

                                    <button class="btn show-details">
                                        Skatīt
                                    </button>

                                    <!-- Удаление -->
                                    <form method="POST" action="{{ route('routes.destroy', $route->id) }}" 
                                        onsubmit="return confirm('Vai tiešām vēlies dzēst šo maršrutu?');" 
                                        class="inline">
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit" class="btn bg-red-600 text-white px-3 py-1 rounded">
                                            Dzēst
                                        </button>
                                    </form>

                                </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-2 text-center text-gray-500">
                                Nav izveidotu maršrutu.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
        <!-- DETAILS MODAL -->
    <div id="details-modal" class="fixed inset-0 bg-black/30 backdrop-blur-sm flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-md">

            <h3 class="text-lg font-bold mb-4">Maršruta detaļas</h3>

            <p><strong>Nosaukums:</strong> <span id="d-name"></span></p>
            <p><strong>Autors:</strong> <span id="d-user"></span></p>
            <p><strong>Datums:</strong> <span id="d-date"></span></p>

            <p><strong>Apraksts:</strong></p>
            <p id="d-description" class="text-gray-600 mt-2"></p>

            <div id="d-photos" class="mt-4 flex gap-2 flex-wrap"></div>

            <button id="close-details" class="btn mt-4">
                Aizvērt
            </button>

        </div>
    </div>

    <!-- IMAGE MODAL -->
    <div id="image-modal" class="fixed inset-0 bg-black/70 flex items-center justify-center z-50 hidden">
        <span id="close-image" class="absolute top-5 right-8 text-white text-3xl cursor-pointer">&times;</span>
        <img id="modal-image" class="max-w-[90%] max-h-[90%] rounded shadow-lg">
    </div>
</x-layout>

<style>
    form {
        @apply max-w-screen-sm bg-white rounded-lg shadow ;
    }
</style>
