<?php

namespace App\Events;

use App\Models\Room;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

class RoomExpired implements ShouldBroadcastNow
{
    use InteractsWithSockets, SerializesModels;



    public function __construct()
    {
       
    }

    public function broadcastOn()
    {
        return new Channel('voting-room');
    }

    public function broadcastAs()
    {
        return 'room.expired';
    }
}
