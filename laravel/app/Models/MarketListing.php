<?php

namespace App\Models;

use App\Enums\MarketStatus;
use App\Enums\PxgServer;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MarketListing extends Model
{
    protected $fillable = [
        'user_id', 'species', 'is_shiny', 'tm',
        'held_x_name', 'held_x_tier', 'held_y_name', 'held_y_tier',
        'price', 'server', 'contact_nick', 'contact_discord',
        'notes', 'screenshot_path', 'status', 'expires_at',
    ];

    protected function casts(): array
    {
        return [
            'is_shiny'    => 'boolean',
            'held_x_tier' => 'integer',
            'held_y_tier' => 'integer',
            'price'       => 'integer',
            'expires_at'  => 'datetime',
            'server'      => PxgServer::class,
            'status'      => MarketStatus::class,
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', MarketStatus::Active)->where('expires_at', '>', now());
    }

    public function isActive(): bool
    {
        return $this->status === MarketStatus::Active && $this->expires_at->isFuture();
    }
}
