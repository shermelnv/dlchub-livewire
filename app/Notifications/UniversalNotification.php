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
    public ?int $user_id; // add creator ID

    // Include user_id as optional
    public function __construct(string $type, string $message, ?int $user_id = null)
    {
        $this->type = $type;
        $this->message = $message;
        $this->user_id = $user_id;
    }

    public function via(object $notifiable): array
    {
        return ['database', 'broadcast'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'type' => $this->type,
            'message' => $this->message,
            'user_id' => $this->user_id, // store creator
        ];
    }

    public function toBroadcast(object $notifiable): BroadcastMessage
    {
        return new BroadcastMessage([
            'type' => $this->type,
            'message' => $this->message,
            'user_id' => $this->user_id, // include in broadcast
        ]);
    }

    public function broadcastType(): string
    {
        return 'notification.received';
    }
}
