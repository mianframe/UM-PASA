<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        $this->createRentalDueNotifications();

        $notifications = Auth::user()->notifications()->latest()->get();

        return view('notifications.index', compact('notifications'));
    }

    public function markAllRead()
    {
        Auth::user()->notifications()->where('is_read', false)->update(['is_read' => true]);

        return back();
    }

    private function createRentalDueNotifications(): void
    {
        $user = Auth::user();

        Transaction::with('item')
            ->where('buyer_id', $user->id)
            ->where('status', 'approved')
            ->whereNotNull('rental_due_date')
            ->get()
            ->each(function (Transaction $transaction) use ($user) {
                $days = now()->startOfDay()->diffInDays($transaction->rental_due_date, false);

                $type = match (true) {
                    $days < 0 => 'rental_overdue',
                    $days === 0 => 'rental_due',
                    $days <= 2 => 'rental_due_soon',
                    default => null,
                };

                if (! $type) {
                    return;
                }

                $exists = Notification::where('user_id', $user->id)
                    ->where('type', $type)
                    ->where('related_id', $transaction->id)
                    ->exists();

                if ($exists) {
                    return;
                }

                $message = match ($type) {
                    'rental_overdue' => "Rental for {$transaction->item->title} is overdue.",
                    'rental_due' => "Rental for {$transaction->item->title} is due today.",
                    default => "Rental for {$transaction->item->title} is due soon.",
                };

                Notification::create([
                    'user_id' => $user->id,
                    'type' => $type,
                    'related_id' => $transaction->id,
                    'message' => $message,
                ]);
            });
    }
}
