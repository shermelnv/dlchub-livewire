<?php

namespace App\Events;

use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

class GroupUserApproved implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets;

    public $groupId;

    public function __construct($groupId)
    {
        $this->groupId = $groupId;
    }

    public function broadcastOn()
    {
        return new PrivateChannel("chat.{$this->groupId}");
    }

    public function broadcastAs()
    {
        return 'group.user.approved';
    }
}
