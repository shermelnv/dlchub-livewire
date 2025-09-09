<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;

class Inbox extends Component
{
    public $notifications = [];
    public $search = '';

    public function mount()
    {
        $this->fetchNotifications();
    }

        public function updatedSearch()
    {
        // whenever search changes, reload notifications
        $this->fetchNotifications();
    }

    public function fetchNotifications()
    {
        $this->notifications = auth()->user()
            ->notifications()
            ->latest()
            ->get();

         $query = auth()->user()->notifications()->latest();

        if ($this->search) {
            $search = $this->search;
            $query->where(function ($q) use ($search) {
                $q->where('data->message', 'like', "%{$search}%")
                  ->orWhere('data->type', 'like', "%{$search}%");
            });
        }

        $this->notifications = $query->get();

        $this->dispatch('notificationUpdated');
    }

    #[On('notificationReceived')]
    public function refreshNotifications($payload = null)
    {
        $this->fetchNotifications();
    }

    public function markAllAsRead()
    {
    auth()->user()->unreadNotifications->markAsRead();
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
