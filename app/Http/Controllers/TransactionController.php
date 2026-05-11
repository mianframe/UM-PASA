<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Notification;
use App\Models\Rating;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class TransactionController extends Controller
{
    public function history()
    {
        $transactions = Transaction::with(['item', 'buyer', 'seller'])
            ->where(function ($query) {
                $query->where('buyer_id', Auth::id())
                    ->orWhere('seller_id', Auth::id());
            })
            ->latest()
            ->paginate(12);

        return view('transactions.history', compact('transactions'));
    }

    public function pending()
    {
        $transactions = Transaction::with(['item', 'buyer', 'seller'])
            ->where('seller_id', Auth::id())
            ->where('status', 'pending')
            ->latest()
            ->paginate(12);

        return view('transactions.pending', compact('transactions'));
    }

    public function store(Request $request, Item $item)
    {
        abort_if($item->status !== 'available' || $item->moderation_status !== 'approved', 403, 'This item is no longer available.');
        abort_if($item->user_id === Auth::id(), 403, 'You cannot request your own item.');

        $alreadyRequested = Transaction::where('item_id', $item->id)
            ->where('buyer_id', Auth::id())
            ->whereIn('status', ['pending', 'approved'])
            ->exists();

        if ($alreadyRequested) {
            return back()->with('error', 'You already have an active request for this item.');
        }

        $data = $request->validate([
            'payment_method' => ['required', Rule::in($item->accepted_payment_methods ?: array_keys(Item::paymentMethodOptions()))],
            'other_payment_method' => ['nullable', 'required_if:payment_method,other', 'string', 'max:80'],
            'rental_duration_days' => [
                'nullable',
                Rule::requiredIf($item->listing_type === 'rent'),
                'integer',
                'min:'.($item->minimum_rental_days ?? 1),
                'max:'.($item->maximum_rental_days ?? 365),
            ],
        ]);

        $rentalDuration = $item->listing_type === 'rent' ? (int) $data['rental_duration_days'] : null;

        $transaction = Transaction::create([
            'buyer_id' => Auth::id(),
            'seller_id' => $item->user_id,
            'item_id' => $item->id,
            'status' => 'pending',
            'payment_method' => $data['payment_method'],
            'other_payment_method' => $data['payment_method'] === 'other' ? $data['other_payment_method'] : null,
            'rental_duration_days' => $rentalDuration,
            'rental_due_date' => $rentalDuration ? now()->addDays($rentalDuration)->toDateString() : null,
        ]);

        $item->update(['status' => 'pending']);

        Notification::create([
            'user_id' => $item->user_id,
            'type' => 'request',
            'related_id' => $transaction->id,
            'message' => "New request received for {$item->title} using {$transaction->getPaymentMethodLabel()}.",
        ]);

        return redirect()->route('transactions.history')->with('success', 'Request sent to the seller.');
    }

    public function approve(Request $request, Transaction $transaction)
    {
        abort_if($transaction->seller_id !== Auth::id(), 403);
        abort_if($transaction->status !== 'pending', 403);

        $data = $request->validate([
            'meetup_location' => ['required', 'string', 'max:255'],
            'meetup_time' => ['required', 'date', 'after:now'],
        ]);

        $transaction->update(array_merge($data, ['status' => 'approved']));

        Notification::create([
            'user_id' => $transaction->buyer_id,
            'type' => 'approval',
            'related_id' => $transaction->id,
            'message' => "Your request for {$transaction->item->title} was approved.",
        ]);

        return back()->with('success', 'Request approved successfully.');
    }

    public function reject(Transaction $transaction)
    {
        abort_if($transaction->seller_id !== Auth::id(), 403);
        abort_if($transaction->status !== 'pending', 403);

        $transaction->update(['status' => 'rejected']);
        $transaction->item->update(['status' => 'available']);

        Notification::create([
            'user_id' => $transaction->buyer_id,
            'type' => 'rejection',
            'related_id' => $transaction->id,
            'message' => "Your request for {$transaction->item->title} was rejected.",
        ]);

        return back()->with('success', 'Request rejected.');
    }

    public function complete(Transaction $transaction)
    {
        abort_if($transaction->seller_id !== Auth::id(), 403);
        abort_if($transaction->status !== 'approved', 403);

        $transaction->update(['status' => 'completed']);
        $transaction->item->update(['status' => 'sold']);

        Notification::create([
            'user_id' => $transaction->buyer_id,
            'type' => 'completion',
            'related_id' => $transaction->id,
            'message' => "Transaction completed for {$transaction->item->title}.",
        ]);

        return redirect()->route('transactions.show', $transaction)->with('success', 'Transaction completed.');
    }

    public function show(Transaction $transaction)
    {
        abort_if(! in_array(Auth::id(), [$transaction->buyer_id, $transaction->seller_id]), 403);

        $transaction->load(['item', 'buyer', 'seller']);

        $reviewedUserId = Auth::id() === $transaction->buyer_id
            ? $transaction->seller_id
            : $transaction->buyer_id;

        $canRate = $transaction->status === 'completed' && ! Rating::where('reviewer_id', Auth::id())
            ->where('transaction_id', $transaction->id)
            ->where('reviewed_user_id', $reviewedUserId)
            ->exists();

        return view('transactions.receipt', compact('transaction', 'canRate'));
    }

    public function uploadProof(Request $request, Transaction $transaction)
    {
        abort_if(Auth::id() !== $transaction->buyer_id, 403);
        abort_if(! in_array($transaction->status, ['pending', 'approved']), 403);

        $data = $request->validate([
            'payment_proof' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:4096'],
        ]);

        if ($transaction->payment_proof) {
            Storage::disk('public')->delete($transaction->payment_proof);
        }

        $path = $data['payment_proof']->store('payment-proofs', 'public');

        $transaction->update([
            'payment_proof' => $path,
            'payment_proof_uploaded_at' => now(),
        ]);

        Notification::create([
            'user_id' => $transaction->seller_id,
            'type' => 'payment_proof',
            'related_id' => $transaction->id,
            'message' => "Payment proof was uploaded for {$transaction->item->title}.",
        ]);

        return back()->with('success', 'Payment proof uploaded successfully.');
    }
}
