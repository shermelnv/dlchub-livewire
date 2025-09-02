<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RecentActivity extends Model
{
    protected $fillable = [
        'message',
        'type',
        'status',
        'user_id',
        'action',     
    ];


    protected $casts = [
        'message'   => 'array',
        'created_at'=> 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
