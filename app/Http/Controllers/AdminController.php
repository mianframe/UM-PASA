<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Notification;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
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
            'moderation_status' => 'approved',
            'rejection_reason' => null,
        ]);

        Notification::create([
            'user_id' => $item->user_id,
            'type' => 'item_approved',
            'related_id' => $item->id,
            'message' => "Your listing {$item->title} was approved and is now visible in the marketplace.",
        ]);

        return back()->with('success', 'Item approved.');
    }

    public function rejectItem(Request $request, Item $item)
    {
        $data = $request->validate([
            'rejection_reason' => ['nullable', 'string', 'max:500'],
        ]);

        $item->update([
            'moderation_status' => 'rejected',
            'rejection_reason' => $data['rejection_reason'] ?? null,
        ]);

        Notification::create([
            'user_id' => $item->user_id,
            'type' => 'item_rejected',
            'related_id' => $item->id,
            'message' => "Your listing {$item->title} was rejected by admin.".($item->rejection_reason ? " Reason: {$item->rejection_reason}" : ''),
        ]);

        return back()->with('success', 'Item rejected.');
    }
}
