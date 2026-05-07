<?php

namespace App\Services;

use App\Events\PokemonCheckedOut;
use App\Events\PokemonReturned;
use App\Models\Pokemon;
use App\Models\PokemonCheckout;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class PokemonCheckoutService
{
    public function checkout(Pokemon $pokemon, User $user): bool
    {
        $taken = false;

        DB::transaction(function () use ($pokemon, $user, &$taken) {
            $fresh = Pokemon::lockForUpdate()->findOrFail($pokemon->id);

            if ($fresh->current_holder_id !== null) {
                $taken = true;
                return;
            }

            $fresh->update([
                'current_holder_id' => $user->id,
                'held_since' => now(),
            ]);

            PokemonCheckout::create([
                'pokemon_id' => $fresh->id,
                'user_id' => $user->id,
                'checked_out_at' => now(),
            ]);

            $fresh->load('currentHolder');
            broadcast(new PokemonCheckedOut($fresh))->toOthers();
        });

        return ! $taken;
    }

    public function return(Pokemon $pokemon): void
    {
        DB::transaction(function () use ($pokemon) {
            $fresh = Pokemon::lockForUpdate()->findOrFail($pokemon->id);

            $checkout = PokemonCheckout::where('pokemon_id', $fresh->id)
                ->whereNull('returned_at')
                ->first();

            if ($checkout) {
                if ($checkout->checked_out_at->diffInSeconds(now()) < 60) {
                    $checkout->delete();
                } else {
                    $checkout->update(['returned_at' => now()]);
                }
            }

            $fresh->update(['current_holder_id' => null, 'held_since' => null]);

            broadcast(new PokemonReturned($fresh))->toOthers();
        });
    }
}
