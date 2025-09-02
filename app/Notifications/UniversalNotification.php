<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\BroadcastMessage;

class UniversalNotification extends Notification implements ShouldBroadcast
{
    use Queueable;

    public string $type;
    public string $message;

    public function __construct(string $type, string $message)
    {
        $this->type = $type;
        $this->message = $message;
    }

    public function via(object $notifiable): array
    {
        // send both database + broadcast
        return ['database', 'broadcast'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'type' => $this->type,
            'message' => $this->message,
        ];
    }

    public function toBroadcast(object $notifiable): BroadcastMessage
    {
        return new BroadcastMessage([
            'type' => $this->type,
            'message' => $this->message,
        ]);
    }

    public function broadcastType(): string
    {
        return 'notification.received';
    }
}
