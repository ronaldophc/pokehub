<?php

namespace App\Livewire\Market;

use App\Enums\MarketStatus;
use App\Models\MarketListing;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;

#[Layout('layouts.app')]
class MyListings extends Component
{
    #[Url]
    public string $tab = 'active';

    public function markSold(MarketListing $listing): void
    {
        $this->authorize('markSold', $listing);
        $listing->update(['status' => MarketStatus::Sold]);
        $this->dispatch('notify', message: 'Anúncio marcado como vendido.', type: 'success');
    }

    public function delete(MarketListing $listing): void
    {
        $this->authorize('delete', $listing);

        if ($listing->screenshot_path) {
            Storage::disk('public')->delete($listing->screenshot_path);
        }

        $listing->delete();
        $this->dispatch('notify', message: 'Anúncio removido.', type: 'success');
    }

    public function render()
    {
        $userId      = auth()->id();
        $activeCount = MarketListing::where('user_id', $userId)->active()->count();

        $listings = match ($this->tab) {
            'sold'    => MarketListing::where('user_id', $userId)->where('status', MarketStatus::Sold)->latest()->get(),
            'expired' => MarketListing::where('user_id', $userId)->where('status', MarketStatus::Expired)->latest()->get(),
            default   => MarketListing::where('user_id', $userId)->active()->latest()->get(),
        };

        return view('livewire.market.my-listings', [
            'listings'    => $listings,
            'activeCount' => $activeCount,
            'maxActive'   => config('market.max_active_per_user', 5),
        ]);
    }
}
