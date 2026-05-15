<?php

namespace App\Http\Requests;

use App\Models\Transaction;
use Illuminate\Foundation\Http\FormRequest;

class ApproveTransactionRequest extends FormRequest
{
    public function authorize(): bool
    {
        $transaction = $this->route('transaction');

        return $this->user() !== null
            && $transaction instanceof Transaction
            && $this->user()->can('approve', $transaction);
    }

    public function rules(): array
    {
        return [
            'meetup_location' => ['required', 'string', 'max:255'],
            'meetup_time' => ['required', 'date', 'after:now'],
        ];
    }
}
