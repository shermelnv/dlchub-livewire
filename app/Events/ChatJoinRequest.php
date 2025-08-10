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

class ChatJoinRequest implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $groupId;

    public function __construct($groupId)
    {
        $this->groupId = $groupId;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('group.' . $this->groupId);
    }

    public function broadcastAs()
    {
        return 'group.join.request';
    }
    public function broadcastWith()
{
    return [
        'groupId' => $this->groupId
    ];
}

}
