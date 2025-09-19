<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Advertisement extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'description',
        'org_id',   // ✅ renamed
        'privacy',
    ];

    public function photos()
    {
        return $this->hasMany(AdvertisementPhoto::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // ✅ Add relation to Org
    public function org()
    {
        return $this->belongsTo(Org::class, 'org_id');
    }


// Advertisement.php
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
