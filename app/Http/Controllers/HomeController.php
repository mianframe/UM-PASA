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
            ->where('status', Item::STATUS_AVAILABLE)
            ->where('moderation_status', Item::MODERATION_APPROVED)
            ->latest()
            ->take(4)
            ->get();

        $stats = [
            'students' => User::where('role', 'student')->count(),
            'listings' => Item::count(),
            'completed' => Transaction::where('status', Transaction::STATUS_COMPLETED)->count(),
            'departments' => count(config('um_departments.departments')),
        ];
        $departments = config('um_departments.departments');

        return view('home', compact('recentItems', 'stats', 'departments'));
    }
}
