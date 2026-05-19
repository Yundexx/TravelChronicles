<x-layout>
    <div class="flex items-center justify-center min-h-[70vh]">
        <form class="w-full max-w-lg bg-white rounded-lg shadow p-8" action="{{ route('register')}}" method="POST">
            @csrf

            <h2>Reģistrēt jauno kontu</h2>
        
            <label for="name">Vārds:</label>
            <input 
                type="text"
                name="name"
                required
                value="{{ old('name') }}"
                placeholder="Ievadi savu vārdu"
            >

            <label for="email">E-pasts:</label>
            <input 
                type="email"
                name="email"
                required
                value="{{ old('email') }}"
                placeholder="Ievadi savu e-pastu"
            >

            <label for="password">Parole:</label>
            <input 
                type="password"
                name="password"
                required
                placeholder="Ievadi paroli"
            >

            <label for="password_confirmation">Apstiprini paroli:</label>
            <input 
                type="password"
                name="password_confirmation"
                required
                placeholder="Ievadi paroli vēlreiz"
            >

            <button type="submit" class="btn mt-4">Reģistrēties</button>

            <!-- validation errors -->
            @if ($errors->any())
                <ul class="px-4 py-2 bg-red-100 border border-red-300 rounded mt-4">
                    @foreach ($errors->all() as $error)
                        <li class="my-2 text-red-500">{{ $error }}</li>
                    @endforeach
                </ul>
            @endif
        </form>
    </div>
</x-layout>