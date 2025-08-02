<?php

use App\Models\User;
use Illuminate\Support\Facades\Broadcast;



Broadcast::channel('users.{id}', function (User $user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('chat.{groupId}', function ($user, $groupId) {
    return $user->groupChats->contains('id', $groupId);
});



// #[On('group-message-received')]
// public function handleRealtimeMessage($e)
// {
//     $this->messages[] = [
//         'user_id' => $e['user_id'],
//         'message' => $e['message'],
//         'created_at' => now(),
//         'user' => ['name' => $e['user_name']],
//     ];
// }
