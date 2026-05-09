<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Leave a Rating') }}</h2>
                <p class="text-sm text-gray-500 mt-1">Rate your completed transaction partner.</p>
            </div>
            <a href="{{ route('transactions.history') }}" class="px-4 py-2 bg-gray-100 text-gray-800 rounded-md">Back to History</a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-3xl border border-gray-200 p-6 shadow-sm">
                <div class="space-y-4">
                    <div>
                        <div class="text-sm text-gray-500">Reviewing</div>
                        <p class="text-lg font-semibold text-gray-900">{{ $reviewedUser->name }}</p>
                        <p class="text-sm text-gray-600">{{ $reviewedUser->email }}</p>
                    </div>

                    <form method="POST" action="{{ route('ratings.store', $transaction) }}" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Rating</label>
                            <select name="rating" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="">Choose a rating</option>
                                @for($i = 1; $i <= 5; $i++)
                                    <option value="{{ $i }}" {{ old('rating') == $i ? 'selected' : '' }}>{{ $i }} star{{ $i > 1 ? 's' : '' }}</option>
                                @endfor
                            </select>
                            @error('rating') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Comment</label>
                            <textarea name="comment" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('comment') }}</textarea>
                            @error('comment') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="flex justify-end gap-3">
                            <button type="submit" class="px-6 py-3 bg-blue-600 text-white rounded-md">Submit Rating</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
