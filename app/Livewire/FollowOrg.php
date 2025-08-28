<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Org;
use Illuminate\Support\Facades\Auth;

class FollowOrg extends Component
{
    public $org;
    public $isFollowing;

    public function mount(Org $org)
    {
        $this->org = $org;
        $this->isFollowing = Auth::user()->followingOrgs->contains($org->id);
    }

    public function toggleFollow()
    {
        $user = Auth::user();

        if ($this->isFollowing) {
            $user->followingOrgs()->detach($this->org->id);
            $this->isFollowing = false;
        } else {
            $user->followingOrgs()->attach($this->org->id);
            $this->isFollowing = true;
        }
    }

    public function render()
    {
        return view('livewire.follow-org');
    }
}
