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
    return $query->when(!in_array($user->role, ['admin', 'superadmin']), function($q) use ($user) {
        $q->where('privacy', 'public') // Public ads are always visible
          ->orWhere(function($q2) use ($user) {
              $q2->where('privacy', 'private')
                 ->where(function($q3) use ($user) {
                     $q3->whereHas('org.followers', fn($q4) => $q4->where('users.id', $user->id)) // Followers
                        ->orWhere('org_id', $user->id); // OR the org itself who posted it
                 });
          });
    });
}



}
