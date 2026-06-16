<x-layout>
    <div class="flex items-center justify-center min-h-[70vh]">
        <form class="w-full max-w-md bg-white rounded-lg shadow p-8" action="{{ route('login') }}" method="POST">
            @csrf

            <h2>Pieslēgties savam kontam</h2>

            <label for="email">E-pasts:</label>
            <input 
                type="email"
                name="email"
                required
                value="{{ old('email') }}"
                placeholder="Ievadiet savu e-pastu"
            >

            <label for="password">Parole:</label>
            <input 
                type="password"
                name="password"
                required
                placeholder="Ievadiet savu paroli"
            >

            <div class="flex items-center justify-between mt-4">
                <button type="submit" class="btn">Pieslēgties</button>
            </div>

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