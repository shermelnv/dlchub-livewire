<?php

namespace App\Livewire;

use App\Models\Feed;
use App\Models\User;
use Livewire\Component;
use App\Models\VotingRoom;
use App\Models\Advertisement;

class GuestLandingPage extends Component
{
    public $userCount;
    public $ongoingVotingRooms;
    public $orgCount;
    public $latestFeeds;
    public $latestAds;

    public $organizations = [];
    public $allOrganizations = [];

    public function mount()
    {
        $this->userCount = User::where('role', 'user')->count();
        $this->ongoingVotingRooms = VotingRoom::where('status', 'Ongoing')->count();
        $this->orgCount = User::where('role', 'org')->count();

        $this->organizations = User::where('role', 'org')->take(6)->get();
        $this->allOrganizations = User::where('role', 'org')->get();

        $this->latestFeeds = Feed::latest()->take(3)->get();
        $this->latestAds = Advertisement::with('photos')->latest()->take(4)->get();
    }


    public function render()
    {
         return view('livewire.guest-landing-page')
            ->layout('components.layouts.guest');
    }
}


