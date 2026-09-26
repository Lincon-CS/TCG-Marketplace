<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            My Sent Offers
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                @forelse($sentOffers as $offer)
                    <div class="border rounded-lg p-4 mb-6 bg-gray-50 flex flex-col md:flex-row gap-6 items-center">
                        
                        <!-- Target Card -->
                        <div class="w-full md:w-1/4 text-center border-r pr-4">
                            <h3 class="text-sm font-bold text-gray-500 mb-2">Wanted</h3>
                            @if($offer->listing->card->image_url)
                                <img src="{{ $offer->listing->card->image_url }}" alt="Target Card" class="h-32 w-auto mx-auto rounded shadow-sm">
                            @endif
                            <p class="font-semibold mt-2">{{ $offer->listing->card->name }}</p>
                        </div>

                        <!-- Offer Details -->
                        <div class="w-full md:w-3/4">
                            <div class="flex justify-between items-start mb-4">
                                <div>
                                    <p class="text-sm text-gray-500">Sent to: {{ $offer->listing->user->name }}</p>
                                    <p class="text-xs text-gray-400">{{ $offer->created_at->diffForHumans() }}</p>
                                </div>
                                <div>
                                    @if($offer->status === 'pending')
                                        <span class="bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full text-sm font-bold uppercase">Pending</span>
                                    @elseif($offer->status === 'accepted')
                                        <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-bold uppercase">Accepted</span>
                                    @else
                                        <span class="bg-red-100 text-red-800 px-3 py-1 rounded-full text-sm font-bold uppercase">Rejected</span>
                                    @endif
                                </div>
                            </div>

                            <h4 class="text-sm font-bold text-gray-600 mb-2">You Offered:</h4>
                            <div class="flex gap-3 overflow-x-auto pb-2">
                                @foreach($offer->offeredCards as $card)
                                    <div class="text-center shrink-0">
                                        @if($card->image_url)
                                            <img src="{{ $card->image_url }}" alt="{{ $card->name }}" class="h-20 w-auto rounded border shadow-sm">
                                        @endif
                                        <p class="text-xs mt-1 text-gray-600">{{ $card->name }}</p>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8 text-gray-500">
                        <p>You haven't sent any trade offers yet.</p>
                        <a href="{{ route('listings.index') }}" class="text-blue-600 hover:underline mt-2 inline-block">Browse the marketplace</a>
                    </div>
                @endforelse

            </div>
        </div>
    </div>
</x-app-layout>