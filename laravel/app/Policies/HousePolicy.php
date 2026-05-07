<?php

namespace App\Policies;

use App\Models\House;
use App\Models\HouseMember;
use App\Models\User;

class HousePolicy
{
    public function view(User $user, House $house): bool
    {
        return HouseMember::where('house_id', $house->id)
            ->where('user_id', $user->id)
            ->exists();
    }

    public function update(User $user, House $house): bool
    {
        return HouseMember::where('house_id', $house->id)
            ->where('user_id', $user->id)
            ->whereIn('role', ['owner', 'admin'])
            ->exists();
    }

    public function delete(User $user, House $house): bool
    {
        return $house->owner_id === $user->id;
    }

    public function manageMembers(User $user, House $house): bool
    {
        return $this->update($user, $house);
    }
}
