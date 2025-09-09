<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class OrgFollowRequests extends Component
{
    public $org;

    // protected $listeners = [
    //     'followRequestUpdated' => '$refresh',
    // ];

    public function mount()
    {
        // Automatically get the currently logged-in org user
        $this->org = Auth::user();

        // Optional: ensure only orgs can access this component
        if (!$this->org->isOrg()) {
            abort(403, 'Unauthorized');
        }
    }

  public function getPendingRequestsProperty()
{
    return $this->org->pendingFollowers()->get();
}

public function accept($userId)
{
    $this->org->pendingFollowers()->updateExistingPivot($userId, ['status' => 'accepted']);
}

public function reject($userId)
{
    $this->org->pendingFollowers()->detach($userId);
}


    public function render()
    {
        return view('livewire.org-follow-requests', [
            'requests' => $this->pendingRequests,
        ]);
    }
}
