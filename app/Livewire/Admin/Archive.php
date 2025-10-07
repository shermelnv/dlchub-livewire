<?php

namespace App\Livewire\Admin;


use Livewire\Component;
use Masmerise\Toaster\Toaster;
use App\Models\Archive as ArchiveModel;

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

    public function emptyArchive()
    {
    ArchiveModel::truncate();
    $this->modal('empty-archive')->close();
    Toaster::success('Archive cleared Successfully!');
    }


    public function render()
    {
        return view('livewire.admin.archive');
    }
}
