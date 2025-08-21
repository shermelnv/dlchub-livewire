<?php

namespace App\Livewire\Admin\Chat;

use Livewire\Component;
use App\Models\GroupChat;
use Livewire\Attributes\Title;
#[Title('Chat')]
class ManageChat extends Component
{

    public $selectedRoom = null;
    public $rooms;

    public function mount()
    {
        $this->rooms = GroupChat::all();
        
    }

    public function viewRoom($roomId)
    {
        
        $this->selectedRoom = GroupChat::with('owner', 'members')->find($roomId);
        $this->modal('roomDetails')->show();
    }

    public function render()
    {
        return view('livewire.admin.chat.manage-chat');
    }
}
