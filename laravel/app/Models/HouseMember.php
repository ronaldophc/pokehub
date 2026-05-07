<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HouseMember extends Model
{
    public $timestamps = false;

    protected $fillable = ['house_id', 'user_id', 'role', 'joined_at'];

    protected $casts = [
        'joined_at' => 'datetime',
    ];

    public function house(): BelongsTo
    {
        return $this->belongsTo(House::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function isOwner(): bool
    {
        return $this->role === 'owner';
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function canManage(): bool
    {
        return in_array($this->role, ['owner', 'admin']);
    }
}
