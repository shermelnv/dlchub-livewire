<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;

class Inbox extends Component
{
    public $notifications = [];

    public function mount()
    {
        $this->fetchNotifications();
    }

    public function fetchNotifications()
    {
        $this->notifications = auth()->user()
            ->unreadNotifications()
            ->latest()
            ->get();

        $this->dispatch('notificationUpdated');
    }

    #[On('notificationReceived')]
    public function refreshNotifications($payload = null)
    {
        $this->fetchNotifications();
    }

    public function markAsRead($id)
    {
        $notif = auth()->user()->notifications()->find($id);

        if ($notif) {
            $notif->markAsRead();
        }

        $this->fetchNotifications();
    }

    public function render()
    {
        return view('livewire.inbox');
    }
}
