<?php

namespace App\Services;

use App\Models\Conversation;
use App\Models\Item;
use App\Models\Message;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class MessagingWorkflowService
{
    public function __construct(private readonly NotificationService $notifications) {}

    public function send(User $sender, array $data): Message
    {
        $type = $data['type'] ?? Message::TYPE_TEXT;
        $conversation = isset($data['conversation_id'])
            ? Conversation::findOrFail($data['conversation_id'])
            : $this->findOrCreateConversation($sender, $data);

        abort_unless($conversation->isParticipant($sender->id), 403);

        $message = DB::transaction(function () use ($conversation, $data, $sender, $type) {
            $message = Message::create([
                'conversation_id' => $conversation->id,
                'user_id' => $sender->id,
                'body' => $type === Message::TYPE_MEETUP_PROPOSAL
                    ? (($data['body'] ?? null) ?: 'Meetup proposal sent.')
                    : $data['body'],
                'type' => $type,
                'proposal_status' => $type === Message::TYPE_MEETUP_PROPOSAL ? Message::PROPOSAL_PENDING : null,
                'meetup_location' => $data['meetup_location'] ?? null,
                'meetup_time' => $data['meetup_time'] ?? null,
                'meta' => $conversation->item ? ['item_title' => $conversation->item->title] : null,
            ]);

            $conversation->update(['last_message_at' => now()]);

            return $message->load('conversation.starter', 'conversation.recipient');
        });

        $recipient = $message->conversation->otherParticipant($sender->id);

        if ($recipient) {
            $this->notifications->send(
                $recipient,
                $type === Message::TYPE_MEETUP_PROPOSAL ? 'meetup' : 'message',
                $type === Message::TYPE_MEETUP_PROPOSAL
                    ? "{$sender->name} sent a meetup proposal."
                    : "{$sender->name} sent you a message.",
                $message->conversation
            );
        }

        return $message;
    }

    public function acceptProposal(User $actor, Message $message): Message
    {
        return $this->resolveProposal($actor, $message, Message::PROPOSAL_ACCEPTED, 'accepted');
    }

    public function declineProposal(User $actor, Message $message): Message
    {
        return $this->resolveProposal($actor, $message, Message::PROPOSAL_DECLINED, 'declined');
    }

    public function findOrCreateConversation(User $sender, array $data): Conversation
    {
        $recipient = User::findOrFail($data['recipient_id']);
        $item = isset($data['item_id']) ? Item::findOrFail($data['item_id']) : null;

        abort_if($recipient->id === $sender->id, 403);

        if ($item) {
            $buyerMessagingSeller = $item->user_id === $recipient->id;
            $sellerMessagingTransactionBuyer = $item->user_id === $sender->id
                && Transaction::where('item_id', $item->id)
                    ->where('buyer_id', $recipient->id)
                    ->where('seller_id', $sender->id)
                    ->exists();

            abort_unless($buyerMessagingSeller || $sellerMessagingTransactionBuyer, 403);
        }

        $conversation = Conversation::where(function ($query) use ($recipient, $sender) {
            $query->where(function ($innerQuery) use ($recipient, $sender) {
                $innerQuery->where('starter_id', $sender->id)
                    ->where('recipient_id', $recipient->id);
            })->orWhere(function ($innerQuery) use ($recipient, $sender) {
                $innerQuery->where('starter_id', $recipient->id)
                    ->where('recipient_id', $sender->id);
            });
        });

        if ($item) {
            $conversation->where('item_id', $item->id);
        } else {
            $conversation->whereNull('item_id');
        }

        return $conversation->first() ?: Conversation::create([
            'starter_id' => $sender->id,
            'recipient_id' => $recipient->id,
            'item_id' => $item?->id,
            'last_message_at' => now(),
        ]);
    }

    private function resolveProposal(User $actor, Message $message, string $status, string $verb): Message
    {
        $message = DB::transaction(function () use ($actor, $message, $status, $verb) {
            $lockedMessage = Message::with('conversation.starter', 'conversation.recipient')
                ->whereKey($message->id)
                ->lockForUpdate()
                ->firstOrFail();

            abort_unless(
                $lockedMessage->type === Message::TYPE_MEETUP_PROPOSAL
                && $lockedMessage->proposal_status === Message::PROPOSAL_PENDING
                && $lockedMessage->user_id !== $actor->id
                && $lockedMessage->conversation->isParticipant($actor->id),
                403
            );

            $lockedMessage->update(['proposal_status' => $status]);
            $lockedMessage->conversation->update(['last_message_at' => now()]);

            Message::create([
                'conversation_id' => $lockedMessage->conversation_id,
                'user_id' => $actor->id,
                'type' => Message::TYPE_SYSTEM,
                'body' => "Meetup proposal {$verb}.",
            ]);

            return $lockedMessage->refresh()->load('conversation.starter', 'conversation.recipient');
        });

        $this->notifications->send(
            $message->user_id,
            'meetup',
            "{$actor->name} {$verb} your meetup proposal.",
            $message->conversation
        );

        return $message;
    }
}
