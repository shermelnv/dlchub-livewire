<?php

namespace App\Livewire\User;

use Livewire\Component;
use App\Models\VotingRoom;

class Voting extends Component
{
    public $rooms = [];

     public function mount()
    {
        $this->loadRooms();
    }

    public function loadRooms()
    {
        $this->rooms = VotingRoom::latest()->get();
    }

    public function render()
    {
        return view('livewire.user.voting');
    }
}
