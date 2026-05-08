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
    public bool $shinyOnly = false;

    #[Url]
    public string $tm = '';

    public function updatedSearch(): void    { $this->resetPage(); }
    public function updatedServer(): void    { $this->resetPage(); }
    public function updatedShinyOnly(): void { $this->resetPage(); }
    public function updatedTm(): void        { $this->resetPage(); }

    public function clearFilters(): void
    {
        $this->reset('search', 'server', 'shinyOnly', 'tm');
        $this->resetPage();
    }

    public function render()
    {
        $listings = MarketListing::active()
            ->with('user')
            ->when($this->search, fn ($q) => $q->where('species', 'like', "%{$this->search}%"))
            ->when($this->server, fn ($q) => $q->where('server', $this->server))
            ->when($this->shinyOnly, fn ($q) => $q->where('is_shiny', true))
            ->when($this->tm, fn ($q) => $q->where('tm', $this->tm))
            ->latest()
            ->paginate(24);

        return view('livewire.market.index', [
            'listings' => $listings,
            'servers'  => PxgServer::cases(),
        ]);
    }
}
