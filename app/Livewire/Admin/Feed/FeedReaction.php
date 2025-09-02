<?php

namespace App\Livewire\Admin\Feed;


use App\Models\User;
use Livewire\Component;
use App\Models\Reaction;
use Illuminate\Support\Facades\Auth;
use App\Notifications\UniversalNotification;

class FeedReaction extends Component
{
    public $feed;

public function toggleHeart()
{
    $reaction = Reaction::where('feed_id', $this->feed->id)
        ->where('user_id', Auth::id())
        ->where('type', 'heart')
        ->first();

    $user = Auth::user();





 


}


    public function render()
    {
        $count = Reaction::where('feed_id', $this->feed->id)->count();
        $userReacted = Reaction::where('feed_id', $this->feed->id)
            ->where('user_id', Auth::id())
            ->exists();

        return view('livewire.admin.feed.feed-reaction', [
            'count' => $count,
            'userReacted' => $userReacted,
        ]);
    }
}
