<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Transaction;
use App\Models\User;

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
        $item->delete();

        return back()->with('success', 'Item deleted by admin.');
    }
}
