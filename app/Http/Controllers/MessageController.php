<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\Item;
use App\Models\Message;
use App\Models\Notification;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    public function index()
    {
        $conversations = $this->conversationQuery()->get();
        $activeConversation = $conversations->first();

        if ($activeConversation) {
            $activeConversation->load(['starter', 'recipient', 'item.user', 'messages.user']);
        }

        return view('messages.index', [
            'conversations' => $conversations,
            'activeConversation' => $activeConversation,
        ]);
    }

    public function show(Conversation $conversation)
    {
        abort_unless($this->isParticipant($conversation), 403);

        $conversation->load([
            'starter',
            'recipient',
            'item.user',
            'messages.user',
        ]);

        $conversation->messages()
            ->whereNull('read_at')
            ->where('user_id', '!=', Auth::id())
            ->update(['read_at' => now()]);

        return view('messages.index', [
            'conversations' => $this->conversationQuery()->get(),
            'activeConversation' => $conversation,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'conversation_id' => ['nullable', 'exists:conversations,id', 'required_without:recipient_id'],
            'recipient_id' => ['nullable', 'exists:users,id', 'required_without:conversation_id'],
            'item_id' => ['nullable', 'exists:items,id'],
            'body' => ['nullable', 'string', 'max:1000'],
            'type' => ['nullable', 'in:text,meetup_proposal'],
            'meetup_location' => ['nullable', 'string', 'max:255'],
            'meetup_time' => ['nullable', 'date', 'after:now'],
        ]);

        $type = $request->input('type', 'text');
        $conversation = $request->filled('conversation_id')
            ? Conversation::findOrFail($request->conversation_id)
            : $this->findOrCreateConversation($request);

        abort_unless($this->isParticipant($conversation), 403);

        if ($type === 'meetup_proposal') {
            $request->validate([
                'meetup_location' => ['required', 'string', 'max:255'],
                'meetup_time' => ['required', 'date', 'after:now'],
            ]);
        } else {
            $request->validate([
                'body' => ['required', 'string', 'max:1000'],
            ]);
        }

        $message = Message::create([
            'conversation_id' => $conversation->id,
            'user_id' => Auth::id(),
            'body' => $type === 'meetup_proposal'
                ? ($request->body ?: 'Meetup proposal sent.')
                : $request->body,
            'type' => $type,
            'proposal_status' => $type === 'meetup_proposal' ? 'pending' : null,
            'meetup_location' => $request->meetup_location,
            'meetup_time' => $request->meetup_time,
            'meta' => $conversation->item ? ['item_title' => $conversation->item->title] : null,
        ]);

        $conversation->update(['last_message_at' => now()]);

        $recipient = $conversation->otherParticipant(Auth::id());

        if ($recipient) {
            Notification::create([
                'user_id' => $recipient->id,
                'type' => $type === 'meetup_proposal' ? 'meetup' : 'message',
                'related_id' => $conversation->id,
                'message' => $type === 'meetup_proposal'
                    ? Auth::user()->name.' sent a meetup proposal.'
                    : Auth::user()->name.' sent you a message.',
            ]);
        }

        return redirect()->route('messages.show', $conversation)
            ->with('success', $message->type === 'meetup_proposal' ? 'Meetup proposal sent.' : 'Message sent.');
    }

    public function acceptProposal(Message $message)
    {
        $conversation = $message->conversation()->with(['starter', 'recipient', 'item'])->firstOrFail();
        abort_unless($this->isParticipant($conversation), 403);
        abort_if($message->type !== 'meetup_proposal', 403);
        abort_if($message->user_id === Auth::id(), 403);
        abort_if($message->proposal_status !== 'pending', 403);

        $message->update(['proposal_status' => 'accepted']);
        $conversation->update(['last_message_at' => now()]);

        Message::create([
            'conversation_id' => $conversation->id,
            'user_id' => Auth::id(),
            'type' => 'system',
            'body' => 'Meetup proposal accepted.',
        ]);

        Notification::create([
            'user_id' => $message->user_id,
            'type' => 'meetup',
            'related_id' => $conversation->id,
            'message' => Auth::user()->name.' accepted your meetup proposal.',
        ]);

        return back()->with('success', 'Meetup proposal accepted.');
    }

    public function declineProposal(Message $message)
    {
        $conversation = $message->conversation()->with(['starter', 'recipient', 'item'])->firstOrFail();
        abort_unless($this->isParticipant($conversation), 403);
        abort_if($message->type !== 'meetup_proposal', 403);
        abort_if($message->user_id === Auth::id(), 403);
        abort_if($message->proposal_status !== 'pending', 403);

        $message->update(['proposal_status' => 'declined']);
        $conversation->update(['last_message_at' => now()]);

        Message::create([
            'conversation_id' => $conversation->id,
            'user_id' => Auth::id(),
            'type' => 'system',
            'body' => 'Meetup proposal declined.',
        ]);

        Notification::create([
            'user_id' => $message->user_id,
            'type' => 'meetup',
            'related_id' => $conversation->id,
            'message' => Auth::user()->name.' declined your meetup proposal.',
        ]);

        return back()->with('success', 'Meetup proposal declined.');
    }

    protected function conversationQuery()
    {
        return Conversation::with(['starter', 'recipient', 'item', 'latestMessage.user'])
            ->where(function ($query) {
                $query->where('starter_id', Auth::id())
                    ->orWhere('recipient_id', Auth::id());
            })
            ->orderByDesc('last_message_at')
            ->orderByDesc('updated_at');
    }

    protected function isParticipant(Conversation $conversation): bool
    {
        return in_array(Auth::id(), [$conversation->starter_id, $conversation->recipient_id], true);
    }

    protected function findOrCreateConversation(Request $request): Conversation
    {
        $recipient = User::findOrFail($request->recipient_id);
        $item = $request->filled('item_id') ? Item::findOrFail($request->item_id) : null;

        abort_if($recipient->id === Auth::id(), 403);

        if ($item) {
            $buyerMessagingSeller = $item->user_id === $recipient->id;
            $sellerMessagingTransactionBuyer = $item->user_id === Auth::id()
                && Transaction::where('item_id', $item->id)
                    ->where('buyer_id', $recipient->id)
                    ->where('seller_id', Auth::id())
                    ->exists();

            abort_unless($buyerMessagingSeller || $sellerMessagingTransactionBuyer, 403);
        }

        $conversation = Conversation::where(function ($query) use ($recipient) {
            $query->where('starter_id', Auth::id())
                ->where('recipient_id', $recipient->id);
        })->orWhere(function ($query) use ($recipient) {
            $query->where('starter_id', $recipient->id)
                ->where('recipient_id', Auth::id());
        });

        if ($item) {
            $conversation->where('item_id', $item->id);
        } else {
            $conversation->whereNull('item_id');
        }

        $existing = $conversation->first();

        if ($existing) {
            return $existing;
        }

        return Conversation::create([
            'starter_id' => Auth::id(),
            'recipient_id' => $recipient->id,
            'item_id' => $item?->id,
            'last_message_at' => now(),
        ]);
    }
}
