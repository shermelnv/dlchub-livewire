<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;

class FollowOrg extends Component
{
    public $org;
    public string $followStatus = ''; // 'none', 'pending', 'accepted'

    public function mount(User $org)
    {
        $this->org = $org;

        $pivot = auth()->user()->followingOrgs()->where('org_id', $org->id)->first();
        $this->followStatus = $pivot?->pivot->status ?? 'none';
    }

    public function toggleFollow()
    {
        $user = auth()->user();

        if ($this->followStatus === 'accepted') {
            // Unfollow
            $user->followingOrgs()->detach($this->org->id);
            $this->followStatus = 'none';
        } elseif ($this->followStatus === 'pending') {
            // Cancel pending request
            $user->followingOrgs()->detach($this->org->id);
            $this->followStatus = 'none';
        } else {
            // Send follow request
            $user->followingOrgs()->attach($this->org->id, ['status' => 'pending']);
            $this->followStatus = 'pending';
        }
    }

    public function render()
    {
        return view('livewire.follow-org');
    }
}
