<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pokemon extends Model
{
    protected $table = 'pokemons';

    protected $fillable = [
        'house_id',
        'owner_id',
        'name',
        'species',
        'level',
        'sprite_url',
        'is_shiny',
        'notes',
        'tm',
        'held_x_name',
        'held_x_tier',
        'held_y_name',
        'held_y_tier',
        'extra_helds',
        'current_holder_id',
        'held_since',
    ];

    protected $casts = [
        'held_since' => 'datetime',
        'level' => 'integer',
        'is_shiny' => 'boolean',
        'held_x_tier'  => 'integer',
        'held_y_tier'  => 'integer',
        'extra_helds'  => 'array',
    ];

    public const HELD_X = [
        'X-Attack'    => [1,2,3,4,5,6,7,8],
        'X-Critical'  => [1,2,3,4,5,6,7,8],
        'X-Defense'   => [1,2,3,4,5,6,7,8],
        'X-Block'     => [1,2,3,4,5,6,7,8],
        'X-Boost'     => [1,2,3,4,5,6,7],
        'X-Vitality'  => [1,2,3,4,5,6,7],
        'X-Harden'    => [1,2,3,4,5,6,7],
        'X-Lucky'     => [1,2,3,4,5,6,7,9],
        'X-Experience'=> [1,2,3,4,5,6,7],
        'X-Accuracy'  => [1,2,3,4,5,6,7],
        'X-Return'    => [1,2,3,4,5,6,7],
        'X-Poison'    => [1,2,3,4,5,6,7],
        'X-Hellfire'  => [1,2,3,4,5,6,7],
        'X-Rage'      => [1,2,3,4,5,6,7],
        'X-Strafe'    => [1,2,3,4,5,6,7],
        'X-Agility'   => [1,2,3,4,5,6,7],
        'X-Haste'     => [1,2,3,4,5,6,7],
        'X-Elemental' => [1,2,3,4,5,6,7],
        'X-Cooldown'  => [3,5,7],
        'X-Blink'     => [5],
        'X-Upgrade'   => [4,5,6,7,8],
    ];

    public const HELD_Y = [
        'Y-Teleport'     => [1,2,3,4,5,6,7,8],
        'Y-Wing'         => [1,2,3,4,5,6,7,8],
        'Y-Cure'         => [1,2,3,4,5,6,7],
        'Y-Control'      => [1,2,3,4,5,6,7],
        'Y-Regeneration' => [1,2,3,4,5,6,7],
        'Y-Antiburn'     => [6],
        'Y-Antipoison'   => [4],
        'Y-Ghost'        => [1],
        'Y-Light'        => [1],
        'Y-Headbutt'     => [1],
        'Y-Dig'          => [1],
        'Y-Smash'        => [1],
        'Y-Cut'          => [1],
        'Y-Antiself'     => [7],
        'Y-Blur'         => [7],
    ];

    public function house(): BelongsTo
    {
        return $this->belongsTo(House::class);
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function currentHolder(): BelongsTo
    {
        return $this->belongsTo(User::class, 'current_holder_id');
    }

    public function checkouts(): HasMany
    {
        return $this->hasMany(PokemonCheckout::class);
    }

    public function isAvailable(): bool
    {
        return $this->current_holder_id === null;
    }

    public static function spriteUrl(string $species): string
    {
        $slug = Str::slug($species);
        return "https://img.pokemondb.net/sprites/home/normal/{$slug}.png";
    }
}
