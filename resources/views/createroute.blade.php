{{-- filepath: resources/views/createroute.blade.php --}}
<x-layout>
    <div class="flex flex-col items-center mt-10">
        <div class="w-full max-w-2xl bg-white rounded-lg shadow-lg p-6 mb-8">
            <h2 class="text-2xl font-bold mb-4">Izveidot jaunu maršrutu</h2>

            <form method="POST" action="{{ route('routes.store') }}" enctype="multipart/form-data">
                @csrf

                <div class="mb-4">
                    <label class="block font-semibold mb-1">Nosaukums</label>
                    <input type="text" name="name" class="w-full border rounded px-3 py-2" required>
                </div>

                <div class="mb-4">
                    <label class="block font-semibold mb-1">Apraksts</label>
                    <textarea name="description" class="w-full border rounded px-3 py-2"></textarea>
                </div>

                <div class="mb-4 flex gap-4">
                    <div class="flex-1">
                        <label class="block font-semibold mb-1">Valsts</label>
                        <input type="text" name="country_name" class="w-full border rounded px-3 py-2" required>
                    </div>

                    <div class="flex-1">
                        <label class="block font-semibold mb-1">Pilsēta</label>
                        <input type="text" name="city_name" class="w-full border rounded px-3 py-2">
                    </div>
                </div>

                <div id="map" class="w-full h-96 rounded shadow mb-4"></div>
                <div class="flex gap-2 mb-4">
                    <button type="button" onclick="removeLastPoint()" class="btn bg-red-500 text-white">
                        Dzēst pēdējo punktu
                    </button>

                    <button type="button" onclick="clearRoute()" class="btn bg-gray-500 text-white">
                        Notīrīt maršrutu
                    </button>
                </div>

                <input type="hidden" name="points" id="points-data">

                <div class="mb-4">
                    <label class="block font-semibold mb-1">Maršruta fotogrāfijas</label>
                    <input type="file" name="photos[]" multiple class="w-full border rounded px-3 py-2">
                </div>
                
                <div class="mb-4">
                    <label class="block font-semibold mb-1">Tēgi</label>

                    <div class="flex flex-wrap gap-2">
                        @foreach($tags as $tag)
                            <label class="flex items-center gap-1">
                                <input type="checkbox" name="tags[]" value="{{ $tag->id }}">
                                <span>{{ $tag->name }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <button type="submit" class="btn w-full">Izveidot maršrutu</button>
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
        @vite('resources/js/createroute.js')
        <script>
           
        </script>
        @endpush
</x-layout>