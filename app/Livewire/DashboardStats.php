<?php

namespace App\Livewire;

use App\Models\Org;
use App\Models\User;
use Livewire\Component;
use App\Models\GroupChat;
use App\Models\VotingRoom;
use Livewire\Attributes\On;
use App\Models\Advertisement;

class DashboardStats extends Component
{
    public $studentCount = 0;
    public $groupChatCount = 0;
    public $activeVoteCount = 0;
    public $orgCount = 0;

    public function mount()
    {
        $this->fetchCounts();
    }

    public function fetchCounts()
    {
        $this->studentCount = User::where('role', 'user')->count();
        $this->groupChatCount = GroupChat::count();
        $this->activeVoteCount = VotingRoom::where('status', 'Ongoing')->count();
        $this->orgCount = Org::count();
    }

    public function updateCounts($stats)
    {
        $this->studentCount = $stats['students'] ?? $this->studentCount;
        $this->groupChatCount = $stats['groupChats'] ?? $this->groupChatCount;
        $this->activeVoteCount = $stats['activeVotings'] ?? $this->activeVoteCount;
        $this->orgCount = $stats['org'] ?? $this->orgCount;
    }

    public function render()
    {
        return view('livewire.dashboard-stats');
    }
}
