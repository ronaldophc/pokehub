<?php

namespace App\Events;

use App\Models\Pokemon;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PokemonCheckedOut implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public Pokemon $pokemon) {}

    public function broadcastOn(): Channel
    {
        return new PrivateChannel('house.' . $this->pokemon->house_id);
    }

    public function broadcastWith(): array
    {
        return [
            'pokemon_id' => $this->pokemon->id,
            'holder_id' => $this->pokemon->current_holder_id,
            'holder_name' => $this->pokemon->currentHolder->name,
            'held_since' => $this->pokemon->held_since->toISOString(),
        ];
    }
}
