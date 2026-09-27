<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use App\Services\TcgdexService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Http;

class ListingController extends Controller
{
   public function index(Request $request)
    {
        $query = Listing::with(['card', 'user']);

        // Handle Search (Card Name or Code/ID)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('card', function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('id', 'like', '%' . $search . '%');
            });
        }

        // Handle Sorting
        $sort = $request->input('sort', 'newest');
        match ($sort) {
            'price_asc' => $query->orderBy('price', 'asc'),
            'price_desc' => $query->orderBy('price', 'desc'),
            'oldest' => $query->orderBy('created_at', 'asc'),
            default => $query->orderBy('created_at', 'desc'), // newest
        };

        // Paginate and retain search/sort parameters in the URL
        $listings = $query->paginate(12)->withQueryString();

        return view('listings.index', compact('listings'));
    }

    public function create()
    {
        return view('listings.create');
    }

    public function store(Request $request, TcgdexService $tcgdex)
    {
        $validated = $request->validate([
            'tcgdex_id' => 'required|string',
            'condition' => 'required|string|in:Mint,Near Mint,Lightly Played,Heavily Played,Damaged',
            'price' => 'required|numeric|min:0',
            'photo' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
            'description' => 'nullable|string|max:500',
        ]);

        // Fetch and cache card data from API
        $card = $tcgdex->getAndCacheCardDetails($validated['tcgdex_id']);

        if (!$card) {
            return back()->withErrors(['tcgdex_id' => 'Could not find this card in the database.']);
        }

        // Handle the physical card photo upload
        $photoPath = $request->file('photo')->store('listings', 'public');

        // Save the listing
        Auth::user()->listings()->create([
            'card_id' => $card->id,
            'condition' => $validated['condition'],
            'price' => $validated['price'],
            'photo_path' => $photoPath,
            'description' => $validated['description'],
        ]);

        return redirect()->route('listings.index')->with('success', 'Listing created successfully!');
        
    }
    
    public function show(Listing $listing)
    {
        return view('listings.show', compact('listing'));
    }

    public function edit(Listing $listing)
    {
        Gate::authorize('update', $listing);
        return view('listings.edit', compact('listing'));
    }

    public function update(Request $request, Listing $listing)
    {
        Gate::authorize('update', $listing);

        $validated = $request->validate([
            'condition' => 'required|string|in:Mint,Near Mint,Lightly Played,Heavily Played,Damaged',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string|max:500',
        ]);

        $listing->update($validated);

        return redirect()->route('listings.show', $listing)->with('success', 'Listing updated successfully!');
    }

    public function destroy(Listing $listing)
    {
        Gate::authorize('delete', $listing);
        
        $listing->delete();

        return redirect()->route('listings.index')->with('success', 'Listing removed from the marketplace.');
    }
    
    public function searchCards(Request $request)
    {
        $query = $request->input('q');
        
        if (strlen($query) < 3) {
            return response()->json([]);
        }

        // Cache the result for 24 hours (86400 seconds) to eliminate external API lag
        $cards = \Illuminate\Support\Facades\Cache::remember('tcg_search_' . strtolower($query), 86400, function () use ($query) {
            $response = \Illuminate\Support\Facades\Http::get("https://api.tcgdex.net/v2/en/cards", [
                'name' => 'like:' . $query
            ]);

            return $response->successful() ? $response->json() : [];
        });

        // Increased from 10 to 50 results
        return response()->json(array_slice($cards, 0, 50));
    }

    public function adminIndex()
    {
        $listings = Listing::with(['card', 'user'])->latest()->paginate(20);
        
        $stats = [
            'total_users' => \App\Models\User::count(),
            'active_listings' => Listing::count(),
            'successful_trades' => \App\Models\TradeOffer::where('status', 'accepted')->count(),
        ];

        return view('admin.dashboard', compact('listings', 'stats'));
    }
}