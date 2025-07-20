<?php

namespace App\Models;

use App\Models\Vote;
use App\Models\Position;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Candidate extends Model
{
    use HasFactory;

    protected $fillable = ['position_id', 'name', 'short_name', 'bio', 'photo_url', 'color'];

    public function position()
    {
        return $this->belongsTo(Position::class);
    }

public function votes()
{
    return $this->hasMany(Vote::class);
}


    public function getVoteCountAttribute()
    {
        return $this->votes()->count();
    }
}
