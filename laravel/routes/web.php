<?php

use App\Livewire\Houses\History as HousesHistory;
use App\Livewire\Houses\Index as HousesIndex;
use App\Livewire\Houses\Join as HousesJoin;
use App\Livewire\Houses\Members as HousesMembers;
use App\Livewire\Houses\Show as HousesShow;
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

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__.'/auth.php';
