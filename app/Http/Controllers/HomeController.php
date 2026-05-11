<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Transaction;
use App\Models\User;

class HomeController extends Controller
{
    public function index()
    {
        $recentItems = Item::with('user')
            ->where('status', 'available')
            ->where('moderation_status', 'approved')
            ->latest()
            ->take(4)
            ->get();

        $stats = [
            'students' => User::where('role', 'student')->count(),
            'listings' => Item::count(),
            'completed' => Transaction::where('status', 'completed')->count(),
            'departments' => count(config('um_departments.departments')),
        ];

        return view('home', compact('recentItems', 'stats'));
    }
}
