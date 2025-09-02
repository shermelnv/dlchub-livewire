<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

class RecentActivities implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    // public string $message;

    public function __construct()
    {
        // $this->message = $message;
    }

    public function broadcastOn(): Channel
    {
        return new Channel('dashboard.activity');
    }

    public function broadcastAs(): string
    {
        return 'activity.created';
    }
}
