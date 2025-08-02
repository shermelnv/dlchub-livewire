<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdvertisementPhoto extends Model
{
    protected $fillable = [
        'advertisement_id',
        'photo_path',
    ];

    public function advertisement()
{
    return $this->belongsTo(Advertisement::class);
}

}
