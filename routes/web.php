<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ListingController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    Route::resource('listings', ListingController::class);
    
    // Trade Offer Routes
    Route::get('/listings/{listing}/trade', [App\Http\Controllers\TradeOfferController::class, 'create'])->name('trade-offers.create');
    Route::post('/listings/{listing}/trade', [App\Http\Controllers\TradeOfferController::class, 'store'])->name('trade-offers.store');
});

require __DIR__.'/auth.php';