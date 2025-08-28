<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Feed extends Model
{
    use HasFactory;

    protected $fillable = [
    'user_id',
    'title',         
    'content',
    'org_id',
    'type',
    'published_at',
    'photo_url',
    'privacy'
];

protected $casts = [
    'published_at' => 'datetime',
];



    public function user()
    {
        return $this->belongsTo(User::class);
    }
        public function comments()
    {
        return $this->hasMany(\App\Models\Comment::class);
    }

    public function reactions()
    {
        return $this->hasMany(\App\Models\Reaction::class);
    }

// Feed.php
public function org()
{
    return $this->belongsTo(Org::class, 'org_id');
}

    
// Feed.php
public function scopeVisibleToUser($query, $user)
{
    return $query->when(!in_array($user->role, ['admin', 'superadmin']), function($q) use ($user) {
        $q->where('privacy', 'public') // public posts
          ->orWhere(function($q2) use ($user) {
              $q2->where('privacy', 'private')
                 ->where(function($q3) use ($user) {
                     $q3->whereHas('org.followers', fn($q4) => $q4->where('users.id', $user->id)) // followers
                        ->orWhere('org_id', $user->id); // OR org itself
                 });
          });
    });
}

}