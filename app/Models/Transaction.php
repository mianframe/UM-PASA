<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Transaction extends Model
{
    use HasFactory;

    public const STATUS_PENDING = 'pending';

    public const STATUS_APPROVED = 'approved';

    public const STATUS_REJECTED = 'rejected';

    public const STATUS_COMPLETED = 'completed';

    protected $fillable = [
        'buyer_id',
        'seller_id',
        'item_id',
        'status',
        'payment_method',
        'other_payment_method',
        'rental_duration_days',
        'rental_due_date',
        'payment_proof',
        'payment_proof_uploaded_at',
        'meetup_location',
        'meetup_time',
    ];

    protected $casts = [
        'meetup_time' => 'datetime',
        'rental_due_date' => 'date',
        'payment_proof_uploaded_at' => 'datetime',
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
        return $this->status === self::STATUS_PENDING;
    }

    public function isApproved(): bool
    {
        return $this->status === self::STATUS_APPROVED;
    }

    public function isRejected(): bool
    {
        return $this->status === self::STATUS_REJECTED;
    }

    public function isCompleted(): bool
    {
        return $this->status === self::STATUS_COMPLETED;
    }

    public function getStatusBadgeColor(): string
    {
        return match ($this->status) {
            self::STATUS_PENDING => 'yellow',
            self::STATUS_APPROVED => 'blue',
            self::STATUS_REJECTED => 'red',
            self::STATUS_COMPLETED => 'green',
            default => 'gray',
        };
    }

    public function getPaymentMethodLabel(): string
    {
        if ($this->payment_method === 'other') {
            return $this->other_payment_method ?: 'Other payment method';
        }

        return match ($this->payment_method) {
            'gcash' => 'GCash',
            'maya' => 'Maya',
            'bank_transfer' => 'Bank Transfer',
            'cash_on_pickup' => 'Cash on Pickup',
            default => 'Not specified',
        };
    }

    public function getPaymentProofStatusLabel(): string
    {
        return $this->payment_proof ? 'Uploaded' : 'Not uploaded';
    }

    public function getTrackingStatusLabel(): string
    {
        if ($this->status === self::STATUS_COMPLETED) {
            return 'Completed';
        }

        if ($this->status === self::STATUS_REJECTED) {
            return 'Rejected';
        }

        if ($this->item?->listing_type === Item::TYPE_RENT && $this->rental_due_date) {
            if ($this->rental_due_date->isPast() && ! $this->rental_due_date->isToday()) {
                return 'Rental overdue';
            }

            if ($this->rental_due_date->isToday()) {
                return 'Rental due today';
            }

            if (now()->diffInDays($this->rental_due_date, false) <= 2) {
                return 'Rental due soon';
            }
        }

        return match ($this->status) {
            self::STATUS_PENDING => 'Waiting for seller approval',
            self::STATUS_APPROVED => 'Approved and in progress',
            default => ucfirst($this->status),
        };
    }

    public function canBeApproved(): bool
    {
        return $this->status === self::STATUS_PENDING && $this->seller_id === auth()->id();
    }

    public function canBeRejected(): bool
    {
        return $this->status === self::STATUS_PENDING && $this->seller_id === auth()->id();
    }

    public function canBeCompleted(): bool
    {
        return $this->status === self::STATUS_APPROVED && $this->seller_id === auth()->id();
    }

    public function hasBeenRated($userId): bool
    {
        return $this->ratings()->where('reviewer_id', $userId)->exists();
    }
}
