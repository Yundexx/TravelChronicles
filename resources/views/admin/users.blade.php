<x-layout>
    <div class="max-w-4xl mx-auto mt-10">

        <h2 class="text-2xl font-bold mb-4">Visi lietotāji</h2>

        @if(session('error'))
            <div class="text-red-600 mb-2">{{ session('error') }}</div>
        @endif

        <!-- SEARCH -->
        <form method="GET" class="mb-4 flex gap-2">
            <input 
                type="text" 
                name="search" 
                value="{{ request('search') }}"
                placeholder="Meklēt lietotājus..."
                class="border rounded px-3 py-2 w-full"
            >
            <button class="btn">Meklēt</button>
        </form>

        <!-- TABLE -->
        <div class="bg-white rounded shadow p-4">
            <table class="w-full border">

                <thead class="bg-gray-100">
                    <tr>
                        <th class="p-2">Vārds</th>
                        <th class="p-2">E-pasts</th>
                        <th class="p-2">Izveidots</th>
                        <th class="p-2">Loma</th>
                        <th class="p-2">Darbība</th>
                    </tr>
                </thead>

                <tbody class="bg-white">
                    @foreach($users as $user)
                        <tr class="border-t hover:bg-gray-50">
                            <td class="p-2">{{ $user->name }}</td>
                            <td class="p-2">{{ $user->email }}</td>
                            <td class="p-2">{{ $user->created_at->format('Y-m-d H:i:s') }}</td>
                            <td class="p-2">{{ $user->role->name }}</td>

                            <td class="p-2">
                                <button 
                                    class="btn bg-red-500 text-white open-delete-modal"
                                    data-user-id="{{ $user->id }}"
                                    data-user-name="{{ $user->name }}"
                                >
                                    Dzēst
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>

            </table>
        </div>

        <!-- PAGINATION -->
        <div class="mt-4">
            {{ $users->links() }}
        </div>

        <!-- DELETE MODAL -->
        <div id="delete-modal" class="fixed inset-0 bg-black/30 backdrop-blur-sm hidden flex items-center justify-center z-50">
            <div class="bg-white p-6 rounded shadow w-[400px]">

                <h3 class="text-lg font-bold mb-4">Apstiprināt dzēšanu</h3>

                <p class="mb-4">
                    Vai tiešām vēlies dzēst lietotāju 
                    <strong id="delete-user-name"></strong>?
                </p>

                <form id="delete-form" method="POST">
                    @csrf
                    @method('DELETE')

                    <div class="flex justify-end gap-2">
                        <button type="button" id="cancel-delete" class="btn">
                            Atcelt
                        </button>

                        <button type="submit" class="btn bg-red-500 text-white">
                            Dzēst
                        </button>
                    </div>
                </form>

            </div>
        </div>

    </div>
</x-layout>

@vite('resources/js/admin.js')