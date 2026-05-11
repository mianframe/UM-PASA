<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    public function student()
    {
        $user = Auth::user();

        $transactions = Transaction::with(['item', 'buyer', 'seller'])
            ->where(function ($query) use ($user) {
                $query->where('buyer_id', $user->id)
                    ->orWhere('seller_id', $user->id);
            })
            ->latest()
            ->get();

        $items = Item::where('user_id', $user->id)->latest()->get();

        $stats = [
            'listed' => $items->count(),
            'approvedListings' => $items->where('moderation_status', 'approved')->count(),
            'transactions' => $transactions->count(),
            'completed' => $transactions->where('status', 'completed')->count(),
            'earned' => $transactions->where('seller_id', $user->id)->where('status', 'completed')->sum(fn ($transaction) => $transaction->item?->price ?? 0),
        ];

        return view('reports.student', compact('user', 'items', 'transactions', 'stats'));
    }

    public function admin()
    {
        $transactions = Transaction::with(['item', 'buyer', 'seller'])->latest()->get();
        $items = Item::with('user')->latest()->get();

        $stats = [
            'items' => $items->count(),
            'pendingListings' => $items->where('moderation_status', 'pending')->count(),
            'approvedListings' => $items->where('moderation_status', 'approved')->count(),
            'transactions' => $transactions->count(),
            'completed' => $transactions->where('status', 'completed')->count(),
            'gcash' => $transactions->where('payment_method', 'gcash')->count(),
        ];

        return view('reports.admin', compact('items', 'transactions', 'stats'));
    }
}
