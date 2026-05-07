<?php

namespace App\Policies;

use App\Models\HouseMember;
use App\Models\Pokemon;
use App\Models\User;

class PokemonPolicy
{
    public function view(User $user, Pokemon $pokemon): bool
    {
        return HouseMember::where('house_id', $pokemon->house_id)
            ->where('user_id', $user->id)
            ->exists();
    }

    public function create(User $user, Pokemon $pokemon): bool
    {
        return HouseMember::where('house_id', $pokemon->house_id)
            ->where('user_id', $user->id)
            ->whereIn('role', ['owner', 'admin'])
            ->exists();
    }

    public function update(User $user, Pokemon $pokemon): bool
    {
        return $this->create($user, $pokemon);
    }

    public function delete(User $user, Pokemon $pokemon): bool
    {
        return $this->create($user, $pokemon);
    }

    public function checkout(User $user, Pokemon $pokemon): bool
    {
        return HouseMember::where('house_id', $pokemon->house_id)
            ->where('user_id', $user->id)
            ->exists();
    }

    public function return(User $user, Pokemon $pokemon): bool
    {
        return $pokemon->current_holder_id === $user->id;
    }
}
