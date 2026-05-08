<?php

namespace App\Livewire\Forms;

use App\Models\Pokemon;
use Livewire\Attributes\Validate;
use Livewire\Form;

class MarketListingForm extends Form
{
    #[Validate('required|string|min:2|max:60', message: 'Selecione um pokémon válido.')]
    public string $species = '';

    public string $spriteUrl = '';

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

    #[Validate('required|string|max:100', as: 'preço')]
    public string $price = '';

    #[Validate('required|string', as: 'servidor')]
    public string $server = '';

    #[Validate('required|string|min:2|max:100', as: 'nick in-game')]
    public string $contactNick = '';

    #[Validate('nullable|string|max:100', as: 'Discord')]
    public ?string $contactDiscord = null;

    #[Validate('nullable|string|max:500', as: 'observações')]
    public ?string $notes = null;

    public function toListingAttributes(): array
    {
        return [
            'species'         => $this->species,
            'sprite_url'      => $this->spriteUrl ?: null,
            'is_shiny'        => $this->isShiny,
            'tm'              => $this->tm ?: null,
            'held_x_name'     => $this->heldXName ?: null,
            'held_x_tier'     => $this->heldXName ? $this->heldXTier : null,
            'held_y_name'     => $this->heldYName ?: null,
            'held_y_tier'     => $this->heldYName ? $this->heldYTier : null,
            'price'           => $this->price,
            'server'          => $this->server,
            'contact_nick'    => $this->contactNick,
            'contact_discord' => $this->contactDiscord ?: null,
            'notes'           => $this->notes ?: null,
            'expires_at'      => now()->addDays(config('market.expiry_days', 7)),
        ];
    }
}
