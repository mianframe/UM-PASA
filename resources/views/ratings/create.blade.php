<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <p class="section-kicker">Feedback</p>
                <h2 class="section-title mt-2">{{ __('Leave a Rating') }}</h2>
                <p class="section-copy mt-2">Rate your completed transaction partner and help keep UM-Pasa trustworthy.</p>
            </div>
            <a href="{{ route('transactions.history') }}" class="btn-secondary">Back to History</a>
        </div>
    </x-slot>

    <div class="page-wrap">
        <div class="rating-shell">
            <div class="rating-review-card">
                <p class="transaction-kicker">Reviewing</p>
                <h3>{{ $reviewedUser->name }}</h3>
                <p>{{ $reviewedUser->email }}</p>
                <div class="transaction-meta-grid">
                    <div class="transaction-meta-tile">
                        <span>Transaction</span>
                        <strong>#{{ $transaction->id }}</strong>
                    </div>
                    <div class="transaction-meta-tile">
                        <span>Item</span>
                        <strong>{{ $transaction->item->title }}</strong>
                    </div>
                    <div class="transaction-meta-tile">
                        <span>Status</span>
                        <strong>{{ ucfirst($transaction->status) }}</strong>
                    </div>
                </div>
            </div>

            <form method="POST" action="{{ route('ratings.store', $transaction) }}" class="rating-form">
                @csrf
                <div>
                    <label class="form-label">Rating</label>
                    <select name="rating" class="glass-input">
                        <option value="">Choose a rating</option>
                        @for($i = 1; $i <= 5; $i++)
                            <option value="{{ $i }}" {{ old('rating') == $i ? 'selected' : '' }}>{{ $i }} star{{ $i > 1 ? 's' : '' }}</option>
                        @endfor
                    </select>
                    @error('rating') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="form-label">Comment</label>
                    <textarea name="comment" rows="5" class="glass-input" placeholder="Share how the transaction went...">{{ old('comment') }}</textarea>
                    @error('comment') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="btn-primary">Submit Rating</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
