<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ReportController extends Controller
{
    public function student(Request $request)
    {
        $user = Auth::user();
        $filters = $this->validateFilters($request);

        $transactionQuery = Transaction::with(['item', 'buyer', 'seller'])
            ->where(function ($query) use ($user) {
                $query->where('buyer_id', $user->id)
                    ->orWhere('seller_id', $user->id);
            });

        $itemQuery = Item::where('user_id', $user->id);

        $this->applyFilters($itemQuery, $transactionQuery, $filters);

        $allTransactions = (clone $transactionQuery)->get();
        $allItems = (clone $itemQuery)->get();

        $transactions = $this->sortTransactions($transactionQuery, $filters['sort'] ?? 'newest')
            ->paginate($filters['per_page'] ?? 10, ['*'], 'transactions_page')
            ->withQueryString();

        $items = $this->sortItems($itemQuery, $filters['sort'] ?? 'newest')
            ->paginate($filters['per_page'] ?? 10, ['*'], 'items_page')
            ->withQueryString();

        $stats = [
            'listed' => $allItems->count(),
            'approvedListings' => $allItems->where('moderation_status', Item::MODERATION_APPROVED)->count(),
            'transactions' => $allTransactions->count(),
            'completed' => $allTransactions->where('status', Transaction::STATUS_COMPLETED)->count(),
            'earned' => $allTransactions->where('seller_id', $user->id)->where('status', Transaction::STATUS_COMPLETED)->sum(fn ($transaction) => $transaction->item?->price ?? 0),
        ];
        $categories = $this->categories();

        return view('reports.student', compact('user', 'items', 'transactions', 'stats', 'filters', 'categories'));
    }

    public function admin(Request $request)
    {
        $filters = $this->validateFilters($request);
        $transactionQuery = Transaction::with(['item', 'buyer', 'seller']);
        $itemQuery = Item::with('user');

        $this->applyFilters($itemQuery, $transactionQuery, $filters);

        $allTransactions = (clone $transactionQuery)->get();
        $allItems = (clone $itemQuery)->get();

        $transactions = $this->sortTransactions($transactionQuery, $filters['sort'] ?? 'newest')
            ->paginate($filters['per_page'] ?? 10, ['*'], 'transactions_page')
            ->withQueryString();

        $items = $this->sortItems($itemQuery, $filters['sort'] ?? 'newest')
            ->paginate($filters['per_page'] ?? 10, ['*'], 'items_page')
            ->withQueryString();

        $stats = [
            'items' => $allItems->count(),
            'pendingListings' => $allItems->where('moderation_status', Item::MODERATION_PENDING)->count(),
            'approvedListings' => $allItems->where('moderation_status', Item::MODERATION_APPROVED)->count(),
            'transactions' => $allTransactions->count(),
            'completed' => $allTransactions->where('status', Transaction::STATUS_COMPLETED)->count(),
            'gcash' => $allTransactions->where('payment_method', 'gcash')->count(),
        ];
        $categories = $this->categories();

        return view('reports.admin', compact('items', 'transactions', 'stats', 'filters', 'categories'));
    }

    private function validateFilters(Request $request): array
    {
        return $request->validate([
            'status' => ['nullable', Rule::in([
                Transaction::STATUS_PENDING,
                Transaction::STATUS_APPROVED,
                Transaction::STATUS_REJECTED,
                Transaction::STATUS_COMPLETED,
            ])],
            'type' => ['nullable', Rule::in([Item::TYPE_SELL, Item::TYPE_RENT])],
            'category' => ['nullable', 'string', 'max:100'],
            'sort' => ['nullable', Rule::in(['newest', 'oldest', 'title', 'status'])],
            'per_page' => ['nullable', 'integer', Rule::in([10, 20])],
        ]);
    }

    private function applyFilters($itemQuery, $transactionQuery, array $filters): void
    {
        if (! empty($filters['status'])) {
            $transactionQuery->where('status', $filters['status']);

            if ($filters['status'] === Transaction::STATUS_COMPLETED) {
                $itemQuery->where('status', Item::STATUS_SOLD);
            } elseif ($filters['status'] === Transaction::STATUS_PENDING) {
                $itemQuery->where('status', Item::STATUS_PENDING);
            }
        }

        if (! empty($filters['type'])) {
            $itemQuery->where('listing_type', $filters['type']);
            $transactionQuery->whereHas('item', fn ($query) => $query->where('listing_type', $filters['type']));
        }

        if (! empty($filters['category'])) {
            $itemQuery->where('category', $filters['category']);
            $transactionQuery->whereHas('item', fn ($query) => $query->where('category', $filters['category']));
        }
    }

    private function sortItems($query, string $sort)
    {
        return match ($sort) {
            'oldest' => $query->oldest(),
            'title' => $query->orderBy('title'),
            'status' => $query->orderBy('status')->latest(),
            default => $query->latest(),
        };
    }

    private function sortTransactions($query, string $sort)
    {
        return match ($sort) {
            'oldest' => $query->oldest(),
            'status' => $query->orderBy('status')->latest(),
            default => $query->latest(),
        };
    }

    private function categories(): array
    {
        return collect(config('um_departments.categories', []))
            ->merge(Item::query()->whereNotNull('category')->distinct()->pluck('category'))
            ->filter()
            ->unique()
            ->sort()
            ->values()
            ->all();
    }
}
