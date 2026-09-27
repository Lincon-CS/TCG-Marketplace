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

        // Fetch only the cards associated with the current user's active marketplace listings
        $cards = Auth::user()->listings()->with('card')->get();

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
            'offered_cards.*' => 'exists:listings,id',
        ]);

        // Create the Trade Offer (4th Table)
        $tradeOffer = TradeOffer::create([
            'sender_id' => Auth::id(),
            'listing_id' => $listing->id,
            'message' => $validated['message'],
            'status' => 'pending',
        ]);

        // Grab the card_id strings from the selected listing IDs
        $cardIds = Listing::whereIn('id', $validated['offered_cards'])->pluck('card_id');

        // Attach the cards via the Pivot Table
        $tradeOffer->offeredCards()->attach($cardIds);

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