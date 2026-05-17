<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMessageRequest;
use App\Models\Conversation;
use App\Models\Message;
use App\Services\MessagingWorkflowService;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    public function __construct(private readonly MessagingWorkflowService $messages) {}

    public function index()
    {
        $conversations = $this->conversationQuery()->get();
        $activeConversation = $conversations->first();

        if ($activeConversation) {
            $activeConversation->load([
                'starter' => $this->userRatingQuery(),
                'recipient' => $this->userRatingQuery(),
                'item.user',
                'messages.user',
            ]);
        }

        return view('messages.index', [
            'conversations' => $conversations,
            'activeConversation' => $activeConversation,
        ]);
    }

    public function show(Conversation $conversation)
    {
        $this->authorize('view', $conversation);

        $conversation->load([
            'starter' => $this->userRatingQuery(),
            'recipient' => $this->userRatingQuery(),
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

    public function store(StoreMessageRequest $request)
    {
        $message = $this->messages->send($request->user(), $request->validated());

        return redirect()->route('messages.show', $message->conversation)
            ->with('success', $message->type === Message::TYPE_MEETUP_PROPOSAL ? 'Meetup proposal sent.' : 'Message sent.');
    }

    public function acceptProposal(Message $message)
    {
        $this->authorize('respondToProposal', $message);
        $this->messages->acceptProposal(Auth::user(), $message);

        return back()->with('success', 'Meetup proposal accepted.');
    }

    public function declineProposal(Message $message)
    {
        $this->authorize('respondToProposal', $message);
        $this->messages->declineProposal(Auth::user(), $message);

        return back()->with('success', 'Meetup proposal declined.');
    }

    protected function conversationQuery()
    {
        return Conversation::with([
            'starter' => $this->userRatingQuery(),
            'recipient' => $this->userRatingQuery(),
            'item',
            'latestMessage.user',
        ])
            ->where(function ($query) {
                $query->where('starter_id', Auth::id())
                    ->orWhere('recipient_id', Auth::id());
            })
            ->orderByDesc('last_message_at')
            ->orderByDesc('updated_at');
    }

    private function userRatingQuery(): callable
    {
        return fn ($query) => $query
            ->withCount('ratingsReceived')
            ->withAvg('ratingsReceived', 'rating');
    }
}
