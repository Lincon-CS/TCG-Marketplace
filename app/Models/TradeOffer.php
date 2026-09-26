<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TradeOffer extends Model
{
    // Add this line to allow mass assignment
    protected $guarded = [];

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function listing()
    {
        return $this->belongsTo(Listing::class);
    }

    public function offeredCards()
    {
        return $this->belongsToMany(Card::class, 'trade_offer_items', 'trade_offer_id', 'card_id')->withTimestamps();
    }
}