<?php

namespace App\Http\Requests;

use App\Models\Item;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SaveItemRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        $departments = config('um_departments.departments', []);
        $selectedDepartment = (string) $this->input('department');
        $programs = $departments[$selectedDepartment] ?? [];
        $programRules = [
            Rule::requiredIf($programs !== []),
            'nullable',
            'string',
            'max:255',
        ];

        if ($programs !== []) {
            $programRules[] = Rule::in($programs);
        }

        return [
            'title' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string', 'max:100'],
            'custom_category' => ['nullable', 'string', 'max:100'],
            'description' => ['required', 'string', 'min:10', 'max:5000'],
            'department' => ['required', 'string', Rule::in(array_keys($departments))],
            'program' => $programRules,
            'course_code' => ['required', 'string', 'max:20'],
            'listing_type' => ['required', Rule::in([Item::TYPE_SELL, Item::TYPE_RENT])],
            'accepted_payment_methods' => ['required', 'array', 'min:1'],
            'accepted_payment_methods.*' => ['required', 'string', 'distinct', Rule::in(array_keys(Item::paymentMethodOptions()))],
            'minimum_rental_days' => ['nullable', 'required_if:listing_type,'.Item::TYPE_RENT, 'integer', 'min:1', 'max:365'],
            'maximum_rental_days' => ['nullable', 'required_if:listing_type,'.Item::TYPE_RENT, 'integer', 'gte:minimum_rental_days', 'max:365'],
            'daily_rental_rate' => ['nullable', 'required_if:listing_type,'.Item::TYPE_RENT, 'numeric', 'min:0'],
            'condition' => ['required', 'string', Rule::in(config('um_departments.conditions', []))],
            'price' => ['nullable', 'numeric', 'min:0'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'title' => trim((string) $this->input('title')),
            'category' => $this->input('category') === '__custom'
                ? trim((string) $this->input('custom_category'))
                : trim((string) $this->input('category')),
            'custom_category' => trim((string) $this->input('custom_category')),
            'course_code' => strtoupper(trim((string) $this->input('course_code'))),
        ]);
    }

    public function attributes(): array
    {
        return [
            'accepted_payment_methods' => 'accepted payment methods',
            'custom_category' => 'custom category',
            'daily_rental_rate' => 'daily rental rate',
            'maximum_rental_days' => 'maximum rental days',
            'minimum_rental_days' => 'minimum rental days',
        ];
    }
}
