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
        return $user->id === $transaction->buyer_id
            || $user->id === $transaction->seller_id
            || $user->isAdmin();
    }

    /**
     * Determine if the user can approve the transaction.
     */
    public function approve(User $user, Transaction $transaction): bool
    {
        return $user->id === $transaction->seller_id
            && $transaction->status === Transaction::STATUS_PENDING;
    }

    /**
     * Determine if the user can reject the transaction.
     */
    public function reject(User $user, Transaction $transaction): bool
    {
        return $user->id === $transaction->seller_id
            && $transaction->status === Transaction::STATUS_PENDING;
    }

    /**
     * Determine if the user can complete the transaction.
     */
    public function complete(User $user, Transaction $transaction): bool
    {
        return $user->id === $transaction->seller_id
            && $transaction->status === Transaction::STATUS_APPROVED;
    }

    public function uploadProof(User $user, Transaction $transaction): bool
    {
        return $user->id === $transaction->buyer_id
            && in_array($transaction->status, [Transaction::STATUS_PENDING, Transaction::STATUS_APPROVED], true);
    }

    public function rate(User $user, Transaction $transaction): bool
    {
        return $transaction->status === Transaction::STATUS_COMPLETED
            && ($user->id === $transaction->buyer_id || $user->id === $transaction->seller_id);
    }
}
