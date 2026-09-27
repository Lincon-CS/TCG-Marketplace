<x-app-layout>
    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            <!-- Offers Received -->
            <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                <h2 class="text-2xl font-bold text-gray-900 mb-4 border-b pb-2">Offers Received</h2>
                @if($receivedOffers->isEmpty())
                    <p class="text-gray-500">No offers received on your listings yet.</p>
                @else
                    <div class="space-y-4">
                        @foreach($receivedOffers as $offer)
                            @php
                                $statusColor = match(strtolower($offer->status)) {
                                    'accepted' => 'text-green-600',
                                    'rejected' => 'text-red-600',
                                    default => 'text-orange-500',
                                };
                            @endphp
                            <div class="p-4 border rounded-md flex justify-between items-center bg-gray-50">
                                <div>
                                    <p class="font-bold text-gray-800">{{ $offer->sender?->name ?? 'Unknown User' }} made an offer on your {{ $offer->listing->card->name }} ({{ $offer->listing->card->id }})</p>
                                    <p class="text-sm text-gray-600">Status: <span class="uppercase font-semibold {{ $statusColor }}">{{ $offer->status }}</span></p>
                                </div>
                                <a href="{{ route('trade-offers.show', $offer->id) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded text-sm font-bold transition">Review Offer</a>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Offers Sent -->
            <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                <h2 class="text-2xl font-bold text-gray-900 mb-4 border-b pb-2">Offers Sent</h2>
                @if($sentOffers->isEmpty())
                    <p class="text-gray-500">You haven't sent any offers yet.</p>
                @else
                    <div class="space-y-4">
                        @foreach($sentOffers as $offer)
                            @php
                                $statusColor = match(strtolower($offer->status)) {
                                    'accepted' => 'text-green-600',
                                    'rejected' => 'text-red-600',
                                    default => 'text-orange-500',
                                };
                            @endphp
                            <div class="p-4 border rounded-md flex justify-between items-center bg-gray-50">
                                <div>
                                    <p class="font-bold text-gray-800">Your offer on {{ $offer->listing->card->name }} ({{ $offer->listing->card->id }})</p>
                                    <p class="text-sm text-gray-600">Status: <span class="uppercase font-semibold {{ $statusColor }}">{{ $offer->status }}</span></p>
                                </div>
                                <a href="{{ route('listings.show', $offer->listing_id) }}" class="text-blue-600 hover:underline text-sm font-bold">View Original Listing</a>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>