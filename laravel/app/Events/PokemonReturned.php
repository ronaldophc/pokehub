<?php

namespace App\Events;

use App\Models\Pokemon;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PokemonReturned implements ShouldBroadcast
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
        ];
    }
}
