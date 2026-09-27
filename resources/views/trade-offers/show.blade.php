<x-app-layout>
    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-8 rounded-lg shadow-sm border border-gray-200">
                
                <div class="flex justify-between items-start border-b pb-4 mb-6">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">Review Trade Offer</h2>
                        <p class="text-gray-500 mt-1">Offer ID: #{{ $tradeOffer->id }}</p>
                    </div>
                    <a href="{{ route('trade-offers.index') }}" class="text-sm font-medium text-blue-600 hover:underline">&larr; Back to Offers</a>
                </div>

                <div class="space-y-6">
                    
                    <!-- Offer Details -->
                    <div>
                        <h3 class="text-sm font-bold text-gray-500 uppercase tracking-wider mb-2">Offer Details</h3>
                        <div class="bg-gray-50 p-4 rounded-md border">
                            <p><strong>From:</strong> {{ $tradeOffer->sender?->name ?? 'Unknown User' }}</p>
                            <p><strong>Status:</strong> 
                                @php
                                    $statusColor = match(strtolower($tradeOffer->status)) {
                                        'accepted' => 'text-green-600',
                                        'rejected' => 'text-red-600',
                                        default => 'text-orange-500',
                                    };
                                @endphp
                                <span class="uppercase font-semibold {{ $statusColor }}">{{ $tradeOffer->status }}</span>
                            </p>
                            <p class="mt-4"><strong>Message:</strong></p>
                            <p class="text-gray-700 italic bg-white p-3 border rounded mt-1">{{ $tradeOffer->message ?? 'No message provided.' }}</p>
                        </div>
                    </div>

                    <!-- Cards Offered Section -->
                    <div>
                        <h3 class="text-sm font-bold text-gray-500 uppercase tracking-wider mb-2">Cards Offered in Trade</h3>
                        @if($tradeOffer->offeredCards && $tradeOffer->offeredCards->isNotEmpty())
                            <div class="space-y-3">
                                @foreach($tradeOffer->offeredCards as $card)
                                    <div class="bg-gray-50 p-4 rounded-md border flex items-center gap-4">
                                        @if($card->image_url)
                                            <img src="{{ $card->image_url }}" alt="{{ $card->name }}" class="h-24 w-auto rounded border shadow-sm" loading="lazy">
                                        @endif
                                        <div class="flex-1">
                                            <p class="font-bold text-gray-800">{{ $card->name }} ({{ $card->id }})</p>
                                        </div>
                                        
                                        <!-- Safely attempts to link to the specific listing, falling back to a marketplace search if the ID is a string -->
                                        @if(isset($card->pivot->listing_id) || is_numeric($card->id))
                                            <a href="{{ route('listings.show', $card->pivot->listing_id ?? $card->id) }}" class="bg-blue-100 text-blue-700 hover:bg-blue-200 px-3 py-1 rounded text-sm font-bold transition" target="_blank">View Listing</a>
                                        @else
                                            <a href="{{ route('listings.index', ['search' => $card->id]) }}" class="bg-blue-100 text-blue-700 hover:bg-blue-200 px-3 py-1 rounded text-sm font-bold transition" target="_blank">View in Market</a>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="bg-gray-50 p-4 rounded-md border">
                                <p class="text-gray-500 italic">No specific cards were attached to this offer.</p>
                            </div>
                        @endif
                    </div>

                    <!-- Target Listing Section -->
                    <div>
                        <h3 class="text-sm font-bold text-gray-500 uppercase tracking-wider mb-2">Target Listing</h3>
                        <div class="bg-gray-50 p-4 rounded-md border flex items-center gap-4">
                            @if($tradeOffer->listing->card->image_url)
                                <img src="{{ $tradeOffer->listing->card->image_url }}" alt="{{ $tradeOffer->listing->card->name }}" class="h-24 w-auto rounded border shadow-sm" loading="lazy">
                            @endif
                            <div class="flex-1">
                                <p class="font-bold text-gray-800">{{ $tradeOffer->listing->card->name }} ({{ $tradeOffer->listing->card->id }})</p>
                            </div>
                            <a href="{{ route('listings.show', $tradeOffer->listing_id) }}" class="bg-blue-100 text-blue-700 hover:bg-blue-200 px-3 py-1 rounded text-sm font-bold transition" target="_blank">View Listing #{{ $tradeOffer->listing_id }}</a>
                        </div>
                    </div>
                
                </div> 

                <!-- Action Buttons -->
                @if(strtolower($tradeOffer->status) === 'pending' && $tradeOffer->listing->user_id === Auth::id())
                    <div class="mt-8 flex gap-4 pt-6 border-t">
                        <form action="{{ route('trade-offers.update', $tradeOffer->id) }}" method="POST" class="flex-1">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status" value="accepted">
                            <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-4 rounded transition">
                                Accept Offer
                            </button>
                        </form>
                        <form action="{{ route('trade-offers.update', $tradeOffer->id) }}" method="POST" class="flex-1">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status" value="rejected">
                            <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-3 px-4 rounded transition">
                                Reject Offer
                            </button>
                        </form>
                    </div>
                @endif

            </div>
        </div>
    </div>
</x-app-layout>