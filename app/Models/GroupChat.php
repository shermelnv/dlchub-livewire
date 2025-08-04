<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GroupChat extends Model
{
    protected $fillable = [
        'group_owner_id', 
        'name', 
        'description', 
        'group_code'
    ];


    /**
     * Messages sent within this group chat.
     */
    public function messages(): HasMany
    {
        return $this->hasMany(ChatMessage::class);
    }

    /**
     * Users who are members of this group chat.
     */
    public function members(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'group_chat_user', 'group_chat_id', 'user_id');
    }

    /**
     * Alias for members(), used interchangeably.
     */
    public function users(): BelongsToMany
    {
        return $this->members(); // Or just remove this if redundant.
    }

    public function owner()
{
    return $this->belongsTo(User::class, 'group_owner_id');
}

public function requests()
{
    return $this->hasMany(GroupMemberRequest::class, 'group_id');
}

}
