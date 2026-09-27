<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Marketplace
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="bg-green-100 text-green-800 p-4 rounded-md mb-4">
                    {{ session('success') }}
                </div>
            @endif

            <div class="mb-6">
                <a href="{{ route('listings.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">Post New Listing</a>
            </div>

            <!-- Search and Filter Form -->
            <form action="{{ route('listings.index') }}" method="GET" class="mb-6 bg-white p-4 rounded-lg shadow-sm border flex flex-col md:flex-row gap-4 items-center">
                
                <div class="flex-grow w-full">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search Pokémon name or code (e.g., swsh3-136)..." class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
                
                <div class="w-full md:w-48 shrink-0">
                    <select name="sort" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500" onchange="this.form.submit()">
                        <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Newest First</option>
                        <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Oldest First</option>
                        <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Lowest Price</option>
                        <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Highest Price</option>
                    </select>
                </div>
                
                <div class="flex gap-2 w-full md:w-auto shrink-0">
                    <button type="submit" class="bg-gray-800 text-white px-6 py-2 rounded-md hover:bg-gray-700 w-full md:w-auto">Search</button>
                    
                    @if(request()->hasAny(['search', 'sort']) && (request('search') != '' || request('sort') != 'newest'))
                        <a href="{{ route('listings.index') }}" class="bg-gray-200 text-gray-800 px-4 py-2 rounded-md hover:bg-gray-300 text-center w-full md:w-auto">Clear</a>
                    @endif
                </div>
            </form>

            <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @foreach($listings as $listing)
                    <a href="{{ route('listings.show', $listing) }}" class="block bg-white border rounded-lg shadow-sm p-4 hover:shadow-md transition-shadow">
                        @if($listing->card && $listing->card->image_url)
                            <img src="{{ $listing->card->image_url }}" alt="{{ $listing->card->name }}" class="h-48 w-auto mx-auto mb-4">
                        @endif
                        
                        <h3 class="font-bold text-lg">{{ $listing->card->name ?? 'Unknown Card' }}</h3>
                        <p class="text-sm text-gray-500">{{ $listing->card->set_name ?? '' }} • {{ $listing->condition }}</p>
                        <p class="font-semibold text-blue-600 mt-2">${{ number_format($listing->price, 2) }}</p>
                        <p class="text-xs text-gray-400 mt-2">Listed by: {{ $listing->user->name }}</p>
                    </a>
                @endforeach
            </div>

            <div class="mt-6">
                {{ $listings->links() }}
            </div>
            
        </div>
    </div>
</x-app-layout>