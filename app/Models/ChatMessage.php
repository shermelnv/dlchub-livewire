<?php

// app/Models/ChatMessage.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChatMessage extends Model
{
    protected $fillable = ['group_chat_id', 'user_id', 'message'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function groupChat(): BelongsTo
    {
        return $this->belongsTo(GroupChat::class);
    }
}
