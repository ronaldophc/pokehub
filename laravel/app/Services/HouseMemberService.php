<?php

namespace App\Services;

use App\Models\House;
use App\Models\HouseMember;
use Illuminate\Support\Facades\DB;

class HouseMemberService
{
    public function promoteToAdmin(House $house, int $userId): void
    {
        HouseMember::where('house_id', $house->id)
            ->where('user_id', $userId)
            ->where('role', 'member')
            ->update(['role' => 'admin']);
    }

    public function demoteToMember(House $house, int $userId): void
    {
        HouseMember::where('house_id', $house->id)
            ->where('user_id', $userId)
            ->where('role', 'admin')
            ->update(['role' => 'member']);
    }

    public function transferOwnership(House $house, int $newOwnerId): void
    {
        DB::transaction(function () use ($house, $newOwnerId) {
            HouseMember::where('house_id', $house->id)
                ->where('user_id', $house->owner_id)
                ->update(['role' => 'admin']);

            HouseMember::where('house_id', $house->id)
                ->where('user_id', $newOwnerId)
                ->update(['role' => 'owner']);

            $house->update(['owner_id' => $newOwnerId]);
        });
    }

    public function remove(House $house, int $userId): void
    {
        HouseMember::where('house_id', $house->id)
            ->where('user_id', $userId)
            ->delete();
    }
}
