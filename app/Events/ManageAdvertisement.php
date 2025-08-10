<?php

namespace App\Events;

use App\Models\User;
use App\Models\Advertisement;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

class ManageAdvertisement implements ShouldBroadcastNow
{
    use InteractsWithSockets, SerializesModels;

    public $ads;

    public function __construct(Advertisement $ads)
    {
        $this->ads = $ads;
    }

    public function broadcastOn(): Channel
    {
        return new Channel('manage-ads');
    }

    public function broadcastAs(): string
    {
        return 'ads.post';
    }
}