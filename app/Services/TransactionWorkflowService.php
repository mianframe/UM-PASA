<?php

namespace App\Services;

use App\Models\Item;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class TransactionWorkflowService
{
    public function __construct(private readonly NotificationService $notifications) {}

    public function requestItem(User $buyer, Item $item, array $data): Transaction
    {
        $transaction = DB::transaction(function () use ($buyer, $data, $item) {
            $lockedItem = Item::whereKey($item->id)->lockForUpdate()->firstOrFail();

            abort_unless($lockedItem->isAvailable(), 403, 'This item is no longer available.');
            abort_if($lockedItem->user_id === $buyer->id, 403, 'You cannot request your own item.');

            if ($this->buyerHasActiveRequest($buyer, $lockedItem)) {
                throw ValidationException::withMessages([
                    'item' => 'You already have an active request for this item.',
                ]);
            }

            $rentalDuration = $lockedItem->listing_type === Item::TYPE_RENT
                ? (int) $data['rental_duration_days']
                : null;

            $transaction = Transaction::create([
                'buyer_id' => $buyer->id,
                'seller_id' => $lockedItem->user_id,
                'item_id' => $lockedItem->id,
                'status' => Transaction::STATUS_PENDING,
                'payment_method' => $data['payment_method'],
                'other_payment_method' => $data['payment_method'] === 'other' ? ($data['other_payment_method'] ?? null) : null,
                'rental_duration_days' => $rentalDuration,
                'rental_due_date' => null,
            ]);

            $lockedItem->update(['status' => Item::STATUS_PENDING]);

            return $transaction->load('item');
        });

        $this->notifications->send(
            $transaction->seller_id,
            'request',
            "New request received for {$transaction->item->title} using {$transaction->getPaymentMethodLabel()}.",
            $transaction
        );

        return $transaction;
    }

    public function approve(Transaction $transaction, array $data): Transaction
    {
        $transaction = DB::transaction(function () use ($data, $transaction) {
            $transaction = Transaction::with('item')->whereKey($transaction->id)->lockForUpdate()->firstOrFail();
            abort_if($transaction->status !== Transaction::STATUS_PENDING, 403);

            $transaction->update(array_merge($data, [
                'status' => Transaction::STATUS_APPROVED,
                'rental_due_date' => $transaction->item->listing_type === Item::TYPE_RENT && $transaction->rental_duration_days
                    ? Carbon::parse($data['meetup_time'])->addDays($transaction->rental_duration_days)->toDateString()
                    : null,
            ]));

            return $transaction->refresh()->load('item');
        });

        $this->notifications->send(
            $transaction->buyer_id,
            'approval',
            "Your request for {$transaction->item->title} was approved.",
            $transaction
        );

        return $transaction;
    }

    public function reject(Transaction $transaction): Transaction
    {
        $transaction = DB::transaction(function () use ($transaction) {
            $transaction = Transaction::with('item')->whereKey($transaction->id)->lockForUpdate()->firstOrFail();
            abort_if($transaction->status !== Transaction::STATUS_PENDING, 403);

            $transaction->update(['status' => Transaction::STATUS_REJECTED]);

            if (! $this->itemHasOtherActiveTransactions($transaction)) {
                $transaction->item->update(['status' => Item::STATUS_AVAILABLE]);
            }

            return $transaction->refresh()->load('item');
        });

        $this->notifications->send(
            $transaction->buyer_id,
            'rejection',
            "Your request for {$transaction->item->title} was rejected.",
            $transaction
        );

        return $transaction;
    }

    public function complete(Transaction $transaction): Transaction
    {
        $transaction = DB::transaction(function () use ($transaction) {
            $transaction = Transaction::with('item')->whereKey($transaction->id)->lockForUpdate()->firstOrFail();
            abort_if($transaction->status !== Transaction::STATUS_APPROVED, 403);

            $transaction->update(['status' => Transaction::STATUS_COMPLETED]);
            $transaction->item->update([
                'status' => $transaction->item->listing_type === Item::TYPE_RENT
                    ? Item::STATUS_AVAILABLE
                    : Item::STATUS_SOLD,
            ]);

            return $transaction->refresh()->load('item');
        });

        $this->notifications->send(
            $transaction->buyer_id,
            'completion',
            "Transaction completed for {$transaction->item->title}.",
            $transaction
        );

        return $transaction;
    }

    public function uploadProof(Transaction $transaction, UploadedFile $paymentProof): Transaction
    {
        if ($transaction->payment_proof) {
            Storage::disk('public')->delete($transaction->payment_proof);
        }

        $path = $paymentProof->store('payment-proofs', 'public');

        $transaction->update([
            'payment_proof' => $path,
            'payment_proof_uploaded_at' => now(),
        ]);

        $transaction->load('item');

        $this->notifications->send(
            $transaction->seller_id,
            'payment_proof',
            "Payment proof was uploaded for {$transaction->item->title}.",
            $transaction
        );

        return $transaction;
    }

    private function buyerHasActiveRequest(User $buyer, Item $item): bool
    {
        return Transaction::where('item_id', $item->id)
            ->where('buyer_id', $buyer->id)
            ->whereIn('status', [Transaction::STATUS_PENDING, Transaction::STATUS_APPROVED])
            ->exists();
    }

    private function itemHasOtherActiveTransactions(Transaction $transaction): bool
    {
        return Transaction::where('item_id', $transaction->item_id)
            ->whereKeyNot($transaction->id)
            ->whereIn('status', [Transaction::STATUS_PENDING, Transaction::STATUS_APPROVED])
            ->exists();
    }
}
