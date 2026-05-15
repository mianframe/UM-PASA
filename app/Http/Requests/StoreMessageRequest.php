<?php

namespace App\Http\Requests;

use App\Models\Message;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreMessageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'conversation_id' => ['nullable', 'integer', 'exists:conversations,id', 'required_without:recipient_id'],
            'recipient_id' => ['nullable', 'integer', 'exists:users,id', 'required_without:conversation_id'],
            'item_id' => ['nullable', 'integer', 'exists:items,id'],
            'body' => ['nullable', 'string', 'max:1000'],
            'type' => ['nullable', Rule::in([Message::TYPE_TEXT, Message::TYPE_MEETUP_PROPOSAL])],
            'meetup_location' => ['nullable', 'string', 'max:255'],
            'meetup_time' => ['nullable', 'date', 'after:now'],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->sometimes('body', ['required', 'string', 'max:1000'], function () {
            return $this->input('type', Message::TYPE_TEXT) !== Message::TYPE_MEETUP_PROPOSAL;
        });

        $validator->sometimes(['meetup_location', 'meetup_time'], ['required'], function () {
            return $this->input('type') === Message::TYPE_MEETUP_PROPOSAL;
        });
    }
}
