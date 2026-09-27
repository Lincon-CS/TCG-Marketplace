<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Listing;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class MarketplaceTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_admin_dashboard()
    {
        $response = $this->get('/admin/dashboard');
        $response->assertRedirect('/login');
    }

    public function test_listing_requires_valid_price_and_id()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/listings', [
            'tcgdex_id' => '', 
            'price' => -50,    
            'condition' => 'Mint'
        ]);

        $response->assertSessionHasErrors(['tcgdex_id', 'price']);
    }

    public function test_user_can_create_a_marketplace_listing()
    {
        Storage::fake('public'); 

        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/listings', [
            'tcgdex_id' => 'swsh3-136',
            'price' => 150.00,
            'condition' => 'Mint',
            'description' => 'Pack fresh.',
            'photo' => UploadedFile::fake()->image('card.jpg')
        ]);

        $response->assertRedirect(route('listings.index'));

        // Change 'tcgdex_id' to 'card_id' to match your database schema
        $this->assertDatabaseHas('listings', [
            'card_id' => 'swsh3-136',
            'price' => 150.00,
            'user_id' => $user->id
        ]);
    }
}