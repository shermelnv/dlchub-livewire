<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

class VotedCandidate implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    
    public $roomId;

    public function __construct($roomId)
    {
        
        $this->roomId = $roomId;
    }

    public function broadcastOn()
    {
        return new PrivateChannel("voting-room.{$this->roomId}");
    }

    public function broadcastAs()
    {
        return 'voted.candidate';
    }
}

