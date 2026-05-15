<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Transaction;
use App\Models\User;
use App\Services\RentalNotificationService;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __construct(private readonly RentalNotificationService $rentalNotifications) {}

    public function index()
    {
        $user = Auth::user();

        if ($user->isAdmin()) {
            $stats = [
                'users' => User::count(),
                'transactions' => Transaction::count(),
                'completed' => Transaction::where('status', Transaction::STATUS_COMPLETED)->count(),
                'items' => Item::count(),
            ];

            $users = User::latest()->take(8)->get();
            $transactions = Transaction::with(['item', 'buyer', 'seller'])->latest()->take(8)->get();

            return view('dashboard.admin', compact('user', 'users', 'transactions', 'stats'));
        }

        $this->rentalNotifications->createDueNotificationsFor($user);

        $recentItems = Item::with('user')
            ->where('status', Item::STATUS_AVAILABLE)
            ->where('moderation_status', Item::MODERATION_APPROVED)
            ->latest()
            ->take(6)
            ->get();
        $notifications = $user->notifications()->latest()->take(5)->get();
        $stats = [
            'totalItems' => $user->items()->count(),
            'approvedListings' => $user->items()->where('moderation_status', Item::MODERATION_APPROVED)->count(),
            'pendingListings' => $user->items()->where('moderation_status', Item::MODERATION_PENDING)->count(),
            'pendingRequests' => Transaction::where('seller_id', $user->id)->where('status', Transaction::STATUS_PENDING)->count(),
            'completedTransactions' => Transaction::where(function ($query) use ($user) {
                $query->where('buyer_id', $user->id)->orWhere('seller_id', $user->id);
            })->where('status', Transaction::STATUS_COMPLETED)->count(),
            'notifications' => $user->notifications()->where('is_read', false)->count(),
        ];

        return view('dashboard.student', compact('user', 'recentItems', 'notifications', 'stats'));
    }
}
