<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\VotingRoom;

class ActiveVoting extends Component
{
    public $activeVotings;

    public function mount()
    {
        $this->activeVotings = VotingRoom::where('status', 'ongoing')->get();        
    }

    public function render()
    {
        return view('livewire.active-voting');
    }
}
