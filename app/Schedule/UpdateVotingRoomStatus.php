<?php

namespace App\Schedule;

use Carbon\Carbon;
use App\Models\User;
use App\Models\VotingRoom;
use App\Events\RoomExpired;
use App\Events\DashboardStats;
use App\Models\RecentActivity;
use App\Events\RecentActivities;
use App\Notifications\UniversalNotification;
use Illuminate\Support\Facades\Notification;

class UpdateVotingRoomStatus
{
    public function __invoke(): void
    {
        $now = Carbon::now();
        $users = User::all();

        // Pending → Ongoing
        $startingRooms = VotingRoom::where('status', 'Pending')
            ->whereNotNull('start_time')
            ->where('start_time', '<=', $now)
            ->get();

        foreach ($startingRooms as $room) {
            $room->update(['status' => 'Ongoing']);

            RecentActivity::create([
                'user_id' => $room->creator_id ?? null,
                'message' => "Voting: \"{$room->title}\" is now active",
                'type'    => 'voting',
                'action'  => 'active',
            ]);

            Notification::send($users, new UniversalNotification(
                'Voting',
                "Voting \"{$room->title}\" is now ongoing!",
                $user->id,
            ));
        }

        // Ongoing → Closed
        $endingRooms = VotingRoom::where('status', 'Ongoing')
            ->whereNotNull('end_time')
            ->where('end_time', '<=', $now)
            ->get();

        foreach ($endingRooms as $room) {
            $room->update(['status' => 'Closed']);

            RecentActivity::create([
                'user_id' => $room->creator_id ?? null,
                'message' => "Voting: \"{$room->title}\" has ended",
                'type'    => 'voting',
                'action'  => 'ended',
            ]);

            Notification::send($users, new UniversalNotification(
                'Voting',
                "Voting \"{$room->title}\" has ended!",
                $user->id,
            ));

            event(new RoomExpired());
        }

        // Fire event once for all new activities
        event(new RecentActivities());

        // Update dashboard stats
        event(new DashboardStats([
            'activeVotings' => VotingRoom::where('status', 'Ongoing')->count(),
        ]));
    }
}
