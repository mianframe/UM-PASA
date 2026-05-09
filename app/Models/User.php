<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'role'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function items()
    {
        return $this->hasMany(Item::class);
    }

    public function transactionsAsBuyer()
    {
        return $this->hasMany(Transaction::class, 'buyer_id');
    }

    public function transactionsAsSeller()
    {
        return $this->hasMany(Transaction::class, 'seller_id');
    }

    public function ratingsGiven()
    {
        return $this->hasMany(Rating::class, 'reviewer_id');
    }

    public function ratingsReceived()
    {
        return $this->hasMany(Rating::class, 'reviewed_user_id');
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function getAverageRatingAttribute(): float
    {
        return round($this->ratingsReceived()->avg('rating') ?? 0, 1);
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }
}
