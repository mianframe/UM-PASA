@props([
    'transaction',
    'buttonClass' => 'btn-primary',
    'compact' => false,
])

<form
    method="POST"
    action="{{ route('transactions.paymentProof', $transaction) }}"
    enctype="multipart/form-data"
    class="payment-proof-uploader {{ $compact ? 'payment-proof-uploader-compact' : '' }}"
    x-data="{
        fileName: '',
        fileSize: '',
        previewUrl: '',
        isImage: false,
        errorMessage: '',
        maxBytes: 2 * 1024 * 1024,
        updatePreview(event) {
            const file = event.target.files[0];

            if (this.previewUrl) {
                URL.revokeObjectURL(this.previewUrl);
            }

            if (!file) {
                this.fileName = '';
                this.fileSize = '';
                this.previewUrl = '';
                this.isImage = false;
                this.errorMessage = '';
                return;
            }

            this.fileName = file.name;
            this.fileSize = `${(file.size / 1024 / 1024).toFixed(2)} MB`;
            this.isImage = file.type.startsWith('image/');
            this.previewUrl = this.isImage ? URL.createObjectURL(file) : '';
            this.errorMessage = file.size > this.maxBytes
                ? 'This file is larger than 2MB. Please compress it or choose a smaller screenshot.'
                : '';
        }
    }"
>
    @csrf

    @if($transaction->payment_proof)
        <div class="payment-proof-status payment-proof-status-success">
            <div class="payment-proof-status-title">Payment proof already uploaded</div>
            <a href="{{ route('transactions.paymentProof.show', $transaction) }}" target="_blank" class="payment-proof-link">
                View uploaded proof
            </a>
        </div>
    @else
        <div class="payment-proof-status payment-proof-status-waiting">
            <div class="payment-proof-status-title">No proof uploaded yet</div>
            <p>Choose a JPG, PNG, or PDF up to 2MB. A preview will appear here before you upload.</p>
        </div>
    @endif

    @error('payment_proof')
        <div class="payment-proof-status payment-proof-status-error">
            {{ $message }}
        </div>
    @enderror

    <div class="payment-proof-controls">
        <label class="payment-proof-field">
            <span>Proof file</span>
            <input
                type="file"
                name="payment_proof"
                accept="image/png,image/jpeg,application/pdf"
                class="glass-input"
                required
                x-on:change="updatePreview"
            >
        </label>
        <button
            type="submit"
            class="{{ $buttonClass }} payment-proof-button disabled:cursor-not-allowed disabled:opacity-50"
            data-loading-label="Uploading..."
            x-bind:disabled="Boolean(errorMessage)"
        >
            <span x-text="fileName ? 'Upload Selected Proof' : 'Upload Proof'">Upload Proof</span>
        </button>
    </div>

    <div x-show="fileName" x-transition class="payment-proof-preview" style="display: none;">
        <div class="payment-proof-preview-inner">
            <template x-if="isImage">
                <img :src="previewUrl" alt="Selected payment proof preview" class="payment-proof-preview-media">
            </template>
            <template x-if="!isImage">
                <div class="payment-proof-preview-file">
                    PDF
                </div>
            </template>
            <div class="payment-proof-preview-copy">
                <div class="payment-proof-preview-title">Selected proof ready to upload</div>
                <div class="payment-proof-preview-name" x-text="fileName"></div>
                <div class="payment-proof-preview-size" x-text="fileSize"></div>
                <p class="payment-proof-preview-help" x-show="!errorMessage">Click the upload button to save this file to the transaction.</p>
                <p class="payment-proof-preview-error" x-show="errorMessage" x-text="errorMessage"></p>
            </div>
        </div>
    </div>
</form>
