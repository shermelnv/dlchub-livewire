<?php

namespace App\Livewire\Admin\Voting;

use App\Models\User;
use Livewire\Component;
use App\Models\VotingRoom;
use App\Events\DashboardStats;
use App\Models\RecentActivity;
use Livewire\Attributes\Title;
use Masmerise\Toaster\Toaster;
use App\Events\RecentActivities;
use Illuminate\Support\Facades\Auth;
use App\Notifications\UniversalNotification;
use App\Events\ManageVoting as BroadcastVotingRoom;

#[Title('Voting')]
class ManageVoting extends Component
{
    public $title = '';
    public $description = '';
    public $start_time = '';
    public $end_time = '';
    public $status = 'Pending';
    public $rooms = [];
    public $editingRoomId = null;
    public $selectedRoom = null;

    public function mount()
    {
        $this->loadRooms();
    }

    public function loadRooms()
    {
        $now = now();

       VotingRoom::where('status', 'Pending')
        ->whereNotNull('start_time')
        ->where('start_time', '<=', $now)
        ->update(['status' => 'Ongoing']);

        VotingRoom::where('status', 'Ongoing')
        ->whereNotNull('end_time')
        ->where('end_time', '<=', $now)
        ->update(['status' => 'Closed']);

    $this->rooms = VotingRoom::latest()->get();
    }

    public function createVoting()
    {
        $this->validate([
        'title' => 'required|string|max:255',
        'description' => 'nullable|string',
        'start_time' => 'required|date',
        'end_time' => 'required|date|after:start_time',
        
    ]);

    $status = now()->lt($this->start_time) ? 'Pending' : 'Ongoing';

    $votingRoom = VotingRoom::create([
        'title' => $this->title,
        'description' => $this->description,
        'start_time' => $this->start_time ?: null,
        'end_time' => $this->end_time ?: null,
        'status' => $status,
        'creator_id' => Auth::id(),
    ]);

    $users = User::where('id', '!=', Auth::id())->get();
foreach ($users as $u) {
    $u->notify(new UniversalNotification([
        'type'    => 'voting',
        'room_id' => $votingRoom->id,
        'title'   => $votingRoom->title,
        'user_id' => $votingRoom->auth()->user()->id,
        'message' => auth()->user()->name . " created a voting room titled \"{$votingRoom->title}\"",
    ]));
}


        RecentActivity::create([
            'user_id'   => auth()->user()->id,
            'message'   => "{$user->name} created a new voting: \"$votingRoom->title\" ",
            'type'      => 'voting',
            'action'    => 'created',
        ]);
        event(new RecentActivities());

        broadcast(new BroadcastVotingRoom($votingRoom));


        event(new DashboardStats([
            'students' => \App\Models\User::where('role', 'user')->count(),
            'groupChats' => \App\Models\GroupChat::count(),
            'activeVotings' => \App\Models\VotingRoom::where('status', 'Ongoing')->count(),
            'advertisements' => \App\Models\Advertisement::count(),
        ]));

        $this->resetFields();
       
        Toaster::success('Voting created successfully.');
        $this->modal('add-voting')->close();
        $this->loadRooms();
    }

    public function editRoom($roomId)
    {
        $room = VotingRoom::findOrFail($roomId);

        $this->editingRoomId = $room->id;
        $this->title = $room->title;
        $this->description = $room->description;
        $this->start_time = $room->start_time ? \Carbon\Carbon::parse($room->start_time)->format('Y-m-d\TH:i') : '';
        $this->end_time = $room->end_time ? \Carbon\Carbon::parse($room->end_time)->format('Y-m-d\TH:i') : '';
        $this->status = $room->status;

        $this->modal('edit-voting')->show();
    }

    public function updateVoting()
    {
        $this->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_time' => 'nullable|date',
            'end_time' => 'nullable|date|after:start_time',
            'status' => 'required|in:Pending,Ongoing,Closed',
        ]);

        $room = VotingRoom::findOrFail($this->editingRoomId);
        $room->update([
            'title' => $this->title,
            'description' => $this->description,
            'start_time' => $this->start_time ?: null,
            'end_time' => $this->end_time ?: null,
            'status' => $this->status,
        ]);

        $this->resetFields();
        Toaster::success('Voting room updated successfully.');
        $this->modal('edit-voting')->close();
        $this->loadRooms();
    }

    public function deleteRoom($roomId)
    {
        $room = VotingRoom::findOrFail($roomId);
        $roomTitle = $room->title;
        $room->delete();


        $this->loadRooms();
        Toaster::success('Voting room deleted successfully.');
    }

    protected function resetFields()
    {
        $this->reset([
            'title', 'description', 'start_time', 'end_time', 'status', 'editingRoomId'
        ]);
        $this->status = 'Pending';
    }

    public function render()
    {
        return view('livewire.admin.voting.manage-voting');
    }
}
