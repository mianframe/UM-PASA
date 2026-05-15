<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class NotificationService
{
    public function send(User|int $recipient, string $type, string $message, ?Model $related = null, bool $isRead = false): Notification
    {
        return Notification::create([
            'user_id' => $recipient instanceof User ? $recipient->id : $recipient,
            'type' => $type,
            'related_type' => $related?->getMorphClass(),
            'related_id' => $related?->getKey(),
            'message' => $message,
            'is_read' => $isRead,
        ]);
    }
}
