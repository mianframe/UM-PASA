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
        'accepted_payment_methods',
        'minimum_rental_days',
        'maximum_rental_days',
        'daily_rental_rate',
        'rental_duration_days',
        'condition',
        'price',
        'image',
        'status',
        'moderation_status',
        'rejection_reason',
    ];

    protected $casts = [
        'accepted_payment_methods' => 'array',
        'daily_rental_rate' => 'decimal:2',
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
        return $this->status === 'available' && $this->moderation_status === 'approved';
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
        return match ($this->status) {
            'available' => 'green',
            'pending' => 'yellow',
            'sold' => 'red',
            default => 'gray',
        };
    }

    public function getModerationStatusLabel(): string
    {
        return match ($this->moderation_status) {
            'approved' => 'Approved',
            'rejected' => 'Rejected',
            default => 'Pending Review',
        };
    }

    public function getListingTypeLabel(): string
    {
        return match ($this->listing_type) {
            'sell' => 'For Sale',
            'rent' => 'For Rent',
            default => 'Unknown',
        };
    }

    public static function paymentMethodOptions(): array
    {
        return [
            'gcash' => 'GCash',
            'maya' => 'Maya',
            'bank_transfer' => 'Bank Transfer',
            'cash_on_pickup' => 'Cash on Pickup',
            'other' => 'Other payment method',
        ];
    }

    public function getAcceptedPaymentMethodLabels(): array
    {
        $methods = $this->accepted_payment_methods ?: array_keys(self::paymentMethodOptions());

        return collect($methods)
            ->map(fn ($method) => self::paymentMethodOptions()[$method] ?? $method)
            ->values()
            ->all();
    }
}
