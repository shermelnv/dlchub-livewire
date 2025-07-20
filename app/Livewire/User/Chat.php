<?php

namespace App\Livewire\User;

use Livewire\Component;
use App\Models\GroupChat;
use App\Models\ChatMessage;
use Illuminate\Support\Str;
use Masmerise\Toaster\Toaster;
use Illuminate\Support\Facades\Auth;

class Chat extends Component
{
    public $groups = [];
    public $selectedGroup = null;
    public $messages = [];
    public $messageInput = '';
    public $newGroupName = '';
    public $newGroupDescription = '';
    public $groupCode = '';

public function mount($groupCode = null)
{
    // Only show groups the user is a member of
    $this->groups = GroupChat::whereHas('members', function ($q) {
        $q->where('user_id', Auth::id());
    })->with('members')->get();

    if ($groupCode) {
        $this->selectedGroup = GroupChat::where('group_code', $groupCode)
            ->whereHas('members', fn ($q) => $q->where('user_id', Auth::id()))
            ->with('members')->first();
    } elseif (session('selected_group_code')) {
        $this->selectedGroup = GroupChat::where('group_code', session('selected_group_code'))
            ->whereHas('members', fn ($q) => $q->where('user_id', Auth::id()))
            ->with('members')->first();
    }
}


    public function createGroup()
    {
        $group = GroupChat::create([
            'name' => $this->newGroupName,
            'description' => $this->newGroupDescription,
             'group_code' => strtoupper(Str::random(6)), // Generates something like "A1B2C3"
        ]);

        $group->members()->attach(Auth::id());
        $this->reset(['newGroupName', 'newGroupDescription']);
        $this->modal('create-group')->close();
        Toaster::success('Group Created Successfully!');
        return redirect()->route('user.chat', ['groupCode' => $group->group_code]);
    }

    public function joinGroup()
    {
        $group = GroupChat::where('group_code', $this->groupCode)->first();


        if ($group && !$group->members->contains(Auth::id())) {
            $group->members()->attach(Auth::id());
        }

        $this->reset('groupCode');
        $this->modal('join-group')->close();
        Toaster::success('Joined Successfully!');
        return redirect()->route('user.chat', ['groupCode' => $group->group_code]);
    }

    public function leaveGroup($groupId)
    {
        $group = GroupChat::find($groupId);

        if ($group) {
            $group->members()->detach(Auth::id());

            if ($this->selectedGroup && $this->selectedGroup->id == $group->id) {
                $this->selectedGroup = null;
                $this->messages = [];
            }

            $this->mount();
        }
    }

public function openGroup($groupCode)
{
    $group = GroupChat::where('group_code', $groupCode)
        ->whereHas('members', fn ($q) => $q->where('user_id', Auth::id()))
        ->with('members')->first();

    if ($group) {
        $this->selectedGroup = $group;
        session(['selected_group_code' => $groupCode]);
        return redirect()->route('user.chat', ['groupCode' => $groupCode]);
    }
}


    public function sendMessage()
    {
        if (!$this->selectedGroup || trim($this->messageInput) === '') {
            return;
        }

        $message = $this->selectedGroup->messages()->create([
            'user_id' => Auth::id(),
            'message' => $this->messageInput,
        ]);

        $this->messages[] = $message->load('user');
        $this->messageInput = '';
    }

    public function render()
    {
        return view('livewire.user.chat');
    }
}
