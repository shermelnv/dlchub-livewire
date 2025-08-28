<?php

namespace App\Livewire\Admin\Feed;


use Livewire\Component;
use App\Models\Reaction;
use Illuminate\Support\Facades\Auth;

class FeedReaction extends Component
{
    public $feed;

    public function toggleHeart()
    {
        $reaction = Reaction::where('feed_id', $this->feed->id)
            ->where('user_id', Auth::id())
            ->where('type', 'heart')
            ->first();

        if ($reaction) {
            $reaction->delete();
        } else {
            Reaction::create([
                'feed_id' => $this->feed->id,
                'user_id' => Auth::id(),
                'type' => 'heart',
            ]);
        }
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
