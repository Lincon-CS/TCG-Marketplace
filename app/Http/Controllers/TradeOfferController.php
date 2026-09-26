<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use App\Models\TradeOffer;
use App\Models\Card;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TradeOfferController extends Controller
{
    public function create(Listing $listing)
    {
        // Prevent users from making trade offers on their own listings
        if (Auth::id() === $listing->user_id) {
            return redirect()->route('listings.show', $listing)->withErrors('You cannot trade with yourself.');
        }

        // Fetch local cards to populate the trade offer selection
        $cards = Card::all();

        return view('trade-offers.create', compact('listing', 'cards'));
    }

    public function store(Request $request, Listing $listing)
    {
        if (Auth::id() === $listing->user_id) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'message' => 'nullable|string|max:500',
            'offered_cards' => 'required|array|min:1',
            'offered_cards.*' => 'exists:cards,id',
        ]);

        // Create the Trade Offer (4th Table)
        $tradeOffer = TradeOffer::create([
            'sender_id' => Auth::id(),
            'listing_id' => $listing->id,
            'message' => $validated['message'],
            'status' => 'pending',
        ]);

        // Attach the cards via the Pivot Table (5th Table / Feature 11)
        $tradeOffer->offeredCards()->attach($validated['offered_cards']);

        return redirect()->route('listings.show', $listing)->with('success', 'Trade offer submitted successfully!');
    }

    public function accept(TradeOffer $tradeOffer)
    {
        // Security check: ensure the logged-in user actually owns the target listing
        if (Auth::id() !== $tradeOffer->listing->user_id) {
            abort(403, 'Unauthorized action.');
        }

        // Accept the target offer
        $tradeOffer->update(['status' => 'accepted']);

        // Reject all other pending offers for this specific listing
        TradeOffer::where('listing_id', $tradeOffer->listing_id)
            ->where('id', '!=', $tradeOffer->id)
            ->update(['status' => 'rejected']);

        return back()->with('success', 'Trade offer accepted! All other offers have been declined.');
    }

    public function reject(TradeOffer $tradeOffer)
    {
        if (Auth::id() !== $tradeOffer->listing->user_id) {
            abort(403, 'Unauthorized action.');
        }

        $tradeOffer->update(['status' => 'rejected']);

        return back()->with('success', 'Trade offer rejected.');
    }
    
    public function index()
    {
        $sentOffers = TradeOffer::with(['listing.card', 'offeredCards'])
            ->where('sender_id', Auth::id())
            ->latest()
            ->get();

        return view('trade-offers.index', compact('sentOffers'));
    }
}