<html lang="lv">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Travel Chronicles</title>

  @vite('resources/css/app.css')
  @stack('styles')

  <style>
    #site-content {
      transition: transform 0.7s cubic-bezier(0.4,0,0.2,1);
    }
  </style>

  <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body class="min-h-screen flex flex-col">
    <div class="flex-1 flex flex-col">

        <!-- Background -->
        <div class="fixed inset-0 -z-10">
            <img src="{{ asset('images/background.png') }}" 
                alt="Background" 
                class="w-full h-full object-cover blur" />
        </div>

        <!-- Flash message -->
        @if (session('success'))
            <div id="flash" class="p-4 text-center bg-green-50 text-green-500 font-bold transition-opacity duration-700 opacity-100">
                {{ session('success') }}
            </div>

            <script>
                setTimeout(() => {
                    const flash = document.getElementById('flash');
                    if (flash) {
                        flash.style.opacity = '0';
                        setTimeout(() => {
                            flash.style.display = 'none';
                        }, 700);
                    }
                }, 3000);
            </script>
        @endif

        <!-- HEADER -->
        <header class="sticky top-0 z-50 bg-white shadow">
            <nav class="w-full flex items-center justify-between gap-5 px-8 py-4">

                <!-- Logo -->
                <a href="{{ route('welcome')}}" class="text-2xl font-bold">
                    Travel Chronicles
                </a>

                <div class="flex items-center gap-5">

                    <a href="{{ route('map')}}" class="btn">
                        Karte
                    </a>

                    @guest
                        <a href="{{ route('show.login') }}" class="btn">
                            Ieiet
                        </a>

                        <!-- REGISTER BUTTON -->
                        <a href="{{ route('show.register') }}" 
                        class="btn bg-green-500 hover:bg-green-600 text-white">
                            Reģistrēties
                        </a>
                    @endguest

                    @auth
                        <a href="{{ route('create.route')}}" class="btn">
                            Izveidot maršrutu
                        </a>

                        @if(auth()->user()->role && auth()->user()->role->name === 'admin')
                            <a href="{{ route('admin.users') }}" class="btn">
                                Admin panelis
                            </a>
                        @endif

                        <a href="{{ route('home')}}" class="btn">
                            Mājaslapa
                        </a>

                        <!-- USER DROPDOWN -->
                        <span class="relative border-r-2 pr-2">
                            <button
                                type="button"
                                class="font-bold cursor-pointer px-2 py-1 rounded"
                                onclick="this.nextElementSibling.classList.toggle('hidden')"
                            >
                                Sveiki, {{ auth()->user()->name }}
                            </button>

                            <div class="hidden absolute right-0 mt-2 bg-white rounded shadow-lg border z-50 flex flex-col items-center w-32 py-2">

                                <a href="{{ route('profile') }}" 
                                class="block text-sm px-3 py-1 rounded text-white bg-blue-500 hover:bg-blue-600 w-100% text-center">
                                    Profils
                                </a>

                                <form action="{{ route('logout')}}" method="POST" class="w-full flex justify-center">
                                    @csrf

                                    <!-- LOGOUT BUTTON -->
                                    <button type="submit" 
                                        class="text-sm px-3 py-1 rounded text-white bg-red-500 hover:bg-red-600 w-full mt-2">
                                        Iziet
                                    </button>
                                </form>

                            </div>
                        </span>
                    @endauth

                </div>
            </nav>
        </header>

        <!-- CONTENT -->
        <main class="container max-w-screen-xl flex-1 flex flex-col px-2">
            {{ $slot }}
        </main>

        <!-- FOOTER -->
        <footer class="text-center mt-8">
            <div>
                <p>&copy; 2026 Travel Chronicles. Visas tiesības aizsargātas.</p>
                <p>Mana lapa</p>
            </div>
        </footer>

    </div>

@stack('scripts')
</body>
</html>