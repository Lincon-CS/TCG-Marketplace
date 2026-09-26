<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $listing->card->name }} Listing
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 flex flex-col md:flex-row gap-6">
            
            <!-- Left Column: API Art & Local Photo -->
            <div class="w-full md:w-1/2 space-y-6">
                <div class="bg-white p-4 rounded-lg shadow-sm border text-center">
                    <h3 class="text-sm font-bold text-gray-500 mb-2">Physical Card Photo</h3>
                    @if($listing->photo_path)
                        <img src="{{ asset('storage/' . $listing->photo_path) }}" alt="Physical Photo" class="max-h-96 mx-auto rounded">
                    @else
                        <p>No photo provided.</p>
                    @endif
                </div>

                <div class="bg-white p-4 rounded-lg shadow-sm border text-center">
                    <h3 class="text-sm font-bold text-gray-500 mb-2">Official Database Art</h3>
                    <img src="{{ $listing->card->image_url }}" alt="{{ $listing->card->name }}" class="h-64 w-auto mx-auto">
                </div>
            </div>

            <!-- Right Column: Details & Actions -->
            <div class="w-full md:w-1/2">
                <div class="bg-white p-6 rounded-lg shadow-sm border">
                    <h1 class="text-3xl font-bold mb-2">{{ $listing->card->name }}</h1>
                    <p class="text-gray-600 mb-4">{{ $listing->card->set_name }} • {{ $listing->condition }}</p>
                    <p class="text-4xl font-semibold text-blue-600 mb-6">${{ number_format($listing->price, 2) }}</p>
                    
                    <div class="mb-6">
                        <h4 class="font-bold mb-1">Seller Notes:</h4>
                        <p class="text-gray-700">{{ $listing->description ?? 'No additional details provided.' }}</p>
                    </div>
                    <p class="text-sm text-gray-400 mb-6">Listed by: {{ $listing->user->name }}</p>

                    @if(auth()->id() === $listing->user_id || auth()->user()->is_admin)
                        <div class="flex gap-4 border-t pt-4">
                            <a href="{{ route('listings.edit', $listing) }}" class="bg-gray-200 text-gray-800 px-4 py-2 rounded-md hover:bg-gray-300">Edit Price/Condition</a>
                            
                            <form action="{{ route('listings.destroy', $listing) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this listing?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700">Delete Listing</button>
                            </form>
                        </div>

                        <div class="mt-8 border-t pt-6">
                            <h3 class="text-xl font-bold mb-4">Trade Offers Received</h3>
                            @forelse($listing->tradeOffers as $offer)
                                <div class="border rounded-lg p-4 mb-4 bg-gray-50">
                                    <div class="flex justify-between items-start mb-2">
                                        <div>
                                            <p class="font-semibold text-gray-800">From: {{ $offer->sender->name }}</p>
                                            <p class="text-sm text-gray-500">Status: <span class="uppercase font-bold text-yellow-600">{{ $offer->status }}</span></p>
                                        </div>
                                        <div class="flex gap-2 items-center">
                                            @if($offer->status === 'pending')
                                                <form action="{{ route('trade-offers.accept', $offer) }}" method="POST">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="bg-blue-600 text-white px-3 py-1 text-sm rounded hover:bg-blue-700">Accept</button>
                                                </form>
                                                <form action="{{ route('trade-offers.reject', $offer) }}" method="POST">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="bg-red-600 text-white px-3 py-1 text-sm rounded hover:bg-red-700">Reject</button>
                                                </form>
                                            @endif
                                        </div>
                                    </div>
                                    
                                    @if($offer->message)
                                        <p class="text-gray-700 text-sm italic mb-3">"{{ $offer->message }}"</p>
                                    @endif

                                    <h4 class="text-sm font-bold text-gray-600 mb-2">Offered Cards:</h4>
                                    <div class="flex gap-3 overflow-x-auto pb-2">
                                        @foreach($offer->offeredCards as $card)
                                            <div class="text-center shrink-0">
                                                @if($card->image_url)
                                                    <img src="{{ $card->image_url }}" alt="{{ $card->name }}" class="h-24 w-auto rounded border shadow-sm">
                                                @endif
                                                <p class="text-xs mt-1 text-gray-600">{{ $card->name }}</p>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @empty
                                <p class="text-gray-500 text-sm">No trade offers yet.</p>
                            @endforelse
                        </div>
                    @else
                        <a href="{{ route('trade-offers.create', $listing) }}" class="block text-center bg-green-600 text-white px-6 py-3 rounded-md hover:bg-green-700 w-full font-bold">
                            Propose Trade
                        </a>
                    @endif
                </div>
            </div>

        </div>
    </div>
</x-app-layout>