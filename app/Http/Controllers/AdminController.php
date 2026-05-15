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
