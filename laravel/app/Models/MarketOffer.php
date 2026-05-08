<?php

namespace App\Models;

use App\Enums\MarketOfferStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MarketOffer extends Model
{
    protected $fillable = ['listing_id', 'user_id', 'message', 'status'];

    protected $casts = [
        'status' => MarketOfferStatus::class,
    ];

    public function listing(): BelongsTo
    {
        return $this->belongsTo(MarketListing::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
