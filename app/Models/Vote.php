<?php

namespace App\Models;

use App\Models\User;
use App\Models\Position;
use App\Models\Candidate;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Vote extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'candidate_id', 'position_id', 'voting_rooms_id'];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function candidate() {
        return $this->belongsTo(Candidate::class);
    }

    public function position() {
        return $this->belongsTo(Position::class);
    }
}
