<?php

namespace App\Services;

use App\Models\Card;
use Illuminate\Support\Facades\Http;

class TcgdexService
{
    protected $baseUrl = 'https://api.tcgdex.net/v2/en';

    public function searchCardsByName(string $name)
    {
        // Example search: "Charmander"
        $response = Http::get("{$this->baseUrl}/cards", [
            'name' => $name
        ]);

        if ($response->successful()) {
            return $response->json();
        }

        return [];
    }

    public function getAndCacheCardDetails(string $cardId)
    {
        // Check local database first to avoid unnecessary API calls
        $localCard = Card::find($cardId);
        if ($localCard) {
            return $localCard;
        }

        $response = Http::get("{$this->baseUrl}/cards/{$cardId}");

        if ($response->successful()) {
            $data = $response->json();
            
            // Cache the API result in the local SQLite database
            return Card::create([
                'id' => $data['id'],
                'name' => $data['name'],
                'set_name' => $data['set']['name'] ?? 'Promo',
                'rarity' => $data['rarity'] ?? 'Common',
                'image_url' => isset($data['image']) ? $data['image'] . '/high.png' : null,
            ]);
        }

        return null;
    }
}