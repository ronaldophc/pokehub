<?php

namespace App\Livewire\Houses;

use App\Models\House;
use App\Models\HouseMember;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class Join extends Component
{
    public House $house;

    public function mount(string $code): void
    {
        $this->house = House::where('invite_code', $code)->firstOrFail();

        if (HouseMember::where('house_id', $this->house->id)->where('user_id', auth()->id())->exists()) {
            $this->redirect(route('houses.show', $this->house), navigate: true);
        }
    }

    public function join(): void
    {
        HouseMember::create([
            'house_id' => $this->house->id,
            'user_id' => auth()->id(),
            'role' => 'member',
        ]);

        session()->flash('toast', ['message' => __('You joined the house!'), 'type' => 'success']);
        $this->redirect(route('houses.show', $this->house), navigate: true);
    }

    public function render()
    {
        return view('livewire.houses.join');
    }
}
