<x-layout>
    <div class="flex flex-col items-center mt-10">
        <!-- User Card -->
        <div class="bg-white rounded-lg shadow-lg p-8 w-full max-w-2xl mb-8">
            <h2 class="text-2xl font-bold mb-4">Profile</h2>
            <div class="divide-y divide-dashed divide-gray-300">
                <div class="flex items-center py-2">
                    <span class="font-semibold w-32">Name</span>
                    <span class="flex-1 relative pl-4">
                        <span class="absolute left-0 top-1/2 w-full border-t border-dashed border-gray-300 -z-10" style="transform: skewY(-12deg);"></span>
                        <span class="relative bg-white pr-2">{{ auth()->user()->name }}</span>
                    </span>
                </div>
                <div class="flex items-center py-2">
                    <span class="font-semibold w-32">Email</span>
                    <span class="flex-1 relative pl-4">
                        <span class="absolute left-0 top-1/2 w-full border-t border-dashed border-gray-300 -z-10" style="transform: skewY(-12deg);"></span>
                        <span class="relative bg-white pr-2">{{ auth()->user()->email }}</span>
                    </span>
                </div>
                <div class="flex items-center py-2">
                    <span class="font-semibold w-32">Bio</span>
                    <span class="flex-1 relative pl-4">
                        <span class="absolute left-0 top-1/2 w-full border-t border-dashed border-gray-300 -z-10" style="transform: skewY(-12deg);"></span>
                        <span class="relative bg-white pr-2">{{ auth()->user()->bio ?? 'No bio provided.' }}</span>
                    </span>
                </div>
            </div>
        </div>

        <!-- User's Routes Table -->
        <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-2xl">
            <h3 class="text-xl border-b-1 font-bold pb-4">Your Routes</h3>
            <table class="min-w-full bg-white">
                <thead>
                    <tr>
                        <th class="px-4 py-2 text-left">Name</th>
                        <th class="px-4 py-2 text-left">City</th>
                        <th class="px-4 py-2 text-left">Created At</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($routes as $route)
                        <tr class="border-b border-dashed border-gray-300">
                            <td class="px-4 py-2 relative">
                                <span class="absolute left-0 top-1/2 w-full border-t border-dashed border-gray-300 -z-10" style="transform: skewY(-12deg);"></span>
                                <span class="relative bg-white pr-2">{{ $route->name }}</span>
                            </td>
                            <td class="px-4 py-2 relative">
                                <span class="absolute left-0 top-1/2 w-full border-t border-dashed border-gray-300 -z-10" style="transform: skewY(-12deg);"></span>
                                <span class="relative bg-white pr-2">{{ $route->city_name ?? '-' }}</span>
                            </td>
                            <td class="px-4 py-2 relative">
                                <span class="absolute left-0 top-1/2 w-full border-t border-dashed border-gray-300 -z-10" style="transform: skewY(-12deg);"></span>
                                <span class="relative bg-white pr-2">{{ $route->created_at->format('Y-m-d') }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-4 py-2 text-center text-gray-500">No routes created yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-layout>