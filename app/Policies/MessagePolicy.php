<?php

namespace App\Policies;

use App\Models\Message;
use App\Models\User;

class MessagePolicy
{
    public function respondToProposal(User $user, Message $message): bool
    {
        $conversation = $message->relationLoaded('conversation')
            ? $message->conversation
            : $message->conversation()->first();

        return $conversation !== null
            && $conversation->isParticipant($user->id)
            && $message->type === Message::TYPE_MEETUP_PROPOSAL
            && $message->user_id !== $user->id
            && $message->proposal_status === Message::PROPOSAL_PENDING;
    }
}
