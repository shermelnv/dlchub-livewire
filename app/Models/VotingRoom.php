<?php

namespace App\Models;

use App\Models\User;
use App\Models\Vote;
use App\Models\Position;
use App\Models\Candidate;
use App\Models\VotingOption;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class VotingRoom extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'description', 'status', 'start_time', 'end_time', 'creator_id'];

 public function positions()
{
    return $this->hasMany(Position::class);
}


    public function votes()
    {
        return $this->hasManyThrough(Vote::class, Candidate::class);
    }
// VotingRoom.php
public function candidates()
{
    return $this->hasMany(\App\Models\Candidate::class);
}


}

