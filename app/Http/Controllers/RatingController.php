<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\Rating;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RatingController extends Controller
{
    public function create(Transaction $transaction)
    {
        abort_if(! in_array(Auth::id(), [$transaction->buyer_id, $transaction->seller_id]), 403);
        abort_if($transaction->status !== 'completed', 403);

        $reviewedUser = Auth::id() === $transaction->buyer_id
            ? $transaction->seller
            : $transaction->buyer;

        return view('ratings.create', compact('transaction', 'reviewedUser'));
    }

    public function store(Request $request, Transaction $transaction)
    {
        abort_if(! in_array(Auth::id(), [$transaction->buyer_id, $transaction->seller_id]), 403);
        abort_if($transaction->status !== 'completed', 403);

        $reviewedUserId = Auth::id() === $transaction->buyer_id
            ? $transaction->seller_id
            : $transaction->buyer_id;

        $request->validate([
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'comment' => ['nullable', 'string', 'max:500'],
        ]);

        $alreadyRated = Rating::where('reviewer_id', Auth::id())
            ->where('transaction_id', $transaction->id)
            ->where('reviewed_user_id', $reviewedUserId)
            ->exists();

        if ($alreadyRated) {
            return back()->with('error', 'You already rated this user.');
        }

        Rating::create([
            'reviewer_id' => Auth::id(),
            'reviewed_user_id' => $reviewedUserId,
            'transaction_id' => $transaction->id,
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        Notification::create([
            'user_id' => $reviewedUserId,
            'type' => 'rating',
            'related_id' => $transaction->id,
            'message' => Auth::user()->name . ' left a new rating.',
        ]);

        return redirect()->route('profile.ratings')->with('success', 'Rating submitted.');
    }
}
