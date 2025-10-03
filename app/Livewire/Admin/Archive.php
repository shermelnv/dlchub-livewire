<?php

namespace App\Livewire\Admin;

use App\Models\Archive as ArchiveModel;
use Livewire\Component;

class Archive extends Component
{
    public $archives;

    public function mount()
    {
        $this->loadArchived();
    }

    public function loadArchived()
    {
        $this->archives = ArchiveModel::where('role', 'user')->get();
    }

    public function render()
    {
        return view('livewire.admin.archive');
    }
}
