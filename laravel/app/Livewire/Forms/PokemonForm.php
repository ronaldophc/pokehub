<?php

namespace App\Livewire\Forms;

use App\Models\Pokemon;
use Livewire\Attributes\Validate;
use Livewire\Form;

class PokemonForm extends Form
{
    #[Validate('nullable|string|min:2|max:60', as: 'apelido')]
    public ?string $name = null;

    #[Validate('required|string|min:2|max:60', message: 'Selecione um pokémon válido.')]
    public string $species = '';

    #[Validate('nullable|string|max:500', as: 'observações')]
    public ?string $notes = null;

    #[Validate('nullable|integer')]
    public ?int $ownerId = null;

    public bool $isShiny = false;

    #[Validate('nullable|string|in:TM Tank,TM DPS,TM Burst,TM Off-Tank', as: 'TM')]
    public ?string $tm = null;

    #[Validate('nullable|string|max:50', as: 'held X')]
    public ?string $heldXName = null;

    #[Validate('nullable|integer')]
    public ?int $heldXTier = null;

    #[Validate('nullable|string|max:50', as: 'held Y')]
    public ?string $heldYName = null;

    #[Validate('nullable|integer')]
    public ?int $heldYTier = null;

    public array $extraHelds = [];

    public function fillFrom(Pokemon $pokemon): void
    {
        $this->name = $pokemon->name;
        $this->species = $pokemon->species;
        $this->notes = $pokemon->notes;
        $this->ownerId = $pokemon->owner_id;
        $this->isShiny = (bool) $pokemon->is_shiny;
        $this->tm = $pokemon->tm;
        $this->heldXName = $pokemon->held_x_name;
        $this->heldXTier = $pokemon->held_x_tier;
        $this->heldYName  = $pokemon->held_y_name;
        $this->heldYTier  = $pokemon->held_y_tier;
        $this->extraHelds = $pokemon->extra_helds ?? [];
    }

    public function toPokemonAttributes(): array
    {
        return [
            'name'        => $this->name ?: null,
            'species'     => $this->species,
            'sprite_url'  => Pokemon::spriteUrl($this->species),
            'notes'       => $this->notes ?: null,
            'owner_id'    => $this->ownerId ?: null,
            'is_shiny'    => $this->isShiny,
            'tm'          => $this->tm ?: null,
            'held_x_name' => $this->heldXName ?: null,
            'held_x_tier' => $this->heldXName ? $this->heldXTier : null,
            'held_y_name'  => $this->heldYName ?: null,
            'held_y_tier'  => $this->heldYName ? $this->heldYTier : null,
            'extra_helds'  => $this->normalizedExtraHelds(),
        ];
    }

    private function normalizedExtraHelds(): ?array
    {
        $filtered = array_values(array_filter(
            $this->extraHelds,
            fn ($h) => ! empty($h['name'])
        ));

        return $filtered ?: null;
    }
}
