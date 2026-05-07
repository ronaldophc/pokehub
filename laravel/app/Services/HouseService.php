<?php

namespace App\Services;

use App\Models\House;
use App\Models\HouseMember;
use App\Models\PokemonCheckout;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class HouseService
{
    public function create(string $name, User $owner): House
    {
        return DB::transaction(function () use ($name, $owner) {
            $house = House::create([
                'name' => $name,
                'slug' => House::generateSlug($name),
                'invite_code' => House::generateInviteCode(),
                'owner_id' => $owner->id,
            ]);

            HouseMember::create([
                'house_id' => $house->id,
                'user_id' => $owner->id,
                'role' => 'owner',
            ]);

            return $house;
        });
    }

    public function join(House $house, User $user): void
    {
        HouseMember::create([
            'house_id' => $house->id,
            'user_id' => $user->id,
            'role' => 'member',
        ]);
    }

    public function delete(House $house): void
    {
        DB::transaction(function () use ($house) {
            PokemonCheckout::whereIn('pokemon_id', $house->pokemons()->pluck('id'))->delete();
            $house->pokemons()->delete();
            $house->houseMemberships()->delete();
            $house->delete();
        });
    }
}
