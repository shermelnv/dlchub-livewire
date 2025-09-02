<?php

namespace App\Schedule;

use Carbon\Carbon;
use App\Models\VotingRoom;
use App\Events\RoomExpired;
use App\Events\DashboardStats;
use App\Models\RecentActivity;
use App\Events\RecentActivities;

class UpdateVotingRoomStatus
{
    public function __invoke(): void
    {
        $now = Carbon::now();

        $startingRooms = VotingRoom::where('status', 'Pending')
        ->whereNotNull('start_time')
        ->where('start_time', '<=', $now)
        ->get();


        // Update their status to Ongoing
        foreach ($startingRooms as $room) {
            $room->update(['status' => 'Ongoing']);

            RecentActivity::create([
                'user_id'   => auth()->user()->id,
                'message'   => "{$room->title} is active",
                'type'      => 'voting',
                'action'    => 'active',
            ]);
            event(new RecentActivities());
        }

        
         $endingRooms = VotingRoom::where('status', 'Ongoing')
            ->whereNotNull('end_time')
            ->where('end_time', '<=', $now)
            ->get();

            foreach ($endingRooms as $room) {
            $room->update(['status' => 'Closed']);

        RecentActivity::create([
            'user_id'   => $user->id,
            'message'   => "{$room->title} has ended",
            'type'      => 'voting',
            'action'    => 'ended',
        ]);

            event(new RoomExpired());
            event(new RecentActivities());
        }


        VotingRoom::where('status', 'Ongoing')
            ->whereNotNull('end_time')
            ->where('end_time', '<=', $now)
            ->update(['status' => 'Closed']);

            event(new DashboardStats([
            'activeVotings' => VotingRoom::where('status', 'Ongoing')->count(),
        ]));

    }
}
