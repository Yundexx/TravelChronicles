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

        <!-- BACKGROUND -->
        <div class="fixed inset-0 -z-10">
            <img
                src="{{ asset('images/background.png') }}"
                alt="Background"
                class="w-full h-full object-cover blur-sm scale-105"
            >
        </div>

        <!-- FLASH MESSAGE -->
        @if (session('success'))

            <div
                id="flash"
                class="
                    fixed top-5 left-1/2 -translate-x-1/2 z-[100]
                    px-6 py-3
                    rounded-2xl
                    bg-white/90
                    backdrop-blur-md
                    border border-green-200
                    shadow-xl
                    text-green-600
                    font-semibold
                    transition-opacity duration-700
                "
            >
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
        <header class="
            sticky top-0 z-50
            bg-gradient-to-t
            from-white
            via-blue-50
            to-blue-200
            border-b border-blue-100
            shadow-md
            backdrop-blur-md
        ">

            <nav class="w-full bg-transparent">

                <div class="
                    max-w-6xl mx-auto
                    px-8 lg:px-12
                    py-5
                    flex flex-col lg:flex-row
                    items-center
                    justify-between
                    gap-6
                ">

                    <!-- LOGO -->
                    <a
                        href="{{ route('welcome') }}"
                        class="
                            whitespace-nowrap
                            text-4xl
                            font-extrabold
                            tracking-tight
                            text-blue-800
                            hover:text-blue-600
                            transition
                        "
                    >
                        Travel Chronicles
                    </a>

                    <!-- NAVIGATION -->
                    <div class="flex flex-wrap items-center justify-center gap-3">

                        <a href="{{ route('map') }}"
                        class="btn">
                            Karte
                        </a>

                        @guest

                            <a href="{{ route('show.login') }}"
                            class="btn">
                                Ieiet
                            </a>

                            <a href="{{ route('show.register') }}"
                            class="btn btn-success">
                                Reģistrēties
                            </a>

                        @endguest

                        @auth

                            <a href="{{ route('create.route') }}"
                            class="btn">
                                Izveidot maršrutu
                            </a>

                            @if(auth()->user()->role && auth()->user()->role->name === 'admin')

                                <a href="{{ route('admin.users') }}"
                                class="btn">
                                    Admin panelis
                                </a>

                            @endif

                            <a href="{{ route('home') }}"
                            class="btn">
                                Mājaslapa
                            </a>

                            <!-- USER MENU -->
                            <div class="relative">

                                <button
                                    type="button"
                                    onclick="this.nextElementSibling.classList.toggle('hidden')"
                                    class="
                                        flex items-center gap-3
                                        px-4 py-2
                                        rounded-2xl
                                        bg-white/70
                                        backdrop-blur-md
                                        border border-blue-100
                                        hover:bg-white
                                        transition
                                        shadow-sm
                                    "
                                >

                                    <div class="w-10 h-10 rounded-full overflow-hidden border-2 border-blue-200">

                                        <img
                                            src="{{ auth()->user()->avatar
                                                ? asset('storage/' . auth()->user()->avatar)
                                                : asset('images/default-avatar.png') }}"
                                            alt="Avatar"
                                            class="w-full h-full object-cover"
                                        >

                                    </div>

                                    <span class="font-semibold text-blue-800">
                                        {{ auth()->user()->name }}
                                    </span>

                                </button>

                                <!-- DROPDOWN -->
                                <div
                                    class="
                                        hidden
                                        absolute right-0 mt-3
                                        w-56
                                        bg-white/95
                                        backdrop-blur-xl
                                        rounded-3xl
                                        shadow-2xl
                                        border border-blue-100
                                        p-4
                                        z-50
                                    "
                                >

                                    <a href="{{ route('profile') }}"
                                    class="btn w-full mb-3">
                                        Profils
                                    </a>

                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf

                                        <button
                                            type="submit"
                                            class="btn btn-danger w-full"
                                        >
                                            Iziet
                                        </button>
                                    </form>

                                </div>

                            </div>

                        @endauth

                    </div>

                </div>

            </nav>

        </header>

        <!-- CONTENT -->
        <main class="container max-w-screen-xl flex-1 flex flex-col px-4 md:px-6">
            {{ $slot }}
        </main>

        <!-- FOOTER -->
        <footer class="
            w-full
            mt-10
            bg-gradient-to-b
            from-blue-200
            via-blue-50
            to-white
            border-t border-blue-100
        ">

            <div class="
                w-full
                px-6 md:px-12
                py-4
                flex flex-col md:flex-row
                items-center
                justify-between
                gap-3
            ">

                <!-- LEFT -->
                <div class="text-center md:text-left">

                    <h3 class="text-blue-800 text-lg font-bold leading-none mb-1">
                        Travel Chronicles
                    </h3>

                    <p class="text-blue-700 text-xs">
                        Dalies ar piedzīvojumiem un atklāj jaunus maršrutus.
                    </p>

                </div>

                <!-- CENTER -->
                <div class="flex flex-wrap justify-center gap-4 text-sm">

                    <a href="{{ route('welcome') }}"
                    class="text-blue-700 hover:text-blue-900 transition">
                        Sākums
                    </a>

                    <a href="{{ route('map') }}"
                    class="text-blue-700 hover:text-blue-900 transition">
                        Karte
                    </a>

                    @auth

                        <a href="{{ route('profile') }}"
                        class="text-blue-700 hover:text-blue-900 transition">
                            Profils
                        </a>

                    @endauth

                </div>

                <!-- RIGHT -->
                <div class="text-center md:text-right">

                    <p class="text-blue-800 text-xs font-medium">
                        © 2026 Travel Chronicles
                    </p>

                </div>

            </div>

        </footer>

    </div>

    @stack('scripts')

</body>
</html>