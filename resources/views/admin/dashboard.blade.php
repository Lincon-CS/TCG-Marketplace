<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-red-600 leading-tight">
            Admin Moderation Dashboard
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border">
                
                @if(session('success'))
                    <div class="bg-green-100 text-green-800 p-4 rounded-md mb-4">
                        {{ session('success') }}
                    </div>
                @endif

                <!-- Admin Stats Grid -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    <div class="bg-gray-50 border rounded-lg p-4 text-center shadow-sm">
                        <h3 class="text-sm font-bold text-gray-500 uppercase tracking-wider">Registered Users</h3>
                        <p class="text-3xl font-extrabold text-blue-600 mt-2">{{ $stats['total_users'] }}</p>
                    </div>
                    
                    <div class="bg-gray-50 border rounded-lg p-4 text-center shadow-sm">
                        <h3 class="text-sm font-bold text-gray-500 uppercase tracking-wider">Marketplace Listings</h3>
                        <p class="text-3xl font-extrabold text-green-600 mt-2">{{ $stats['active_listings'] }}</p>
                    </div>

                    <div class="bg-gray-50 border rounded-lg p-4 text-center shadow-sm">
                        <h3 class="text-sm font-bold text-gray-500 uppercase tracking-wider">Completed Trades</h3>
                        <p class="text-3xl font-extrabold text-purple-600 mt-2">{{ $stats['successful_trades'] }}</p>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-100 border-b">
                                <th class="p-3 text-sm font-bold text-gray-600">ID</th>
                                <th class="p-3 text-sm font-bold text-gray-600">Card Name</th>
                                <th class="p-3 text-sm font-bold text-gray-600">Seller</th>
                                <th class="p-3 text-sm font-bold text-gray-600">Price</th>
                                <th class="p-3 text-sm font-bold text-gray-600">Listed On</th>
                                <th class="p-3 text-sm font-bold text-gray-600 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($listings as $listing)
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="p-3 text-sm text-gray-500">{{ $listing->id }}</td>
                                    <td class="p-3 font-medium">
                                        <a href="{{ route('listings.show', $listing) }}" class="text-blue-600 hover:underline">
                                            {{ $listing->card->name ?? $listing->tcgdex_id }}
                                        </a>
                                    </td>
                                    <td class="p-3 text-sm text-gray-700">{{ $listing->user->name }}</td>
                                    <td class="p-3 text-sm text-gray-700">${{ number_format($listing->price, 2) }}</td>
                                    <td class="p-3 text-sm text-gray-500">{{ $listing->created_at->format('M j, Y') }}</td>
                                    <td class="p-3 text-right">
                                        <form action="{{ route('listings.destroy', $listing) }}" method="POST" onsubmit="return confirm('Force delete this listing?');" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-800 text-sm font-bold">Remove</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="p-4 text-center text-gray-500">No listings found in the database.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $listings->links() }}
                </div>

            </div>
        </div>
    </div>
</x-app-layout>