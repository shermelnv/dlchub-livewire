<?php

namespace App\Livewire;

use App\Models\Org;
use Livewire\Component;

class OrgProfile extends Component
{
    public Org $org;
    

    public function mount(Org $org)
    {
        $this->org = $org;
    }

    public function render()
    {
        return view('livewire.org-profile');
    }
}
