<?php

namespace App\Livewire\Houses;

use App\Livewire\Forms\PokemonForm;
use App\Models\House;
use App\Models\Pokemon;
use App\Services\PokemonCheckoutService;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Attributes\Url;
use Livewire\Component;

#[Layout('layouts.app')]
class Show extends Component
{
    public House $house;
    public PokemonForm $form;

    #[Url]
    public string $search = '';

    #[Url]
    public string $filterStatus = 'all';

    public bool $showForm = false;
    public ?int $editingId = null;

    public function mount(House $house): void
    {
        $this->authorize('view', $house);
    }

    public function canManage(): bool
    {
        return auth()->user()->can('update', $this->house);
    }

    public function checkout(Pokemon $pokemon): void
    {
        $this->authorize('checkout', $pokemon);

        $success = app(PokemonCheckoutService::class)->checkout($pokemon, auth()->user());

        if (! $success) {
            $this->addError('checkout', __('This pokémon has already been taken by another player.'));
            return;
        }

        $this->dispatch('notify', message: __('Pokémon taken successfully!'), type: 'success');
    }

    public function return(Pokemon $pokemon): void
    {
        $this->authorize('return', $pokemon);

        app(PokemonCheckoutService::class)->return($pokemon);

        $this->dispatch('notify', message: __('Pokémon returned.'), type: 'success');
    }

    #[On('echo-private:house.{house.id},PokemonCheckedOut')]
    #[On('echo-private:house.{house.id},PokemonReturned')]
    public function refreshPokemons(): void {}

    public function openCreate(): void
    {
        $this->form->reset();
        $this->form->resetValidation();
        $this->showForm = true;
        $this->editingId = null;
    }

    public function openEdit(Pokemon $pokemon): void
    {
        $this->authorize('update', $pokemon);
        $this->form->fillFrom($pokemon);
        $this->editingId = $pokemon->id;
        $this->showForm = true;
    }

    public function save(): void
    {
        $this->form->validate();

        if ($this->editingId) {
            $pokemon = Pokemon::findOrFail($this->editingId);
            $this->authorize('update', $pokemon);
            $pokemon->update($this->form->toPokemonAttributes());
            $this->dispatch('notify', message: __('Pokémon updated.'), type: 'success');
        } else {
            $this->authorize('create', new Pokemon(['house_id' => $this->house->id]));
            $this->house->pokemons()->create($this->form->toPokemonAttributes());
            $this->dispatch('notify', message: __('Pokémon registered!'), type: 'success');
        }

        $this->resetForm();
    }

    public function delete(Pokemon $pokemon): void
    {
        $this->authorize('delete', $pokemon);
        $pokemon->delete();
        $this->dispatch('notify', message: __('Pokémon removed.'), type: 'success');
    }

    public function addExtraHeld(): void
    {
        $this->form->extraHelds[] = ['name' => '', 'tier' => null];
    }

    public function removeExtraHeld(int $index): void
    {
        unset($this->form->extraHelds[$index]);
        $this->form->extraHelds = array_values($this->form->extraHelds);
    }

    public function cancelForm(): void
    {
        $this->resetForm();
    }

    private function resetForm(): void
    {
        $this->showForm = false;
        $this->editingId = null;
        $this->form->reset();
        $this->form->resetValidation();
    }

    public function render()
    {
        $pokemons = $this->house->pokemons()->with('currentHolder', 'owner')
            ->when($this->search, fn ($q) =>
                $q->where('species', 'like', "%{$this->search}%")
                  ->orWhere('name', 'like', "%{$this->search}%")
            )
            ->when($this->filterStatus === 'free', fn ($q) => $q->whereNull('current_holder_id'))
            ->when($this->filterStatus === 'taken', fn ($q) => $q->whereNotNull('current_holder_id'))
            ->orderBy('species')
            ->get();

        $members = $this->house->houseMemberships()->with('user')->get();
        $heldX    = Pokemon::HELD_X;
        $heldY    = Pokemon::HELD_Y;
        $allItems = Pokemon::HELD_X + Pokemon::HELD_Y;

        return view('livewire.houses.show', compact('pokemons', 'members', 'heldX', 'heldY', 'allItems'));
    }
}
