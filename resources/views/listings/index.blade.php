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