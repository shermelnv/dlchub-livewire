<?php

namespace App\Events;

use App\Models\User;
use App\Models\VotingRoom as VotingRoomModel;
use App\Livewire\User\Voting;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

class ManageVoting implements ShouldBroadcastNow
{
    use InteractsWithSockets, SerializesModels;

    public $voting;

    public function __construct(VotingRoomModel $voting)
    {
        $this->voting = $voting;
    }

    public function broadcastOn(): Channel
    {
        return new Channel('manage-voting');
    }

    public function broadcastAs(): string
    {
        return 'voting.created';
    }
}