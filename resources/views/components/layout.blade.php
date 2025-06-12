<html lang="en">
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
</head>
<body class="min-h-screen flex flex-col">
    <div class="flex-1 flex flex-col">
        <div class="fixed inset-0 -z-10">
            <img src="{{ asset('images/background.png') }}" alt="Background" class="w-full h-full object-cover blur" />
        </div>

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
                }, 3000); // 3 seconds
            </script>
        @endif

        <header class="sticky top-0 z-50 bg-white shadow">
            <nav class="w-full flex items-center justify-between gap-5 px-8 py-4">
                <a href="{{ route('welcome')}}" class="flex items-center shrink-0 text-2xl font-bold m-0 h-full">
                    Travel Chronicles
                </a>
                <div class="flex items-center gap-5">
                    <a href="{{ route('map')}}" class="btn">Routes map</a>

                    @guest
                        <a href="{{ route('show.login') }}" class="btn">Login</a>
                        <a href="{{ route('show.register') }}" class="btn">Sign up!</a>
                    @endguest

                    @auth
                        <a href="{{ route('create.route')}}" class="btn">Create route</a>
                        <a href="{{ route('home')}}" class="btn">Home</a>
                        <span class="relative border-r-2 pr-2">
                            <button
                                type="button"
                                class="font-bold cursor-pointer select-none block w-full text-center px-2 py-1 rounded focus:outline-none"
                                onclick="this.nextElementSibling.classList.toggle('hidden')"
                                onblur="setTimeout(() => this.nextElementSibling.classList.add('hidden'), 150)"
                                tabindex="0"
                            >
                                Hello, {{ auth()->user()->name }}
                            </button>
                            <div
                                class="hidden absolute right-0 mt-2 bg-white rounded-b shadow-lg border border-t-0 border-gray-200 z-50 flex flex-col items-center min-w-[7rem] w-32 pt-2 pb-2"
                                style="min-height: 80px;"
                            >
                                <a href="{{ route('profile') }}" class="block text-sm px-3 py-1 my-1 rounded hover:bg-blue-50 text-center w-24">Profile</a>
                                <a href="{{ route('settings') }}" class="block text-sm px-3 py-1 my-1 rounded hover:bg-blue-50 text-center w-24">Settings</a>
                                <form action="{{ route('logout')}}" method="POST" class="m-0 w-full flex justify-center">
                                    @csrf
                                    <button type="submit" class="block text-sm px-3 py-1 rounded hover:bg-blue-50 text-center w-24 p-2">Logout</button>
                                </form>
                            </div>
                        </span>
                    @endauth
                </div>
            </nav>
        </header> 

        <main class="container max-w-screen-xl flex-1 flex flex-col mx-auto px-8">
            {{ $slot }}
        </main>
        <footer class="text-center mt-8">
            <a href="{{ route('about')}}" class="btn">About us</a>
            <p >&copy; {{ date('Y') }} Travel Chronicles. All rights reserved.</p>
        </footer>
    </div>
    @stack('scripts')
</body>
</html>