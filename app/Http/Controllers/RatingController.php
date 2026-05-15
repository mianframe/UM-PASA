<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRatingRequest;
use App\Models\Rating;
use App\Models\Transaction;
use App\Services\NotificationService;
use Illuminate\Support\Facades\Auth;

class RatingController extends Controller
{
    public function __construct(private readonly NotificationService $notifications) {}

    public function create(Transaction $transaction)
    {
        $this->authorize('rate', $transaction);

        $reviewedUser = Auth::id() === $transaction->buyer_id
            ? $transaction->seller
            : $transaction->buyer;

        return view('ratings.create', compact('transaction', 'reviewedUser'));
    }

    public function store(StoreRatingRequest $request, Transaction $transaction)
    {
        $data = $request->validated();
        $reviewedUserId = Auth::id() === $transaction->buyer_id
            ? $transaction->seller_id
            : $transaction->buyer_id;

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
            'rating' => $data['rating'],
            'comment' => $data['comment'] ?? null,
        ]);

        $this->notifications->send($reviewedUserId, 'rating', Auth::user()->name.' left a new rating.', $transaction);

        return redirect()->route('profile.ratings')->with('success', 'Rating submitted.');
    }
}
