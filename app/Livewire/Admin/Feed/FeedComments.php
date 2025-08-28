<?php

namespace App\Livewire\Admin\Feed;

use App\Models\Feed;
use App\Models\Comment;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class FeedComments extends Component
{
    public Feed $feed;       // The feed this comment belongs to
    public $comment = ''; // Input for new comment

    // Add a new comment
    public function addComment()
    {
        $this->validate([
            'comment' => 'required|string|max:500',
        ]);

        Comment::create([
            'feed_id' => $this->feed->id,
            'user_id' => Auth::id(),
            'comment' => $this->comment,
        ]);

        $this->comment = '';
    }

    public function render()
    {
        $comments = Comment::where('feed_id', $this->feed->id)
            ->latest()
            ->with('user')
            ->get();

        return view('livewire.admin.feed.feed-comments', [
            'comments' => $comments,
        ]);
    }
}

