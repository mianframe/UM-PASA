<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Message extends Model
{
    use HasFactory;

    public const TYPE_TEXT = 'text';

    public const TYPE_SYSTEM = 'system';

    public const TYPE_MEETUP_PROPOSAL = 'meetup_proposal';

    public const PROPOSAL_PENDING = 'pending';

    public const PROPOSAL_ACCEPTED = 'accepted';

    public const PROPOSAL_DECLINED = 'declined';

    protected $fillable = [
        'conversation_id',
        'user_id',
        'body',
        'type',
        'proposal_status',
        'meetup_location',
        'meetup_time',
        'meta',
        'read_at',
    ];

    protected $casts = [
        'meetup_time' => 'datetime',
        'meta' => 'array',
        'read_at' => 'datetime',
    ];

    public function conversation(): BelongsTo
    {
        return $this->belongsTo(Conversation::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
