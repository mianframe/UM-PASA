<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'category',
        'description',
        'department',
        'program',
        'course_code',
        'listing_type',
        'condition',
        'price',
        'image',
        'status',
    ];

    /**
     * Relationships
     */

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    /**
     * Helper Methods
     */

    public function isAvailable(): bool
    {
        return $this->status === 'available';
    }

    public function isSoldByUser($userId): bool
    {
        return $this->user_id === $userId;
    }

    public function hasPendingRequest(): bool
    {
        return $this->transactions()->where('status', 'pending')->exists();
    }

    public function getStatusBadgeColor(): string
    {
        return match($this->status) {
            'available' => 'green',
            'pending' => 'yellow',
            'sold' => 'red',
            default => 'gray',
        };
    }

    public function getListingTypeLabel(): string
    {
        return match($this->listing_type) {
            'sell' => 'For Sale',
            'rent' => 'For Rent',
            'swap' => 'For Swap',
            default => 'Unknown',
        };
    }
}
