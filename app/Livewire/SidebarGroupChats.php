<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\GroupChat;
use Illuminate\Support\Str;
use Masmerise\Toaster\Toaster;
use App\Events\ChatJoinRequest;
use App\Models\GroupMemberRequest;
use Illuminate\Support\Facades\Auth;

class SidebarGroupChats extends Component
{
    public $groups = [];
public $newGroupName = '';
public $newGroupDescription = '';
public $groupCode = '';

    public function mount()
    {
        $this->groups = GroupChat::whereHas('members', function ($q) {
            $q->where('user_id', Auth::id());
        })->with('members')->get();
    }
public function createGroup()
    {
        $group = GroupChat::create([
            'group_owner_id' => Auth::id(),
            'name' => $this->newGroupName,
            'description' => $this->newGroupDescription,
            'group_code' => strtoupper(Str::random(6)), // Generates something like "A1B2C3"
        ]);

        $group->members()->attach(Auth::id());

        event(new DashboardStats([
            'groupChats' => \App\Models\GroupChat::count(),
            
        ]));
        $this->reset(['newGroupName', 'newGroupDescription']);
        $this->modal('create-group')->close();
        Toaster::success('Group Created Successfully!');
        return redirect()->route('user.chat', ['groupCode' => $group->group_code]);

    }

public function joinGroup()
{
    $group = GroupChat::where('group_code', $this->groupCode)->first();

    if (!$group) {
        Toaster::error('Group not found.');
        return;
    }

    $existingRequest = GroupMemberRequest::where('group_chat_id', $group->id)
        ->where('user_id', Auth::id())
        ->first();

    $isMember = $group->members()->where('user_id', Auth::id())->exists();

    if ($isMember) {
        Toaster::info('You are already a member of this group.');
    } elseif ($existingRequest && $existingRequest->status === 'pending') {
        Toaster::info('Join request already submitted.');
    } elseif ($existingRequest && $existingRequest->status === 'rejected') {
        Toaster::info('Your join request was rejected.');
    } else {
        GroupMemberRequest::updateOrCreate(
            ['group_chat_id' => $group->id, 'user_id' => Auth::id()],
            [
                'status' => 'pending',
            ]
        );

        event(new ChatJoinRequest($group->id));

        Toaster::success('Join request submitted!');
    }

    $this->reset('groupCode');
    $this->modal('create-group')->close();
}
    public function openGroup($groupCode)
    {
        $group = GroupChat::where('group_code', $groupCode)
            ->whereHas('members', fn ($q) => $q->where('user_id', Auth::id()))
            ->first();

        if ($group) {
            session()->flush();
            return redirect()->route('user.chat', ['groupCode' => $groupCode]);
        }
    }

    public function render()
    {
        return view('livewire.user.chat.partials.sidebar-group-chats');
    }
}
