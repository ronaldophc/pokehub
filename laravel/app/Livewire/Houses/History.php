<?php

namespace App\Livewire\Houses;

use App\Models\House;
use App\Models\PokemonCheckout;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class History extends Component
{
    use WithPagination;

    public House $house;

    #[Url]
    public string $search = '';

    public function mount(House $house): void
    {
        $this->authorize('view', $house);
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $checkouts = PokemonCheckout::query()
            ->with('pokemon', 'user')
            ->whereHas('pokemon', fn ($q) => $q->where('house_id', $this->house->id))
            ->when($this->search, function ($q) {
                $q->whereHas('pokemon', fn ($q2) =>
                    $q2->where('species', 'like', "%{$this->search}%")
                       ->orWhere('name', 'like', "%{$this->search}%")
                )->orWhereHas('user', fn ($q2) =>
                    $q2->where('name', 'like', "%{$this->search}%")
                );
            })
            ->orderByDesc('checked_out_at')
            ->paginate(20);

        return view('livewire.houses.history', compact('checkouts'));
    }
}
