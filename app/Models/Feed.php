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
    return $this->belongsTo(User::class, 'org_id'); // points to the user with role=org
}


    
// Feed.php
// Feed.php
public function scopeVisibleToUser($query, $user)
{
    // Admins and superadmins see everything
    if (in_array($user->role, ['admin', 'superadmin'])) {
        return $query;
    }

    // Other users: public posts or private posts they are allowed to see
    return $query->where(function ($q) use ($user) {
        // Always show public posts
        $q->where('privacy', 'public');

        // Show private posts if user follows org or is the org itself
        $q->orWhere(function ($q2) use ($user) {
            $q2->where('privacy', 'private')
               ->where(function ($q3) use ($user) {
                   $q3->where('org_id', $user->id) // org itself
                      ->orWhereHas('org.followers', function ($q4) use ($user) {
                          $q4->where('org_user.user_id', $user->id); // explicit pivot
                      });
               });
        });
    });
}







}