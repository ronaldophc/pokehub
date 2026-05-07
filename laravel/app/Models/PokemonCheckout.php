<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PokemonCheckout extends Model
{
    protected $table = 'pokemon_checkouts';

    public $timestamps = false;

    protected $fillable = ['pokemon_id', 'user_id', 'checked_out_at', 'returned_at'];

    protected $casts = [
        'checked_out_at' => 'datetime',
        'returned_at' => 'datetime',
    ];

    public function pokemon(): BelongsTo
    {
        return $this->belongsTo(Pokemon::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
