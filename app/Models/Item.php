<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Item extends Model
{
    use HasFactory;

    public const TYPE_SELL = 'sell';

    public const TYPE_RENT = 'rent';

    public const STATUS_AVAILABLE = 'available';

    public const STATUS_PENDING = 'pending';

    public const STATUS_SOLD = 'sold';

    public const MODERATION_PENDING = 'pending';

    public const MODERATION_APPROVED = 'approved';

    public const MODERATION_REJECTED = 'rejected';

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
        return $this->status === self::STATUS_AVAILABLE
            && $this->moderation_status === self::MODERATION_APPROVED;
    }

    public function isSoldByUser($userId): bool
    {
        return $this->user_id === $userId;
    }

    public function hasPendingRequest(): bool
    {
        return $this->transactions()->where('status', Transaction::STATUS_PENDING)->exists();
    }

    public function getStatusBadgeColor(): string
    {
        return match ($this->status) {
            self::STATUS_AVAILABLE => 'green',
            self::STATUS_PENDING => 'yellow',
            self::STATUS_SOLD => 'red',
            default => 'gray',
        };
    }

    public function getModerationStatusLabel(): string
    {
        return match ($this->moderation_status) {
            self::MODERATION_APPROVED => 'Approved',
            self::MODERATION_REJECTED => 'Rejected',
            default => 'Pending Review',
        };
    }

    public function getListingTypeLabel(): string
    {
        return match ($this->listing_type) {
            self::TYPE_SELL => 'For Sale',
            self::TYPE_RENT => 'For Rent',
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
