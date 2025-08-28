<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reaction extends Model
{
     protected $fillable = ['user_id', 'feed_id', 'type'];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function feed() {
        return $this->belongsTo(Feed::class); // changed post → feed
    }
}
