<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DashboardStats implements ShouldBroadcastNow
{
    use Dispatchable, SerializesModels;

    public array $stats;

    public function __construct(array $stats)
    {
        $this->stats = $stats;
    }

    public function broadcastOn()
    {
        return new Channel('dashboard.stats');
    }

    public function broadcastAs()
    {
        return 'stats.updated';
    }
}
