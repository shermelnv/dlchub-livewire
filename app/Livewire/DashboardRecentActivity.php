<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\RecentActivity;

class DashboardRecentActivity extends Component
{
    public $activities;
    public $search = '';
    public $filterType = '';

    #[On('activity-created')]
    public function refreshActivities()
    {
        $this->activities = $this->queryActivities();
    }

    public function mount()
    {
        $this->activities = $this->queryActivities();
    }

    public function updatedSearch($value)
    {
        // Reset if input cleared
        if (trim($value) === '') {
            $this->reset('search');
        }

        $this->activities = $this->queryActivities();
    }

    public function updatedFilterType($value)
    {
        // If filter set to "All", reset it
        if ($value === '') {
            $this->reset('filterType');
        }

        $this->activities = $this->queryActivities();
    }

    private function queryActivities()
    {
        return RecentActivity::query()
            ->when($this->search !== '', function ($query) {
                $query->where(function ($q) {
                    $q->where('type', 'like', "%{$this->search}%")
                      ->orWhere('message', 'like', "%{$this->search}%") // added message field
                      ->orWhere('action', 'like', "%{$this->search}%")
                      ->orWhere('created_at', 'like', "%{$this->search}%");
                });
            })
            ->when($this->filterType !== '', function ($query) {
                $query->where('type', $this->filterType);
            })
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function render()
    {
        return view('livewire.dashboard-recent-activity');
    }
}
