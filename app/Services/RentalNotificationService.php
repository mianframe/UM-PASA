<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\Transaction;
use App\Models\User;

class RentalNotificationService
{
    public function __construct(private readonly NotificationService $notifications) {}

    public function createDueNotificationsFor(User $user): void
    {
        Transaction::with('item')
            ->where('buyer_id', $user->id)
            ->where('status', Transaction::STATUS_APPROVED)
            ->whereNotNull('rental_due_date')
            ->get()
            ->each(function (Transaction $transaction) use ($user) {
                $type = $this->dueNotificationType($transaction);

                if (! $type || $this->notificationExists($user, $transaction, $type)) {
                    return;
                }

                $this->notifications->send($user, $type, $this->messageFor($transaction, $type), $transaction);
            });
    }

    private function dueNotificationType(Transaction $transaction): ?string
    {
        $days = now()->startOfDay()->diffInDays($transaction->rental_due_date, false);

        return match (true) {
            $days < 0 => 'rental_overdue',
            $days === 0 => 'rental_due',
            $days <= 2 => 'rental_due_soon',
            default => null,
        };
    }

    private function notificationExists(User $user, Transaction $transaction, string $type): bool
    {
        return Notification::where('user_id', $user->id)
            ->where('type', $type)
            ->where('related_id', $transaction->id)
            ->exists();
    }

    private function messageFor(Transaction $transaction, string $type): string
    {
        return match ($type) {
            'rental_overdue' => "Rental for {$transaction->item->title} is overdue.",
            'rental_due' => "Rental for {$transaction->item->title} is due today.",
            default => "Rental for {$transaction->item->title} is due soon.",
        };
    }
}
