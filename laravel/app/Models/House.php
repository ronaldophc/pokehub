<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class House extends Model
{
    protected $fillable = ['name', 'slug', 'invite_code', 'owner_id'];

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function members(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'house_members')
            ->withPivot('role', 'joined_at')
            ->withTimestamps();
    }

    public function houseMemberships(): HasMany
    {
        return $this->hasMany(HouseMember::class);
    }

    public function pokemons(): HasMany
    {
        return $this->hasMany(Pokemon::class);
    }

    public static function generateSlug(string $name, ?int $ignoreId = null): string
    {
        $slug = Str::slug($name);
        $original = $slug;
        $count = 1;

        while (
            static::where('slug', $slug)
                ->when($ignoreId, fn($q) => $q->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $slug = "{$original}-{$count}";
            $count++;
        }

        return $slug;
    }

    public static function generateInviteCode(): string
    {
        do {
            $code = strtoupper(Str::random(7));
        } while (static::where('invite_code', $code)->exists());

        return $code;
    }
}
