<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

class UserRegistered implements ShouldBroadcastNow
{
    use InteractsWithSockets, SerializesModels;

  
    public function __construct()
    {
        
    }

    public function broadcastOn(): Channel
    {
        return new Channel('manage-user');
    }

    public function broadcastAs(): string
    {
        return 'user.registered';
    }
}