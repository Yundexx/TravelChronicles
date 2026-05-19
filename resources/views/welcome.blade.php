<x-layout>
    <div class="text-center px-8 py-12">
        <h1 class="text-3xl font-bold mb-4">Laipni lūdzam Travel Chronicles</h1>

        <p class="mb-8">
            Dalies un atklāj maršrutus aktīvai atpūtai, pārgājieniem, riteņbraukšanai un vēl vairāk. 
            Pārlūko esošos maršrutus vai pievieno savus piedzīvojumus!
        </p>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">

            <div class="bg-white shadow rounded-lg p-6 transition-all duration-300 hover:shadow-2xl hover:bg-white/90 hover:-translate-y-1">
                <h2 class="text-xl font-semibold mb-2">Dalies ar saviem maršrutiem</h2>

                <p>
                    Dokumentē savus iecienītākos maršrutus un ceļojumus. 
                    Iedvesmo citus, daloties ar savu aktīvās atpūtas pieredzi.
                </p>
            </div>

            <div class="bg-white shadow rounded-lg p-6 transition-all duration-300 hover:shadow-2xl hover:bg-white/90 hover:-translate-y-1">
                <h2 class="text-xl font-semibold mb-2">Atklāj jaunus piedzīvojumus</h2>

                <p>
                    Pārlūko arvien pieaugošu citu lietotāju maršrutu kolekciju. 
                    Atrodi savu nākamo pārgājienu, riteņbraukšanas vai skriešanas maršrutu.
                </p>
            </div>

            <div class="bg-white shadow rounded-lg p-6 transition-all duration-300 hover:shadow-2xl hover:bg-white/90 hover:-translate-y-1">
                <h2 class="text-xl font-semibold mb-2">Pievienojies un izpēti</h2>

                <p>
                    Kļūsti par daļu no aktīvās atpūtas entuziastu kopienas. 
                    Saglabā maršrutus, atstāj atsauksmes un sazinies ar citiem lietotājiem.
                </p>
            </div>

        </div>

        @if (!Auth::check())
            <div>
                <a href="{{ route('show.register')}}" class="btn mt-4 inline-block mr-2">
                    Reģistrēties
                </a>

                <span>vai</span>

                <a href="{{ route('show.login')}}" class="btn mt-4 inline-block ml-2">
                    Pieteikties
                </a>
            </div>
        @endif

    </div>
</x-layout>