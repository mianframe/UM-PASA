<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Notification;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->isAdmin()) {
            $stats = [
                'users' => User::count(),
                'transactions' => Transaction::count(),
                'completed' => Transaction::where('status', 'completed')->count(),
                'items' => Item::count(),
            ];

            $users = User::latest()->take(8)->get();
            $transactions = Transaction::with(['item', 'buyer', 'seller'])->latest()->take(8)->get();

            return view('dashboard.admin', compact('user', 'users', 'transactions', 'stats'));
        }

        $this->createRentalDueNotifications($user);

        $recentItems = Item::with('user')
            ->where('status', 'available')
            ->where('moderation_status', 'approved')
            ->latest()
            ->take(6)
            ->get();
        $notifications = $user->notifications()->latest()->take(5)->get();
        $stats = [
            'totalItems' => $user->items()->count(),
            'approvedListings' => $user->items()->where('moderation_status', 'approved')->count(),
            'pendingListings' => $user->items()->where('moderation_status', 'pending')->count(),
            'pendingRequests' => Transaction::where('seller_id', $user->id)->where('status', 'pending')->count(),
            'completedTransactions' => Transaction::where(function ($query) use ($user) {
                $query->where('buyer_id', $user->id)->orWhere('seller_id', $user->id);
            })->where('status', 'completed')->count(),
            'notifications' => $user->notifications()->where('is_read', false)->count(),
        ];

        return view('dashboard.student', compact('user', 'recentItems', 'notifications', 'stats'));
    }

    private function createRentalDueNotifications(User $user): void
    {
        Transaction::with('item')
            ->where('buyer_id', $user->id)
            ->where('status', 'approved')
            ->whereNotNull('rental_due_date')
            ->get()
            ->each(function (Transaction $transaction) use ($user) {
                $days = now()->startOfDay()->diffInDays($transaction->rental_due_date, false);
                $type = $days < 0 ? 'rental_overdue' : ($days === 0 ? 'rental_due' : ($days <= 2 ? 'rental_due_soon' : null));

                if (! $type || Notification::where('user_id', $user->id)->where('type', $type)->where('related_id', $transaction->id)->exists()) {
                    return;
                }

                Notification::create([
                    'user_id' => $user->id,
                    'type' => $type,
                    'related_id' => $transaction->id,
                    'message' => match ($type) {
                        'rental_overdue' => "Rental for {$transaction->item->title} is overdue.",
                        'rental_due' => "Rental for {$transaction->item->title} is due today.",
                        default => "Rental for {$transaction->item->title} is due soon.",
                    },
                ]);
            });
    }
}
