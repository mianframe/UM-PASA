<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RejectItemRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() === true;
    }

    public function rules(): array
    {
        return [
            'rejection_reason' => ['nullable', 'string', 'max:500'],
        ];
    }
}
