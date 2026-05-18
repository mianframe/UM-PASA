<?php

namespace App\Http\Requests;

use App\Models\Transaction;
use Illuminate\Foundation\Http\FormRequest;

class UploadPaymentProofRequest extends FormRequest
{
    public function authorize(): bool
    {
        $transaction = $this->route('transaction');

        return $this->user() !== null
            && $transaction instanceof Transaction
            && $this->user()->can('uploadProof', $transaction);
    }

    public function rules(): array
    {
        return [
            'payment_proof' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:2048'],
        ];
    }

    public function messages(): array
    {
        return [
            'payment_proof.required' => 'Please choose a payment proof file before uploading.',
            'payment_proof.uploaded' => 'Payment proof failed to upload. The file may be over the server limit, so please use a file 2MB or smaller.',
            'payment_proof.mimes' => 'Payment proof must be a JPG, PNG, or PDF file.',
            'payment_proof.max' => 'Payment proof must be 2MB or smaller. Please compress the image or upload a smaller screenshot.',
        ];
    }
}
