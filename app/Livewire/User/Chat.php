<?php

namespace App\Livewire\User;

use Livewire\Component;
use App\Models\GroupChat;
use App\Models\ChatMessage;
use Illuminate\Support\Str;
use Livewire\Attributes\On;
use Livewire\WithFileUploads;
use App\Events\DashboardStats;
use Livewire\Attributes\Title;
use Masmerise\Toaster\Toaster;
use App\Events\ChatJoinRequest;
use App\Events\GroupMessageSent;

use App\Events\GroupUserApproved;
use App\Models\GroupMemberRequest;
use Illuminate\Support\Facades\Auth;
use App\Events\GroupChat as GroupChatEvent;
use App\Notifications\UniversalNotification;
use Illuminate\Support\Facades\Notification;
use Livewire\Features\SupportEvents\Browser;
use App\Models\GroupMemberRequest as UserRequest;

#[Title('Chat')]
class Chat extends Component
{
    use WithFileUploads;
    public $group_profile; 

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
    ->get();

    // $this->dispatch('scroll-to-bottom');
}



    


public function approveRequest($requestId)
{
    $request = GroupMemberRequest::findOrFail($requestId);

    // Only allow if current user is group admin/owner (you may want to add this check)
    if (!$this->selectedGroup || $this->selectedGroup->id !== $request->group_chat_id) return;

    if ($request->user->groupChats()->count() >= 4) {
    Toaster::error('This user have reached the group limit.');
    return;
    }


    $request->update([
        'status' => 'accepted',
    ]);

    ChatMessage::create([
        'group_chat_id' => $this->selectedGroup->id,
        'user_id' => null, // system message
        'message' => ($request->user->name ?? 'unknown') . ' has been approved by the admin',
    ]);

    
    // Broadcast to other users via Reverb/Pusher
    broadcast(new GroupUserApproved($this->selectedGroup->id));


    $this->selectedGroup->members()->attach($request->user_id);

    Toaster::success('Request approved');
}
#[On('user-approved')]
public function addSystemMessage()
{

    
    // dd('received');
    $this->loadMessages();
}






public function rejectRequest($requestId)
{
    $request = GroupMemberRequest::findOrFail($requestId);

    if (!$this->selectedGroup || $this->selectedGroup->id !== $request->group_chat_id) return;

    $request->update([
        'status' => 'rejected',

    ]);

    Toaster::error('Request Rejected');
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
        $this->modal('group-settings-large-devices')->close();
        return redirect()->route('user.chat');
        Toaster::success('You have left the group successfully.');
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
       
            Notification::send($id, new UniversalNotification(
                'Group Chat',
                auth()->user()->name ." removed you from group \"{$group->name}\"!",
                auth()->id() 
            ));
        

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
public function handleRealtimeMessage()
{
    $this->loadMessages();
    $this->dispatch('message-received');
}


#[On('newJoinRequest')]
public function newJoinRequest()
{
    if (!$this->selectedGroup) {
        return;
    }


    Toaster::info('New join request');
    $this->selectedGroup->refresh();
}

public $editGroupId = null;
public $editGroup = [];


public function editGroupInfo($groupId)
{
    $this->editGroupId = $groupId;

    $group = GroupChat::find($groupId);
    if (!$group) return;

    // Populate the Livewire property
    $this->editGroup = $group->toArray();

    // Open the modal
    $this->modal('group-settings-large-devices')->close();
    $this->modal('edit-group-info')->show();
}
public function updateGroupInfo()
{
    if (!$this->editGroupId) return;

    $group = GroupChat::find($this->editGroupId);
    if (!$group) return;

    $group->name = $this->editGroup['name'];

    if ($this->group_profile) {
        $path = $this->group_profile->store('group_profile', 'public');
        $group->group_profile = $path; // <— now matches DB + Blade
    }


    $group->save();

    $this->modal('edit-group-info')->close();
    $this->modal('group-settings')->close();
    // Refresh selected group
    $this->selectedGroup = $group;

    Toaster::success('Group info updated');
}

public function createGroup()
    {
    if (auth()->user()->groupChats()->count() >= 4) {
    Toaster::error('You have reached the group limit.');
    return;
    }

        $group = GroupChat::create([
            'group_owner_id' => Auth::id(),
            'name' => $this->newGroupName,
            'description' => $this->newGroupDescription,
            'group_code' => strtoupper(Str::random(6)),
            'expires_at' => now()->addDays(7), 
        ]);
        
        $group->members()->attach(Auth::id());

        event(new DashboardStats([
            'groupChats' => \App\Models\GroupChat::count(),
            
        ]));
        $this->reset(['newGroupName', 'newGroupDescription']);

        $this->groups = GroupChat::whereHas('members', function ($q) {
        $q->where('user_id', Auth::id());
        })->with('members')->get();

        $this->modal('create-group')->close();
        Toaster::success('Group Created Successfully!');
        // return redirect()->route('user.chat', ['groupCode' => $group->group_code]);

    }

public function joinGroup()
{
    $group = GroupChat::where('group_code', $this->groupCode)->first();

    if (!$group) {
        Toaster::error('Group not found.');
        return;
    }
    if (auth()->user()->groupChats()->count() >= 4) {
    $this->reset(['groupCode']);
    Toaster::error('You have reached the group limit.');
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

        $groupOwner = User::find($group->group_owner_id); // fetch the User model
        if ($groupOwner) {
            Notification::send($groupOwner, new UniversalNotification(
                'Group Chat',
                auth()->user()->name . " requested to join your group \"{$group->name}\"!",
                auth()->id() // or null for system
            ));
        }


        Toaster::success('Join request submitted!');
    }

    $this->reset('groupCode');
    $this->modal('create-group-desktop')->close();
}

    public function render()
    {
        return view('livewire.user.chat.chat');
    }
}
