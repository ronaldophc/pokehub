<?php

use App\Models\HouseMember;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('house.{houseId}', function ($user, $houseId) {
    return HouseMember::where('house_id', $houseId)
        ->where('user_id', $user->id)
        ->exists();
});
