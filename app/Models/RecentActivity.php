<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RecentActivity extends Model
{
    protected $fillable = [
        'message',
        'type',
        'status',
    
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];
}
