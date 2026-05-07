<?php

namespace App\Livewire\Houses;

use App\Models\House;
use App\Models\HouseMember;
use App\Services\HouseService;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Attributes\Validate;
use Livewire\Component;

#[Layout('layouts.app')]
class Index extends Component
{
    public bool $showForm = false;
    public bool $showJoinForm = false;

    #[Url]
    public string $search = '';

    #[Validate('required|string|min:3|max:60|unique:houses,name')]
    public string $name = '';

    #[Validate('required|string|size:7')]
    public string $joinCode = '';

    public function joinByCode(): void
    {
        $this->validateOnly('joinCode');

        $house = House::where('invite_code', strtoupper($this->joinCode))->first();

        if (! $house) {
            $this->addError('joinCode', __('Invalid code.'));
            return;
        }

        if (HouseMember::where('house_id', $house->id)->where('user_id', auth()->id())->exists()) {
            $this->redirect(route('houses.show', $house), navigate: true);
            return;
        }

        app(HouseService::class)->join($house, auth()->user());

        $this->dispatch('notify', message: __('You joined the house!'), type: 'success');
        $this->redirect(route('houses.show', $house), navigate: true);
    }

    public function create(): void
    {
        $this->validateOnly('name');

        $house = app(HouseService::class)->create($this->name, auth()->user());

        session()->flash('toast', ['message' => __('House created!'), 'type' => 'success']);
        $this->redirect(route('houses.show', $house), navigate: true);
    }

    public function render()
    {
        $houses = auth()->user()->houses()->with('owner')
            ->when($this->search, fn ($q) => $q->where('name', 'like', "%{$this->search}%"))
            ->get();

        return view('livewire.houses.index', compact('houses'));
    }
}
