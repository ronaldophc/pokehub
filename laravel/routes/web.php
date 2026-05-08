<?php

use App\Livewire\Houses\History as HousesHistory;
use App\Livewire\Houses\Index as HousesIndex;
use App\Livewire\Houses\Join as HousesJoin;
use App\Livewire\Houses\Members as HousesMembers;
use App\Livewire\Houses\Show as HousesShow;
use App\Livewire\Market\Create as MarketCreate;
use App\Livewire\Market\Index as MarketIndex;
use App\Livewire\Market\MyListings as MarketMyListings;
use App\Livewire\Market\Show as MarketShow;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::redirect('dashboard', '/houses')->name('dashboard');

    Route::get('/houses', HousesIndex::class)->name('houses.index');
    Route::get('/houses/{house:slug}', HousesShow::class)->name('houses.show');
    Route::get('/houses/{house:slug}/manage', HousesMembers::class)->name('houses.members');
    Route::get('/houses/{house:slug}/history', HousesHistory::class)->name('houses.history');
    Route::get('/join/{code}', HousesJoin::class)->name('houses.join');
});

Route::get('/market', MarketIndex::class)->name('market.index');
Route::get('/market/create', MarketCreate::class)->middleware('auth')->name('market.create');
Route::get('/market/my-listings', MarketMyListings::class)->middleware('auth')->name('market.my-listings');
Route::get('/market/{listing}', MarketShow::class)->name('market.show');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__.'/auth.php';
