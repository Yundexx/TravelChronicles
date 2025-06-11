<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Travel Chronicles</title>

  @vite('resources/css/app.css')
  @stack('styles') <!-- Add this line here -->
</head>
<body>
    @if (session('success'))
        <div id="flash" class="p-4 text-center bg-green-50 text-green-500 font-bold">
        {{ session('success') }}
        </div>
    @endif
  
    <header>
        <nav>
            <h1>
            <a href="{{ route('welcome')}}">Travel Chronicles</a>
            </h1>
            
            <a href="{{ route('map')}}" class="btn">Map</a>

            @guest
            <a href="{{ route('show.login') }}" class="btn">Login</a>
            <a href="{{ route('show.register') }}" class="btn">Sign up!</a>
            @endguest
            
            @auth
            <a href="{{ route('home')}}" class="btn">Home</a>
            <span class="border-r-2 pr-2">
                <a href="{{ route('profile') }}" class="font-bold">Hello, {{ auth()->user()->name }}</a>
            </span>
            <form action="{{ route('logout')}}" method="POST" class="m-0">
                @csrf
                <button class="btn">Logout</button>
            </form>
            @endauth

            
        </nav>
    </header> 

    <main class="container flex flex-col min-h-screen">  
        {{ $slot }}
    </main>

    <footer class="text-center mt-8 bottom-0">
        <a href="{{ route('about')}}" class="btn">About us</a>
        <p >&copy; {{ date('Y') }} Travel Chronicles. All rights reserved.</p>
    </footer>
    @stack('scripts')
</body>
</html>