<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ItemController extends Controller
{
    public function index(Request $request)
    {
        $query = Item::with('user')
            ->where('status', 'available')
            ->where('moderation_status', 'approved');

        if ($request->filled('q')) {
            $search = $request->q;
            $query->where(function ($innerQuery) use ($search) {
                $innerQuery->where('title', 'like', '%'.$search.'%')
                    ->orWhere('description', 'like', '%'.$search.'%')
                    ->orWhere('course_code', 'like', '%'.$search.'%');
            });
        }

        if ($request->filled('category') && $request->category !== 'All') {
            $query->where('category', $request->category);
        }

        if ($request->filled('type')) {
            $query->where('listing_type', $request->type);
        }

        if ($request->filled('department')) {
            $query->where('department', $request->department);
        }

        if ($request->filled('program')) {
            $query->where('program', $request->program);
        }

        if ($request->filled('condition')) {
            $query->where('condition', $request->condition);
        }

        if ($request->filled('course_code')) {
            $query->where('course_code', 'like', '%'.$request->course_code.'%');
        }

        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }

        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        $sort = $request->input('sort', 'newest');

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

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string', 'max:100'],
            'description' => ['required', 'string'],
            'department' => ['required', 'string', 'max:255'],
            'program' => ['required', 'string', 'max:255'],
            'course_code' => ['required', 'string', 'max:20'],
            'listing_type' => ['required', 'in:sell,rent'],
            'accepted_payment_methods' => ['required', 'array', 'min:1'],
            'accepted_payment_methods.*' => ['in:gcash,maya,bank_transfer,cash_on_pickup,other'],
            'minimum_rental_days' => ['nullable', 'required_if:listing_type,rent', 'integer', 'min:1', 'max:365'],
            'maximum_rental_days' => ['nullable', 'required_if:listing_type,rent', 'integer', 'gte:minimum_rental_days', 'max:365'],
            'daily_rental_rate' => ['nullable', 'required_if:listing_type,rent', 'numeric', 'min:0'],
            'condition' => ['required', 'in:new,like_new,good,fair,poor'],
            'price' => ['nullable', 'numeric', 'min:0'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ]);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('items', 'public');
        }

        $data['user_id'] = Auth::id();
        $data = $this->normalizeListingData($data);
        $data['moderation_status'] = Auth::user()->isAdmin() ? 'approved' : 'pending';
        Item::create($data);

        return redirect()->route('marketplace.index')->with('success', 'Item posted successfully. It will appear in the marketplace after admin approval.');
    }

    public function show(Item $item)
    {
        $item->load('user');

        return view('items.show', compact('item'));
    }

    public function edit(Item $item)
    {
        abort_if($item->user_id !== Auth::id(), 403);

        $departments = config('um_departments.departments');
        $conditions = config('um_departments.conditions');
        $categories = config('um_departments.categories');

        return view('items.edit', compact('item', 'departments', 'conditions', 'categories'));
    }

    public function update(Request $request, Item $item)
    {
        abort_if($item->user_id !== Auth::id(), 403);

        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string', 'max:100'],
            'description' => ['required', 'string'],
            'department' => ['required', 'string', 'max:255'],
            'program' => ['required', 'string', 'max:255'],
            'course_code' => ['required', 'string', 'max:20'],
            'listing_type' => ['required', 'in:sell,rent'],
            'accepted_payment_methods' => ['required', 'array', 'min:1'],
            'accepted_payment_methods.*' => ['in:gcash,maya,bank_transfer,cash_on_pickup,other'],
            'minimum_rental_days' => ['nullable', 'required_if:listing_type,rent', 'integer', 'min:1', 'max:365'],
            'maximum_rental_days' => ['nullable', 'required_if:listing_type,rent', 'integer', 'gte:minimum_rental_days', 'max:365'],
            'daily_rental_rate' => ['nullable', 'required_if:listing_type,rent', 'numeric', 'min:0'],
            'condition' => ['required', 'in:new,like_new,good,fair,poor'],
            'price' => ['nullable', 'numeric', 'min:0'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ]);

        if ($request->hasFile('image')) {
            if ($item->image) {
                Storage::disk('public')->delete($item->image);
            }

            $data['image'] = $request->file('image')->store('items', 'public');
        }

        $data = $this->normalizeListingData($data);
        $data['moderation_status'] = Auth::user()->isAdmin() ? 'approved' : 'pending';
        $data['rejection_reason'] = null;

        $item->update($data);

        return redirect()->route('marketplace.show', $item)->with('success', 'Item updated successfully. Admin approval is required before it appears in marketplace.');
    }

    public function destroy(Item $item)
    {
        abort_if($item->user_id !== Auth::id(), 403);

        if ($item->image) {
            Storage::disk('public')->delete($item->image);
        }

        $item->delete();

        return redirect()->route('marketplace.index')->with('success', 'Item deleted successfully.');
    }

    private function normalizeListingData(array $data): array
    {
        if ($data['listing_type'] === 'rent') {
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
