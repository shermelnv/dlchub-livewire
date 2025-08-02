<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\RecentActivity;

class DashboardRecentActivity extends Component
{
    public $activities = [];


    public function mount()
    {
        $this->activities = RecentActivity::latest()->limit(10)->get();
    }

public function addActivity($event)
{
    $message = is_array($event) ? $event['message'] : $event->message;

    $activity = RecentActivity::where('message', $message)->latest()->first();

    if ($activity) {
    $this->activities = collect([$activity])
    ->merge($this->activities)
    ->take(10);
    }
}



    public function render()
    {
        return view('livewire.dashboard-recent-activity');
    }
}
