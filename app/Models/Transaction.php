<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'buyer_id',
        'seller_id',
        'item_id',
        'status',
        'meetup_location',
        'meetup_time',
    ];

    protected $casts = [
        'meetup_time' => 'datetime',
    ];

    /**
     * Relationships
     */

    public function buyer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }

    public function seller(): BelongsTo
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }

    public function ratings(): HasMany
    {
        return $this->hasMany(Rating::class);
    }

    /**
     * Helper Methods
     */

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }

    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    public function getStatusBadgeColor(): string
    {
        return match($this->status) {
            'pending' => 'yellow',
            'approved' => 'blue',
            'rejected' => 'red',
            'completed' => 'green',
            default => 'gray',
        };
    }

    public function canBeApproved(): bool
    {
        return $this->status === 'pending' && $this->seller_id === auth()->id();
    }

    public function canBeRejected(): bool
    {
        return $this->status === 'pending' && $this->seller_id === auth()->id();
    }

    public function canBeCompleted(): bool
    {
        return $this->status === 'approved' && $this->seller_id === auth()->id();
    }

    public function hasBeenRated($userId): bool
    {
        return $this->ratings()->where('reviewer_id', $userId)->exists();
    }
}
