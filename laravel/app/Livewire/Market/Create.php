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
class Create extends Component
{
    use WithFileUploads;

    public MarketListingForm $form;
    public $screenshot = null;

    public function save(): void
    {
        $activeCount = MarketListing::where('user_id', auth()->id())
            ->active()
            ->count();

        $max = config('market.max_active_per_user', 5);

        if ($activeCount >= $max) {
            $this->addError('limit', "Você atingiu o limite de {$max} anúncios ativos.");
            return;
        }

        $this->form->validate();

        if ($this->screenshot) {
            $this->validate(['screenshot' => 'image|max:2048|mimes:jpg,jpeg,png,webp']);
        }

        $attributes = $this->form->toListingAttributes();
        $attributes['user_id']      = auth()->id();
        $attributes['contact_nick'] = auth()->user()->name;

        if ($this->screenshot) {
            $attributes['screenshot_path'] = $this->screenshot->store('market-screenshots', 'public');
        }

        MarketListing::create($attributes);

        session()->flash('toast', ['message' => 'Anúncio publicado!', 'type' => 'success']);
        $this->redirect(route('market.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.market.create', [
            'heldX'   => Pokemon::HELD_X,
            'heldY'   => Pokemon::HELD_Y,
            'servers' => PxgServer::cases(),
        ]);
    }
}
