<?php

namespace App\Schedule;

use Carbon\Carbon;
use App\Models\GroupChat;
use App\Events\DashboardStats;
use App\Models\RecentActivity;
use App\Events\RecentActivities;
use App\Notifications\UniversalNotification;
use Illuminate\Support\Facades\Notification;

class ExpireGroupChats
{
    public function __invoke(): void
    {
        $now = Carbon::now();

        $groupsExpiringTomorrow = GroupChat::whereNotNull('expires_at')
            ->whereDate('expires_at', '=', $now->copy()->addDay()->toDateString())
            ->get();

        foreach ($groupsExpiringTomorrow as $group) {
            $users = $group->members; 

            Notification::send($otherUsers, new UniversalNotification(
                'Group Chat',
                "Group \"{$group->name}\" will expire in 1 day!",
                null // sender_id, you can use owner or system
            ));
        }


        $expiredGroups = GroupChat::whereNotNull('expires_at')
            ->where('expires_at', '<=', $now)
            ->get();

        foreach ($expiredGroups as $group) {
            $users = $group->members;
            Notification::send($users, new UniversalNotification(
                'Group Chat',
                "Group \"{$group->name}\" has expired!",
                null // sender_id, you can use owner or system
            ));

            // RecentActivity::create([
            //     'user_id' => $group->group_owner_id,
            //     'message' => "Group \"{$group->name}\" has expired",
            //     'type'    => 'chat',
            //     'action'  => 'expired',
            // ]);

            $group->delete();
        }

        event(new RecentActivities());
        event(new DashboardStats([
            'activeGroups' => GroupChat::count(),
        ]));
    }
}
