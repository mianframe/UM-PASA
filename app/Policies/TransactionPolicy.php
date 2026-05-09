<?php

namespace App\Policies;

use App\Models\Transaction;
use App\Models\User;

class TransactionPolicy
{
    /**
     * Determine if the user can view the transaction.
     */
    public function view(User $user, Transaction $transaction): bool
    {
        return $user->id === $transaction->buyer_id || $user->id === $transaction->seller_id;
    }

    /**
     * Determine if the user can approve the transaction.
     */
    public function approve(User $user, Transaction $transaction): bool
    {
        return $user->id === $transaction->seller_id && $transaction->status === 'pending';
    }

    /**
     * Determine if the user can reject the transaction.
     */
    public function reject(User $user, Transaction $transaction): bool
    {
        return $user->id === $transaction->seller_id && $transaction->status === 'pending';
    }

    /**
     * Determine if the user can complete the transaction.
     */
    public function complete(User $user, Transaction $transaction): bool
    {
        return $user->id === $transaction->seller_id && $transaction->status === 'approved';
    }
}
