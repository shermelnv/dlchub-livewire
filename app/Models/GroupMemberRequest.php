<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GroupMemberRequest extends Model
{
    protected $fillable = [
        'group_id', 'user_id', 'status', 'message', 'responded_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function group()
    {
        return $this->belongsTo(GroupChat::class, 'group_id');
    }
}
