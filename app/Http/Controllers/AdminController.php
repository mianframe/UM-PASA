<?php

namespace App\Http\Controllers;

use App\Http\Requests\RejectItemRequest;
use App\Models\Item;
use App\Models\Transaction;
use App\Models\User;
use App\Services\NotificationService;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    public function __construct(private readonly NotificationService $notifications) {}

    public function users()
    {
        $users = User::latest()->paginate(15);

        return view('admin.users', compact('users'));
    }

    public function userRecord(User $user)
    {
        $itemQuery = Item::where('user_id', $user->id);
        $transactionQuery = Transaction::with(['item', 'buyer', 'seller'])
            ->where(function ($query) use ($user) {
                $query->where('buyer_id', $user->id)
                    ->orWhere('seller_id', $user->id);
            });

        $allItems = (clone $itemQuery)->get();
        $allTransactions = (clone $transactionQuery)->get();

        $stats = [
            'listings' => $allItems->count(),
            'approvedListings' => $allItems->where('moderation_status', Item::MODERATION_APPROVED)->count(),
            'transactions' => $allTransactions->count(),
            'completed' => $allTransactions->where('status', Transaction::STATUS_COMPLETED)->count(),
            'bought' => $allTransactions->where('buyer_id', $user->id)->where('status', Transaction::STATUS_COMPLETED)->count(),
            'sold' => $allTransactions->where('seller_id', $user->id)->where('status', Transaction::STATUS_COMPLETED)->count(),
            'earned' => $allTransactions
                ->where('seller_id', $user->id)
                ->where('status', Transaction::STATUS_COMPLETED)
                ->sum(fn (Transaction $transaction) => $transaction->item?->price ?? 0),
        ];

        $items = $itemQuery
            ->latest()
            ->paginate(5, ['*'], 'items_page')
            ->withQueryString();

        $transactions = $transactionQuery
            ->latest()
            ->paginate(5, ['*'], 'transactions_page')
            ->withQueryString();

        return view('admin.user-record', compact('user', 'items', 'transactions', 'stats'));
    }

    public function transactions()
    {
        $transactions = Transaction::with(['item', 'buyer', 'seller'])->latest()->paginate(15);

        return view('admin.transactions', compact('transactions'));
    }

    public function items()
    {
        $items = Item::with('user')->latest()->paginate(15);

        return view('admin.items', compact('items'));
    }

    public function destroyItem(Item $item)
    {
        if ($item->image) {
            Storage::disk('public')->delete($item->image);
        }

        $item->delete();

        return back()->with('success', 'Item deleted by admin.');
    }

    public function approveItem(Item $item)
    {
        $item->update([
            'moderation_status' => Item::MODERATION_APPROVED,
            'rejection_reason' => null,
        ]);

        $this->notifications->send(
            $item->user_id,
            'item_approved',
            "Your listing {$item->title} was approved and is now visible in the marketplace.",
            $item
        );

        return back()->with('success', 'Item approved.');
    }

    public function rejectItem(RejectItemRequest $request, Item $item)
    {
        $data = $request->validated();

        $item->update([
            'moderation_status' => Item::MODERATION_REJECTED,
            'rejection_reason' => $data['rejection_reason'] ?? null,
        ]);

        $this->notifications->send(
            $item->user_id,
            'item_rejected',
            "Your listing {$item->title} was rejected by admin.".($item->rejection_reason ? " Reason: {$item->rejection_reason}" : ''),
            $item
        );

        return back()->with('success', 'Item rejected.');
    }
}
