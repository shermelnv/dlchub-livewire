<?php

namespace App\Events;

use App\Models\Feed;
use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

class ManageFeed implements ShouldBroadcastNow
{
    use InteractsWithSockets, SerializesModels;

   public $feed;

    public function __construct(Feed $feed)
    {
        $this->feed = $feed;
    }

    public function broadcastOn(): Channel
    {
        return new Channel('manage-feeds');
    }

    public function broadcastAs(): string
    {
        return 'feed.post';
    }
}