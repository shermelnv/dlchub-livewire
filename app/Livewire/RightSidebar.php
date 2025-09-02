<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;
use App\Models\GroupChat;
use Illuminate\Support\Str;
use App\Events\DashboardStats;
use Masmerise\Toaster\Toaster;
use App\Models\GroupMemberRequest;
use Illuminate\Support\Facades\Auth;

class RightSidebar extends Component
{

    public $orgs;
public $newGroupName = '';
public $newGroupDescription = '';
public $groupCode = '';
    public function mount()
    {
        $this->orgs = User::where('role', 'org')->get();
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

    public function render()
    {
        return view('livewire.right-sidebar');
    }
}
