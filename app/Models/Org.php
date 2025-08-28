<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Org extends Model
{
    protected $fillable = 
    [
        'name',

    ];

    public function followers()
{
    return $this->belongsToMany(\App\Models\User::class, 'org_user')->withTimestamps();
}
// In Org.php
public function feeds()
{
    return $this->hasMany(Feed::class, 'org_id');
}
public function advertisements()
{
    return $this->hasMany(Advertisement::class, 'org_id');
}
}
