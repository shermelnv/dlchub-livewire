<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = ['user_id', 'feed_id', 'comment'];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function feed() {
        return $this->belongsTo(Feed::class);
    }
}
