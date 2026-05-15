<?php

namespace App\Console\Commands;

use App\Models\Item;
use App\Models\Transaction;
use App\Services\NotificationService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ExpireStaleRequests extends Command
{
    protected $signature = 'um-pasa:expire-stale-requests {--days=7 : Pending request age before it expires}';

    protected $description = 'Reject stale pending marketplace requests and reopen their items.';

    public function handle(NotificationService $notifications): int
    {
        $days = max(1, (int) $this->option('days'));
        $cutoff = now()->subDays($days);
        $expiredCount = 0;

        Transaction::with('item')
            ->where('status', Transaction::STATUS_PENDING)
            ->where('created_at', '<=', $cutoff)
            ->orderBy('id')
            ->chunkById(100, function ($transactions) use (&$expiredCount, $notifications) {
                foreach ($transactions as $transaction) {
                    DB::transaction(function () use ($transaction, &$expiredCount, $notifications) {
                        $lockedTransaction = Transaction::with('item')
                            ->whereKey($transaction->id)
                            ->lockForUpdate()
                            ->first();

                        if (! $lockedTransaction || $lockedTransaction->status !== Transaction::STATUS_PENDING) {
                            return;
                        }

                        $lockedTransaction->update(['status' => Transaction::STATUS_REJECTED]);

                        if ($lockedTransaction->item) {
                            $lockedTransaction->item->update(['status' => Item::STATUS_AVAILABLE]);
                        }

                        $this->notifyParticipants($lockedTransaction, $notifications);
                        $expiredCount++;
                    });
                }
            });

        $this->info("Expired {$expiredCount} stale pending request(s).");

        return self::SUCCESS;
    }

    private function notifyParticipants(Transaction $transaction, NotificationService $notifications): void
    {
        $notifications->send(
            $transaction->buyer_id,
            'request_expired',
            "Your request for {$transaction->item->title} expired because it was not approved in time.",
            $transaction
        );

        $notifications->send(
            $transaction->seller_id,
            'request_expired',
            "The pending request for {$transaction->item->title} expired and the listing is available again.",
            $transaction
        );
    }
}
