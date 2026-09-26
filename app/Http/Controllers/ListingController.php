<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use App\Services\TcgdexService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class ListingController extends Controller
{
    public function index()
    {
        $listings = Listing::with(['card', 'user'])->latest()->paginate(12);
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
}