<x-layout>
    <form action="{{ route('register')}}" method="POST">
        @csrf

        <h2>Register for an Account</h2>
    
        <label for="name">Name:</label>
        <input 
            type="text"
            name="name"
            required
            value="{{ old('name') }}"
            placeholder="Enter your name"
        >

        <label for="email">Email:</label>
        <input 
            type="email"
            name="email"
            required
            value="{{ old('email') }}"
            placeholder="Enter your email"
        >

        <label for="password">Password:</label>
        <input 
            type="password"
            name="password"
            required
            placeholder="Enter your password"
        >

        <label for="password_confirmation">Confirm password:</label>
        <input 
            type="password"
            name="password_confirmation"
            required
            placeholder="Enter your password again"
        >

        <button type="submit" class="btn mt-4">Register</button>

        <!-- validation errors -->
        @if ($errors->any())
                <ul class="px-4 py-2 bg-red-100 border border-red-300 rounded">
                    @foreach ($errors->all() as $error)
                        <li class="my-2 text-red-500">{{ $error }}</li>
                    @endforeach
                </ul>
        @endif
</form>
</x-layout>