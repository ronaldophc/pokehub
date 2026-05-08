<?php

namespace App\Livewire\Market;

use App\Enums\MarketStatus;
use App\Models\MarketListing;
use App\Models\Pokemon;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class Show extends Component
{
    public MarketListing $listing;

    public function mount(MarketListing $listing): void
    {
        $this->listing = $listing;
    }

    public function markSold(): void
    {
        $this->authorize('markSold', $this->listing);
        $this->listing->update(['status' => MarketStatus::Sold]);
        $this->dispatch('notify', message: 'Anúncio marcado como vendido.', type: 'success');
    }

    public function delete(): void
    {
        $this->authorize('delete', $this->listing);

        if ($this->listing->screenshot_path) {
            Storage::disk('public')->delete($this->listing->screenshot_path);
        }

        $this->listing->delete();

        session()->flash('toast', ['message' => 'Anúncio removido.', 'type' => 'success']);
        $this->redirect(route('market.my-listings'), navigate: true);
    }

    public function render()
    {
        return view('livewire.market.show', [
            'spriteUrl' => $this->listing->sprite_url ?: Pokemon::spriteUrl($this->listing->species),
        ]);
    }
}
