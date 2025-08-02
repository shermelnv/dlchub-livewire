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
    'organization',
    'type',
    'published_at',
    'photo_url'
];

protected $casts = [
    'published_at' => 'datetime',
];



    public function user()
    {
        return $this->belongsTo(User::class);
    }
}