<?php

namespace App\Http\Controllers;

use App\Http\Requests\SaveItemRequest;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ItemController extends Controller
{
    public function index(Request $request)
    {
        $validated = $request->validate([
            'q' => ['nullable', 'string', 'max:120'],
            'category' => ['nullable', 'string', Rule::in(array_merge(['All'], config('um_departments.categories', [])))],
            'type' => ['nullable', Rule::in([Item::TYPE_SELL, Item::TYPE_RENT])],
            'department' => ['nullable', 'string', Rule::in(array_keys(config('um_departments.departments', [])))],
            'program' => ['nullable', 'string', 'max:255'],
            'condition' => ['nullable', 'string', Rule::in(config('um_departments.conditions', []))],
            'course_code' => ['nullable', 'string', 'max:20'],
            'min_price' => ['nullable', 'numeric', 'min:0'],
            'max_price' => ['nullable', 'numeric', 'gte:min_price'],
            'sort' => ['nullable', Rule::in(['newest', 'oldest', 'price_low', 'price_high'])],
        ]);

        $query = Item::with('user')
            ->where('status', Item::STATUS_AVAILABLE)
            ->where('moderation_status', Item::MODERATION_APPROVED);

        if ($request->filled('q')) {
            $search = $validated['q'];
            $query->where(function ($innerQuery) use ($search) {
                $innerQuery->where('title', 'like', '%'.$search.'%')
                    ->orWhere('description', 'like', '%'.$search.'%')
                    ->orWhere('course_code', 'like', '%'.$search.'%');
            });
        }

        if ($request->filled('category') && $request->category !== 'All') {
            $query->where('category', $validated['category']);
        }

        if ($request->filled('type')) {
            $query->where('listing_type', $validated['type']);
        }

        if ($request->filled('department')) {
            $query->where('department', $validated['department']);
        }

        if ($request->filled('program')) {
            $query->where('program', $validated['program']);
        }

        if ($request->filled('condition')) {
            $query->where('condition', $validated['condition']);
        }

        if ($request->filled('course_code')) {
            $query->where('course_code', 'like', '%'.$validated['course_code'].'%');
        }

        if ($request->filled('min_price')) {
            $query->where('price', '>=', $validated['min_price']);
        }

        if ($request->filled('max_price')) {
            $query->where('price', '<=', $validated['max_price']);
        }

        $sort = $validated['sort'] ?? 'newest';

        $query = match ($sort) {
            'price_low' => $query->orderBy('price'),
            'price_high' => $query->orderByDesc('price'),
            'oldest' => $query->oldest(),
            default => $query->latest(),
        };

        $items = $query->paginate(20)->withQueryString();
        $departments = config('um_departments.departments');
        $conditions = config('um_departments.conditions');
        $categories = config('um_departments.categories');

        return view('marketplace.index', compact('items', 'departments', 'conditions', 'categories', 'sort'));
    }

    public function create()
    {
        $departments = config('um_departments.departments');
        $conditions = config('um_departments.conditions');
        $categories = config('um_departments.categories');

        return view('items.create', compact('departments', 'conditions', 'categories'));
    }

    public function store(SaveItemRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('items', 'public');
        }

        $data['user_id'] = Auth::id();
        $data = $this->normalizeListingData($data);
        $data['moderation_status'] = Auth::user()->isAdmin() ? Item::MODERATION_APPROVED : Item::MODERATION_PENDING;
        Item::create($data);

        return redirect()->route('marketplace.index')->with('success', 'Item posted successfully. It will appear in the marketplace after admin approval.');
    }

    public function show(Item $item)
    {
        $this->authorize('view', $item);

        $item->load('user');

        return view('items.show', compact('item'));
    }

    public function edit(Item $item)
    {
        $this->authorize('update', $item);

        $departments = config('um_departments.departments');
        $conditions = config('um_departments.conditions');
        $categories = config('um_departments.categories');

        return view('items.edit', compact('item', 'departments', 'conditions', 'categories'));
    }

    public function update(SaveItemRequest $request, Item $item)
    {
        $this->authorize('update', $item);

        $data = $request->validated();

        if ($request->hasFile('image')) {
            if ($item->image) {
                Storage::disk('public')->delete($item->image);
            }

            $data['image'] = $request->file('image')->store('items', 'public');
        }

        $data = $this->normalizeListingData($data);
        $data['moderation_status'] = Auth::user()->isAdmin() ? Item::MODERATION_APPROVED : Item::MODERATION_PENDING;
        $data['rejection_reason'] = null;

        $item->update($data);

        return redirect()->route('marketplace.show', $item)->with('success', 'Item updated successfully. Admin approval is required before it appears in marketplace.');
    }

    public function destroy(Item $item)
    {
        $this->authorize('delete', $item);

        if ($item->image) {
            Storage::disk('public')->delete($item->image);
        }

        $item->delete();

        return redirect()->route('marketplace.index')->with('success', 'Item deleted successfully.');
    }

    private function normalizeListingData(array $data): array
    {
        if ($data['listing_type'] === Item::TYPE_RENT) {
            $data['rental_duration_days'] = $data['maximum_rental_days'];
            $data['price'] = $data['daily_rental_rate'];

            return $data;
        }

        $data['minimum_rental_days'] = null;
        $data['maximum_rental_days'] = null;
        $data['daily_rental_rate'] = null;
        $data['rental_duration_days'] = null;

        return $data;
    }
}
