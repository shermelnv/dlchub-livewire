<?php

namespace App\Livewire\User;

use Livewire\Component;
use App\Models\VotingRoom;
use Livewire\Attributes\On;

class Voting extends Component
{
    public $rooms = [];

     public function mount()
    {
        $this->loadRooms();
    }

    #[On('newVotingRoom')]
    public function newVotingRoom()
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
