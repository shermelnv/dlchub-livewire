<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Advertisement extends Model
{
    protected $fillable = [
        'title',
        'category',
        'description',
        'organization',
        'location',
        'event_date',
        'time',
        'deadline',
        'tags',
    ];
}
