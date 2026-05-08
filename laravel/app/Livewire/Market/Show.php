<?php

namespace App\Livewire\Market;

use App\Enums\MarketOfferStatus;
use App\Enums\MarketStatus;
use App\Models\MarketListing;
use App\Models\MarketOffer;
use App\Models\Pokemon;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Component;

#[Layout('layouts.app')]
class Show extends Component
{
    public MarketListing $listing;

    #[Validate('required|string|min:3|max:500', as: 'oferta')]
    public string $offerMessage = '';

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

    public function submitOffer(): void
    {
        abort_if(! auth()->check(), 403);
        abort_if($this->listing->user_id === auth()->id(), 403);
        abort_if(! $this->listing->isActive(), 403);

        $this->validateOnly('offerMessage');

        $alreadyPending = MarketOffer::where('listing_id', $this->listing->id)
            ->where('user_id', auth()->id())
            ->where('status', MarketOfferStatus::Pending)
            ->exists();

        if ($alreadyPending) {
            $this->addError('offerMessage', 'Você já tem uma oferta pendente neste anúncio.');
            return;
        }

        MarketOffer::create([
            'listing_id' => $this->listing->id,
            'user_id'    => auth()->id(),
            'message'    => $this->offerMessage,
            'status'     => MarketOfferStatus::Pending,
        ]);

        $this->offerMessage = '';
        $this->dispatch('notify', message: 'Oferta enviada!', type: 'success');
    }

    public function acceptOffer(int $offerId): void
    {
        $offer = MarketOffer::findOrFail($offerId);
        abort_if($offer->listing->user_id !== auth()->id(), 403);

        MarketOffer::where('listing_id', $this->listing->id)
            ->where('id', '!=', $offerId)
            ->where('status', MarketOfferStatus::Pending)
            ->update(['status' => MarketOfferStatus::Rejected]);

        $offer->update(['status' => MarketOfferStatus::Accepted]);
        $this->listing->update(['status' => MarketStatus::Sold]);

        $this->dispatch('notify', message: 'Oferta aceita! Anúncio marcado como vendido.', type: 'success');
    }

    public function rejectOffer(int $offerId): void
    {
        $offer = MarketOffer::findOrFail($offerId);
        abort_if($offer->listing->user_id !== auth()->id(), 403);

        $offer->update(['status' => MarketOfferStatus::Rejected]);
        $this->dispatch('notify', message: 'Oferta recusada.', type: 'info');
    }

    public function render()
    {
        $isOwner = auth()->check() && auth()->id() === $this->listing->user_id;

        $offers = $isOwner
            ? $this->listing->offers()->with('user')->latest()->get()
            : collect();

        $myOffer = ! $isOwner && auth()->check()
            ? MarketOffer::where('listing_id', $this->listing->id)
                ->where('user_id', auth()->id())
                ->latest()
                ->first()
            : null;

        return view('livewire.market.show', [
            'spriteUrl' => $this->listing->sprite_url ?: Pokemon::spriteUrl($this->listing->species),
            'isOwner'   => $isOwner,
            'offers'    => $offers,
            'myOffer'   => $myOffer,
        ]);
    }
}
