<?php

namespace App\Livewire;

use App\Models\Org;
use App\Models\Feed;
use Livewire\Component;
use App\Models\Advertisement;

class OrgProfile extends Component
{
    public Org $org;
    public $feeds;
    public $ads;

    public function mount(Org $org)
    {
        $this->org = $org;

        // Feed type posts
        $this->feeds = Feed::where('org_id', $org->id)
            ->latest()
            ->get();

        // Advertisement type posts
        $this->ads = Advertisement::with('photos')
            ->where('org_id', $org->id)
            ->latest()
            ->get();
    }

    public function render()
    {
        return view('livewire.org-profile');
    }
}
