<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Advertisement extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'description',
        'organization',
    ];

    public function photos()
{
    return $this->hasMany(AdvertisementPhoto::class);
}

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
