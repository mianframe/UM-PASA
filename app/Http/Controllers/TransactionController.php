<?php

namespace App\Http\Controllers;

use App\Http\Requests\ApproveTransactionRequest;
use App\Http\Requests\StoreTransactionRequest;
use App\Http\Requests\UploadPaymentProofRequest;
use App\Models\Item;
use App\Models\Rating;
use App\Models\Transaction;
use App\Services\TransactionWorkflowService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class TransactionController extends Controller
{
    public function __construct(private readonly TransactionWorkflowService $transactions) {}

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
            ->where('status', Transaction::STATUS_PENDING)
            ->latest()
            ->paginate(12);

        return view('transactions.pending', compact('transactions'));
    }

    public function store(StoreTransactionRequest $request, Item $item)
    {
        $this->transactions->requestItem($request->user(), $item, $request->validated());

        return redirect()->route('transactions.history')->with('success', 'Request sent to the seller.');
    }

    public function approve(ApproveTransactionRequest $request, Transaction $transaction)
    {
        $this->transactions->approve($transaction, $request->validated());

        return back()->with('success', 'Request approved successfully.');
    }

    public function reject(Transaction $transaction)
    {
        $this->authorize('reject', $transaction);
        $this->transactions->reject($transaction);

        return back()->with('success', 'Request rejected.');
    }

    public function complete(Transaction $transaction)
    {
        $this->authorize('complete', $transaction);
        $this->transactions->complete($transaction);

        return redirect()->route('transactions.show', $transaction)->with('success', 'Transaction completed.');
    }

    public function show(Transaction $transaction)
    {
        $this->authorize('view', $transaction);

        $transaction->load(['item', 'buyer', 'seller']);

        $reviewedUserId = Auth::id() === $transaction->buyer_id
            ? $transaction->seller_id
            : $transaction->buyer_id;

        $canRate = $transaction->status === Transaction::STATUS_COMPLETED && ! Rating::where('reviewer_id', Auth::id())
            ->where('transaction_id', $transaction->id)
            ->where('reviewed_user_id', $reviewedUserId)
            ->exists();

        return view('transactions.receipt', compact('transaction', 'canRate'));
    }

    public function uploadProof(UploadPaymentProofRequest $request, Transaction $transaction)
    {
        $this->transactions->uploadProof($transaction, $request->file('payment_proof'));

        return back()->with('success', 'Payment proof uploaded successfully.');
    }

    public function paymentProof(Transaction $transaction)
    {
        $this->authorize('view', $transaction);

        abort_unless($transaction->payment_proof, 404);

        $disk = Storage::disk('local')->exists($transaction->payment_proof) ? 'local' : 'public';

        abort_unless(Storage::disk($disk)->exists($transaction->payment_proof), 404);

        return Storage::disk($disk)->response($transaction->payment_proof);
    }
}
