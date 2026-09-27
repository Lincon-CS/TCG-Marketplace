<x-app-layout>
    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- Welcome Header -->
            <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                <h2 class="text-2xl font-bold text-gray-900">Dashboard</h2>
                <p class="text-gray-600 mt-1">Welcome back, {{ Auth::user()->name }}. Manage your items and market activity.</p>
            </div>

            <!-- Stats Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                
                <!-- Active Listings -->
                <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200 flex flex-col justify-between">
                    <div>
                        <h3 class="text-sm font-bold text-gray-500 uppercase tracking-wider">Active Listings</h3>
                        <p class="text-4xl font-black text-gray-900 mt-2">{{ Auth::user()->listings()->count() }}</p>
                    </div>
                    <div class="mt-6 flex gap-4">
                        <a href="{{ route('listings.create') }}" class="text-sm font-medium text-blue-600 hover:text-blue-800">
                            + Post New Listing
                        </a>
                    </div>
                </div>

                <!-- Pending Offers -->
                <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200 flex flex-col justify-between">
                    @php
                        // Calculates offers made ON the logged-in user's listings that are still pending
                        $pendingOffers = \App\Models\TradeOffer::whereHas('listing', function($query) {
                            $query->where('user_id', Auth::id());
                        })->where('status', 'pending')->count();
                    @endphp
                    <div>
                        <h3 class="text-sm font-bold text-gray-500 uppercase tracking-wider">Pending Offers</h3>
                        <p class="text-4xl font-black text-gray-900 mt-2">{{ $pendingOffers }}</p>
                    </div>
                    <div class="mt-6">
                        <a href="{{ route('trade-offers.index') }}" class="text-sm font-medium text-blue-600 hover:text-blue-800">
                            View My Offers &rarr;
                        </a>
                    </div>
                </div>

            </div>

            <!-- Marketplace Call to Action -->
            <div class="bg-gray-900 p-8 rounded-lg shadow-sm text-center">
                <h3 class="text-xl font-bold text-white mb-4">Ready to hunt for cards?</h3>
                <a href="{{ route('listings.index') }}" class="inline-block bg-white hover:bg-gray-100 text-gray-900 font-bold py-3 px-8 rounded-md transition shadow">
                    Browse Marketplace
                </a>
            </div>

        </div>
    </div>
</x-app-layout>