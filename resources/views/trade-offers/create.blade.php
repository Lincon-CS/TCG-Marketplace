<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Propose Trade for {{ $listing->card->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 flex flex-col md:flex-row gap-6">
            
            <!-- Target Listing Summary -->
            <div class="w-full md:w-1/3 space-y-4">
                <div class="bg-white p-4 rounded-lg shadow-sm border text-center">
                    <h3 class="font-bold text-gray-700 mb-2">Target Card</h3>
                    <img src="{{ $listing->card->image_url }}" alt="{{ $listing->card->name }}" class="h-48 w-auto mx-auto mb-2">
                    <p class="font-semibold">{{ $listing->card->name }}</p>
                    <p class="text-sm text-gray-500">{{ $listing->condition }} • Listed by {{ $listing->user->name }}</p>
                </div>
            </div>

            <!-- Trade Offer Form -->
            <div class="w-full md:w-2/3">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border">
                    
                    @if($errors->any())
                        <div class="mb-4 text-red-600">
                            <ul>
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('trade-offers.store', $listing) }}" method="POST" class="space-y-6">
                        @csrf

                        <div>
                            <label class="block font-medium text-sm text-gray-700 mb-2">Select Cards to Offer (Select at least 1)</label>
                            <div class="grid grid-cols-2 gap-4 max-h-64 overflow-y-auto border p-4 rounded-md bg-gray-50">
                                @forelse($cards as $card)
                                    <label class="flex items-center space-x-3 bg-white p-2 border rounded shadow-sm cursor-pointer hover:bg-blue-50">
                                        <input type="checkbox" name="offered_cards[]" value="{{ $card->id }}" class="rounded border-gray-300 text-blue-600 shadow-sm">
                                        <div class="flex items-center gap-2">
                                            @if($card->image_url)
                                                <img src="{{ $card->image_url }}" class="h-10 w-auto rounded" alt="{{ $card->name }}">
                                            @endif
                                            <span class="text-sm font-medium text-gray-700">{{ $card->name }}</span>
                                        </div>
                                    </label>
                                @empty
                                    <p class="text-sm text-gray-500 col-span-2">No cards available in the database to offer.</p>
                                @endforelse
                            </div>
                        </div>

                        <div>
                            <label class="block font-medium text-sm text-gray-700">Message to Seller (Optional)</label>
                            <textarea name="message" rows="3" class="border-gray-300 rounded-md shadow-sm w-full mt-1" placeholder="Let them know why this is a fair trade...">{{ old('message') }}</textarea>
                        </div>

                        <div class="flex gap-4">
                            <button type="submit" class="bg-green-600 text-white px-6 py-2 rounded-md hover:bg-green-700 font-bold">Submit Trade Offer</button>
                            <a href="{{ route('listings.show', $listing) }}" class="bg-gray-200 text-gray-800 px-6 py-2 rounded-md hover:bg-gray-300">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>