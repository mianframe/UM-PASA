@php
    $editing = isset($item);
    $previewImage = old('image_preview');

    if (! $previewImage && $editing && $item->image) {
        $previewImage = asset('storage/' . $item->image);
    }

    $selectedPaymentMethods = old('accepted_payment_methods', $item->accepted_payment_methods ?? ['gcash', 'maya', 'bank_transfer', 'cash_on_pickup']);
@endphp

<form
    method="POST"
    action="{{ $editing ? route('items.update', $item) : route('items.store') }}"
    enctype="multipart/form-data"
    class="space-y-6"
    x-data="{
        preview: '{{ $previewImage }}',
        departmentPrograms: @js($departments),
        department: @js(old('department', $item->department ?? '')),
        selectedProgram: @js(old('program', $item->program ?? '')),
        listingType: @js(old('listing_type', $item->listing_type ?? '')),
        get programs() {
            return this.departmentPrograms[this.department] || [];
        }
    }"
>
    @csrf
    @if($editing)
        @method('PUT')
    @endif

    <div class="grid gap-6 lg:grid-cols-[1.15fr_0.85fr]">
        <div class="space-y-5">
            <div>
                <label for="title" class="text-sm font-medium text-slate-200">Title</label>
                <input id="title" type="text" name="title" value="{{ old('title', $item->title ?? '') }}" class="glass-input" placeholder="Example: Engineering Drawing Set" />
                @error('title') <p class="mt-2 text-sm text-red-200">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="category" class="text-sm font-medium text-slate-200">Category</label>
                <select id="category" name="category" class="glass-input">
                    <option value="">Select category</option>
                    @foreach($categories as $category)
                        <option value="{{ $category }}" @selected(old('category', $item->category ?? '') === $category)>{{ $category }}</option>
                    @endforeach
                </select>
                @error('category') <p class="mt-2 text-sm text-red-200">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="description" class="text-sm font-medium text-slate-200">Description</label>
                <textarea id="description" name="description" rows="6" class="glass-input" placeholder="Describe the condition, inclusions, and ideal buyer.">{{ old('description', $item->description ?? '') }}</textarea>
                @error('description') <p class="mt-2 text-sm text-red-200">{{ $message }}</p> @enderror
            </div>

            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label for="department" class="text-sm font-medium text-slate-200">Department</label>
                    <select id="department" name="department" class="glass-input" x-model="department">
                        <option value="">Select department</option>
                        @foreach($departments as $department => $programs)
                            <option value="{{ $department }}" @selected(old('department', $item->department ?? '') === $department)>{{ $department }}</option>
                        @endforeach
                    </select>
                    @error('department') <p class="mt-2 text-sm text-red-200">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="program" class="text-sm font-medium text-slate-200">Program</label>
                    <select id="program" name="program" class="glass-input" x-model="selectedProgram">
                        <option value="">Select program</option>
                        <template x-for="programOption in programs" :key="programOption">
                            <option :value="programOption" x-text="programOption"></option>
                        </template>
                    </select>
                    @error('program') <p class="mt-2 text-sm text-red-200">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label for="course_code" class="text-sm font-medium text-slate-200">Course Code</label>
                    <input id="course_code" type="text" name="course_code" value="{{ old('course_code', $item->course_code ?? '') }}" class="glass-input" placeholder="Example: CS101" />
                    @error('course_code') <p class="mt-2 text-sm text-red-200">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="listing_type" class="text-sm font-medium text-slate-200">Listing Type</label>
                    <select id="listing_type" name="listing_type" class="glass-input" x-model="listingType">
                        <option value="">Select listing type</option>
                        <option value="sell" @selected(old('listing_type', $item->listing_type ?? '') === 'sell')>Sell</option>
                        <option value="rent" @selected(old('listing_type', $item->listing_type ?? '') === 'rent')>Rent</option>
                    </select>
                    @error('listing_type') <p class="mt-2 text-sm text-red-200">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="glass-panel p-5">
                <h3 class="text-lg font-semibold text-white">Accepted Payment Methods</h3>
                <div class="mt-4 grid gap-3 sm:grid-cols-2">
                    @foreach(\App\Models\Item::paymentMethodOptions() as $methodValue => $methodLabel)
                        <label class="flex items-center gap-3 rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-sm text-slate-200">
                            <input type="checkbox" name="accepted_payment_methods[]" value="{{ $methodValue }}" class="rounded border-white/20 bg-white/10 text-red-600" @checked(in_array($methodValue, $selectedPaymentMethods, true))>
                            <span>{{ $methodLabel }}</span>
                        </label>
                    @endforeach
                </div>
                @error('accepted_payment_methods') <p class="mt-3 text-sm text-red-200">{{ $message }}</p> @enderror
            </div>

            <div x-show="listingType === 'rent'" x-transition class="glass-panel p-5">
                <h3 class="text-lg font-semibold text-white">Rental Terms</h3>
                <div class="mt-4 grid gap-4 sm:grid-cols-3">
                    <div>
                        <label for="minimum_rental_days" class="text-sm font-medium text-slate-200">Minimum Duration</label>
                        <input id="minimum_rental_days" type="number" min="1" max="365" name="minimum_rental_days" value="{{ old('minimum_rental_days', $item->minimum_rental_days ?? '') }}" class="glass-input" placeholder="3" />
                        @error('minimum_rental_days') <p class="mt-2 text-sm text-red-200">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="maximum_rental_days" class="text-sm font-medium text-slate-200">Maximum Duration</label>
                        <input id="maximum_rental_days" type="number" min="1" max="365" name="maximum_rental_days" value="{{ old('maximum_rental_days', $item->maximum_rental_days ?? $item->rental_duration_days ?? '') }}" class="glass-input" placeholder="14" />
                        @error('maximum_rental_days') <p class="mt-2 text-sm text-red-200">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="daily_rental_rate" class="text-sm font-medium text-slate-200">Daily Rental Rate</label>
                        <input id="daily_rental_rate" type="number" step="0.01" min="0" name="daily_rental_rate" value="{{ old('daily_rental_rate', $item->daily_rental_rate ?? '') }}" class="glass-input" placeholder="25.00" />
                        @error('daily_rental_rate') <p class="mt-2 text-sm text-red-200">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label for="condition" class="text-sm font-medium text-slate-200">Condition</label>
                    <select id="condition" name="condition" class="glass-input">
                        <option value="">Select condition</option>
                        @foreach($conditions as $condition)
                            <option value="{{ $condition }}" @selected(old('condition', $item->condition ?? '') === $condition)>{{ str($condition)->replace('_', ' ')->title() }}</option>
                        @endforeach
                    </select>
                    @error('condition') <p class="mt-2 text-sm text-red-200">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="price" class="text-sm font-medium text-slate-200">Price</label>
                    <input id="price" type="number" step="0.01" min="0" name="price" value="{{ old('price', $item->price ?? '') }}" class="glass-input" placeholder="Example: 250.00" />
                    @error('price') <p class="mt-2 text-sm text-red-200">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        <div class="space-y-4">
            <div class="glass-panel p-5">
                <div class="mb-4">
                    <h3 class="text-lg font-semibold text-white">Image Upload</h3>
                    <p class="mt-1 text-sm text-slate-300">Accepted formats: JPG or PNG</p>
                </div>

                <label for="image" class="flex min-h-72 cursor-pointer flex-col items-center justify-center rounded-2xl border border-dashed border-white/20 bg-white/5 p-4 text-center">
                    <template x-if="preview">
                        <img :src="preview" alt="Item preview" class="h-56 w-full rounded-2xl object-cover" />
                    </template>
                    <template x-if="!preview">
                        <div>
                            <div class="text-sm font-semibold text-white">Click to upload an image</div>
                            <div class="mt-2 text-sm text-slate-400">Preview will appear here before posting.</div>
                        </div>
                    </template>
                </label>
                <input id="image" type="file" name="image" accept="image/png, image/jpeg" class="sr-only"
                    @change="const file = $event.target.files[0]; if (file) { preview = URL.createObjectURL(file); }" />
                @error('image') <p class="mt-3 text-sm text-red-200">{{ $message }}</p> @enderror
            </div>

            <div class="glass-panel p-5">
                <h3 class="text-lg font-semibold text-white">Listing Tips</h3>
                <ul class="mt-3 space-y-2 text-sm text-slate-300">
                    <li>Use a clear item title for faster browsing.</li>
                    <li>Pick the correct department and program for live filtering.</li>
                    <li>Set the listing type to sell or rent.</li>
                    <li>Rental listings need minimum and maximum duration plus a daily rate.</li>
                    <li>Select every payment method you can accept.</li>
                    <li>Price can be left blank for negotiation.</li>
                    <li>Admin approval is required before listings appear publicly.</li>
                </ul>
            </div>
        </div>
    </div>

    <div class="flex flex-col gap-3 sm:flex-row sm:justify-end">
        @if($editing)
            <a href="{{ route('marketplace.show', $item) }}" class="btn-secondary">Cancel</a>
        @else
            <a href="{{ route('marketplace.index') }}" class="btn-secondary">Back to Marketplace</a>
        @endif
        <button type="submit" class="btn-primary">{{ $editing ? 'Save Changes' : 'Post Item' }}</button>
    </div>
</form>
