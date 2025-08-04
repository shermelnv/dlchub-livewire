<?php

namespace App\Livewire\User;

use Livewire\Component;
use App\Models\GroupChat;
use App\Models\ChatMessage;
use Illuminate\Support\Str;
use Livewire\Attributes\On;
use App\Events\DashboardStats;
use Masmerise\Toaster\Toaster;
use App\Events\GroupMessageSent;
use App\Models\GroupMemberRequest;
use Illuminate\Support\Facades\Auth;
use App\Events\GroupChat as GroupChatEvent;
use Livewire\Features\SupportEvents\Browser;

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

    // Handle selected group logic safely
    if ($groupCode) {
        $this->selectedGroup = GroupChat::where('group_code', $groupCode)
            ->whereHas('members', fn ($q) => $q->where('user_id', Auth::id()))
            ->with(['members', 'requests.user']) // include requests if needed
            ->first();
    } elseif (session('selected_group_code')) {
        $this->selectedGroup = GroupChat::where('group_code', session('selected_group_code'))
            ->whereHas('members', fn ($q) => $q->where('user_id', Auth::id()))
            ->with(['members', 'requests.user'])
            ->first();
    }

    // Load messages
    if ($this->selectedGroup) {
        $this->messages = $this->selectedGroup->messages()->with('user')->get();
    } else {
        $this->messages = [];
    }
}


public function getPendingRequestsProperty()
{
    return $this->selectedGroup
        ? $this->selectedGroup->requests()->where('status', 'pending')->with('user')->get()
        : collect(); // return empty collection if group is null
}


public function loadMessages()
{
    if (!$this->selectedGroup) {
        return;
    }

    $this->messages = ChatMessage::with('user')
    ->where('group_chat_id', $this->selectedGroup->id)
    ->orderBy('created_at', 'asc')
    ->get()
    ->map(function ($message) {
        return [
            'id' => $message->id,
            'user_id' => $message->user_id,
            'message' => $message->message,
            'created_at' => $message->created_at, // keep as Carbon
            'created_at_human' => $message->created_at->diffForHumans(), // optional for tooltip
            'user' => ['name' => $message->user->name],
        ];
    })
    ->toArray();

    // $this->dispatch('scroll-to-bottom');
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

    $existingRequest = GroupMemberRequest::where('group_id', $group->id)
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
            ['group_id' => $group->id, 'user_id' => Auth::id()],
            [
                'status' => 'pending',
                'message' => null,
                'responded_at' => null,
            ]
        );

        Toaster::success('Join request submitted!');
    }

    $this->reset('groupCode');
    $this->modal('join-group')->close();
}


public function approveRequest($requestId)
{
    $request = GroupMemberRequest::findOrFail($requestId);

    // Only allow if current user is group admin/owner (you may want to add this check)
    if (!$this->selectedGroup || $this->selectedGroup->id !== $request->group_id) return;

    $request->update([
        'status' => 'accepted',
        'responded_at' => now(),
    ]);

    $this->selectedGroup->members()->attach($request->user_id);
    $this->dispatch('toast', title: 'Request approved');
}

public function rejectRequest($requestId)
{
    $request = GroupMemberRequest::findOrFail($requestId);

    if (!$this->selectedGroup || $this->selectedGroup->id !== $request->group_id) return;

    $request->update([
        'status' => 'rejected',
        'responded_at' => now(),
    ]);

    $this->dispatch('toast', title: 'Request rejected');
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
public function removeMember($id)
{
    if (!$this->selectedGroup) return;

    $this->selectedGroup->members()->detach($id);
    $this->selectedGroup->refresh();
    $this->messages = $this->selectedGroup->messages()->with('user')->get(); // optional refresh
    Toaster::success('Member Removed');
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
        
        broadcast(new GroupMessageSent($message));
        $this->dispatch('scroll-to-bottom');
    }

#[On('message-received')]
public function handleRealtimeMessage($message)
{
    $this->loadMessages();
}



    public function render()
    {
        return view('livewire.user.chat.chat');
    }
}
