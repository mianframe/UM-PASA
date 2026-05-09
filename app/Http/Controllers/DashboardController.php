<?php

namespace App\Http\Controllers;

use App\Models\Item;
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

        $recentItems = Item::with('user')->where('status', 'available')->latest()->take(6)->get();
        $notifications = $user->notifications()->latest()->take(5)->get();
        $stats = [
            'totalItems' => $user->items()->count(),
            'pendingRequests' => Transaction::where('seller_id', $user->id)->where('status', 'pending')->count(),
            'completedTransactions' => Transaction::where(function ($query) use ($user) {
                $query->where('buyer_id', $user->id)->orWhere('seller_id', $user->id);
            })->where('status', 'completed')->count(),
            'notifications' => $user->notifications()->where('is_read', false)->count(),
        ];

        return view('dashboard.student', compact('user', 'recentItems', 'notifications', 'stats'));
    }
}
