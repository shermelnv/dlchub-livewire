<?php

namespace App\Models;

use App\Models\Candidate;
use App\Models\VotingRoom;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Position extends Model
{
    use HasFactory;

    protected $fillable = ['voting_room_id', 'name', 'order_index'];

    public function votingRoom()
    {
        return $this->belongsTo(VotingRoom::class);
    }

public function candidates()
{
    return $this->hasMany(Candidate::class);
}
public function votes()
{
    return $this->hasMany(Vote::class);
}

}
