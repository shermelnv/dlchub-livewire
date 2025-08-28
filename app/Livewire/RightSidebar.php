<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;

class RightSidebar extends Component
{

    public $orgs;

    public function mount()
    {
        $this->orgs = User::where('role', 'org')->get();
    }

    public function render()
    {
        return view('livewire.right-sidebar');
    }
}
