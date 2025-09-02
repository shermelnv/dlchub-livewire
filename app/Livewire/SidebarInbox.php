<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;

class SidebarInbox extends Component
{
    public $unreadCount = 0;

    public function mount()
    {
        $this->refreshUnreadCount();
    }

    #[On('notificationUpdated')]
    public function refreshUnreadCount()
    {
        $this->unreadCount = Auth::user()
            ->unreadNotifications()
            ->count();
    }

    public function render()
    {
        return view('livewire.sidebar-inbox');
    }
}
