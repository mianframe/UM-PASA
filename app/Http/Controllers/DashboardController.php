<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Transaction;
use App\Models\User;
use App\Services\RentalNotificationService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function __construct(private readonly RentalNotificationService $rentalNotifications) {}

    public function index()
    {
        $user = Auth::user();

        if ($user->isAdmin()) {
            $stats = [
                'users' => User::count(),
                'students' => User::where('role', 'student')->count(),
                'transactions' => Transaction::count(),
                'completed' => Transaction::where('status', Transaction::STATUS_COMPLETED)->count(),
                'items' => Item::count(),
                'pendingItems' => Item::where('moderation_status', Item::MODERATION_PENDING)->count(),
                'approvedItems' => Item::where('moderation_status', Item::MODERATION_APPROVED)->count(),
                'rejectedItems' => Item::where('moderation_status', Item::MODERATION_REJECTED)->count(),
                'activeListings' => Item::where('status', Item::STATUS_AVAILABLE)->where('moderation_status', Item::MODERATION_APPROVED)->count(),
                'rentals' => Item::where('listing_type', Item::TYPE_RENT)->count(),
                'sales' => Item::where('listing_type', Item::TYPE_SELL)->count(),
            ];

            $users = User::latest()->take(8)->get();
            $transactions = Transaction::with(['item', 'buyer', 'seller'])->latest()->take(8)->get();
            $categoryChart = Item::select('category', DB::raw('count(*) as total'))
                ->groupBy('category')
                ->orderByDesc('total')
                ->take(6)
                ->get();
            $departmentChart = Item::select('department', DB::raw('count(*) as total'))
                ->groupBy('department')
                ->orderByDesc('total')
                ->take(6)
                ->get();
            $monthlyChart = Transaction::query()
                ->oldest()
                ->get()
                ->groupBy(fn (Transaction $transaction) => $transaction->created_at->format('Y-m'))
                ->map(fn ($transactions, $month) => ['label' => $month, 'total' => $transactions->count()])
                ->values()
                ->take(6);
            $approvalChart = collect([
                ['label' => 'Pending', 'total' => $stats['pendingItems']],
                ['label' => 'Approved', 'total' => $stats['approvedItems']],
                ['label' => 'Rejected', 'total' => $stats['rejectedItems']],
            ]);
            $typeChart = collect([
                ['label' => 'Sales', 'total' => $stats['sales']],
                ['label' => 'Rentals', 'total' => $stats['rentals']],
                ['label' => 'Swaps', 'total' => 0],
            ]);

            return view('dashboard.admin', compact(
                'user',
                'users',
                'transactions',
                'stats',
                'categoryChart',
                'departmentChart',
                'monthlyChart',
                'approvalChart',
                'typeChart',
            ));
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
