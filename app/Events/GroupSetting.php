<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class GroupUserApproved implements ShouldBroadcast
{
    public $group;
    public $approvedUser;
    public $admin;

    public function __construct($group, $approvedUser, $admin)
    {
        $this->group        = $group;
        $this->approvedUser = $approvedUser;
        $this->admin        = $admin;
    }

    public function broadcastOn()
    {
        // send to all current group members
        return new PrivateChannel("chat.{$this->group->id}");
    }

    public function broadcastAs()
    {
        return 'group.setting';
    }
}