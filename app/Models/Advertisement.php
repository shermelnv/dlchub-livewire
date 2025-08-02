<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Advertisement extends Model
{
    protected $fillable = [
        'title',
        'description',
        'organization',
    ];

    public function photos()
{
    return $this->hasMany(AdvertisementPhoto::class);
}

}
