<?php

namespace App\Livewire\Market;

use App\Enums\PxgServer;
use App\Livewire\Forms\MarketListingForm;
use App\Models\MarketListing;
use App\Models\Pokemon;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.app')]
class Edit extends Component
{
    use WithFileUploads;

    public MarketListing $listing;
    public MarketListingForm $form;
    public $screenshot = null;
    public bool $removeScreenshot = false;

    public function mount(MarketListing $listing): void
    {
        $this->authorize('update', $listing);
        $this->listing = $listing;
        $this->form->fromListing($listing);
    }

    public function save(): void
    {
        $this->authorize('update', $this->listing);
        $this->form->validate();

        if ($this->screenshot) {
            $this->validate(['screenshot' => 'image|max:2048|mimes:jpg,jpeg,png,webp']);
        }

        $attributes = $this->form->toUpdateAttributes();

        if ($this->screenshot) {
            if ($this->listing->screenshot_path) {
                Storage::disk('public')->delete($this->listing->screenshot_path);
            }
            $attributes['screenshot_path'] = $this->screenshot->store('market-screenshots', 'public');
        } elseif ($this->removeScreenshot) {
            if ($this->listing->screenshot_path) {
                Storage::disk('public')->delete($this->listing->screenshot_path);
            }
            $attributes['screenshot_path'] = null;
        }

        $this->listing->update($attributes);

        session()->flash('toast', ['message' => 'Anúncio atualizado!', 'type' => 'success']);
        $this->redirect(route('market.show', $this->listing), navigate: true);
    }

    public function render()
    {
        return view('livewire.market.edit', [
            'heldX'   => Pokemon::HELD_X,
            'heldY'   => Pokemon::HELD_Y,
            'servers' => PxgServer::cases(),
        ]);
    }
}
