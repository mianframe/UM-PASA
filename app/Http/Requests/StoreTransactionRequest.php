<?php

namespace App\Http\Requests;

use App\Models\Item;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreTransactionRequest extends FormRequest
{
    public function authorize(): bool
    {
        $item = $this->route('item');

        return $this->user() !== null
            && $item instanceof Item
            && $this->user()->can('request', $item);
    }

    public function rules(): array
    {
        /** @var Item $item */
        $item = $this->route('item');
        $acceptedMethods = $item->accepted_payment_methods ?: array_keys(Item::paymentMethodOptions());

        return [
            'payment_method' => ['required', Rule::in($acceptedMethods)],
            'other_payment_method' => ['nullable', 'required_if:payment_method,other', 'string', 'max:80'],
            'rental_duration_days' => [
                'nullable',
                Rule::requiredIf($item->listing_type === Item::TYPE_RENT),
                'integer',
                'min:'.($item->minimum_rental_days ?? 1),
                'max:'.($item->maximum_rental_days ?? 365),
            ],
        ];
    }
}
