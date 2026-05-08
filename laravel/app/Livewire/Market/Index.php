<?php

namespace App\Livewire\Market;

use App\Enums\PxgServer;
use App\Models\MarketListing;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class Index extends Component
{
    use WithPagination;

    #[Url]
    public string $search = '';

    #[Url]
    public string $server = '';

    #[Url]
    public string $priceMin = '';

    #[Url]
    public string $priceMax = '';

    #[Url]
    public bool $shinyOnly = false;

    public function updatedSearch(): void    { $this->resetPage(); }
    public function updatedServer(): void    { $this->resetPage(); }
    public function updatedPriceMin(): void  { $this->resetPage(); }
    public function updatedPriceMax(): void  { $this->resetPage(); }
    public function updatedShinyOnly(): void { $this->resetPage(); }

    public function clearFilters(): void
    {
        $this->reset('search', 'server', 'priceMin', 'priceMax', 'shinyOnly');
        $this->resetPage();
    }

    public function render()
    {
        $listings = MarketListing::active()
            ->with('user')
            ->when($this->search, fn ($q) => $q->where('species', 'like', "%{$this->search}%"))
            ->when($this->server, fn ($q) => $q->where('server', $this->server))
            ->when($this->priceMin !== '', fn ($q) => $q->where('price', '>=', (int) $this->priceMin))
            ->when($this->priceMax !== '', fn ($q) => $q->where('price', '<=', (int) $this->priceMax))
            ->when($this->shinyOnly, fn ($q) => $q->where('is_shiny', true))
            ->latest()
            ->paginate(24);

        return view('livewire.market.index', [
            'listings' => $listings,
            'servers'  => PxgServer::cases(),
        ]);
    }
}
