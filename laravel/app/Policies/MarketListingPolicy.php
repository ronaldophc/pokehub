<?php

namespace App\Policies;

use App\Enums\MarketStatus;
use App\Models\MarketListing;
use App\Models\User;

class MarketListingPolicy
{
    public function update(User $user, MarketListing $listing): bool
    {
        return $user->id === $listing->user_id;
    }

    public function delete(User $user, MarketListing $listing): bool
    {
        return $user->id === $listing->user_id;
    }

    public function markSold(User $user, MarketListing $listing): bool
    {
        return $user->id === $listing->user_id && $listing->status === MarketStatus::Active;
    }
}
