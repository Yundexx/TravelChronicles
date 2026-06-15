<x-layout>
    <div class="flex flex-col items-center mt-10">

        <!-- Greeting Card -->
        <div class="bg-white rounded-lg shadow-lg p-8 w-full max-w-2xl mb-8 text-center">
            <h1 class="text-2xl font-bold mb-4">
                Sveiki, {{ auth()->user()->name }}!
            </h1>

            <p class="text-gray-700 mb-4">
                Prieks tevi atkal redzēt!
            </p>

            <p class="text-gray-600 mb-6">
                Vai šodien nav lieliska diena pastaigai svaigā gaisā? 
                Apskati, ar kādiem maršrutiem mūsu lietotāji pēdējā laikā ir dalījušies!
                Kad izmēģināsi kādu no tiem, neaizmirsti atstāt atsauksmi — dod autoram zināt, kas bija īpaši labs!
            </p>

            <a href="{{ route('map') }}" 
               class="btn bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded">
                Skatīt maršrutus
            </a>
        </div>

        <!-- Create Route Card -->
        <div class="bg-white rounded-lg shadow-lg p-8 w-full max-w-2xl text-center">
            <h2 class="text-xl font-bold mb-4">
                Vēlies padalīties ar savu maršrutu?
            </h2>

            <p class="text-gray-600 mb-6">
                Varbūt tev ir kāds īpašs maršruts, noskaņojums vai piedzīvojums, ar kuru vēlies dalīties?
                Pievieno savu maršrutu, aprakstu un fotogrāfijas, lai iedvesmotu citus!
            </p>

            <a href="{{ route('create.route') }}" 
               class="btn bg-green-500 hover:bg-green-600 text-white px-6 py-2 rounded">
                Izveidot maršrutu
            </a>
        </div>

    </div>
</x-layout>