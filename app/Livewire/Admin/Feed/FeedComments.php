<?php

namespace App\Livewire\Admin\Feed;

use App\Models\Feed;
use App\Models\Comment;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Notifications\UniversalNotification;

class FeedComments extends Component
{
    public Feed $feed;       // The feed this comment belongs to
    public $comment = ''; // Input for new comment

    // Add a new comment
public function addComment($feedId)
{
    $commentText = $this->comments[$feedId] ?? '';

    $this->validate([
        "comments.$feedId" => 'required|string|max:500',
    ]);

    $comment = Comment::create([
        'feed_id' => $feedId,
        'user_id' => Auth::id(),
        'comment' => $commentText,
    ]);

    $user = Auth::user();



    $this->comments[$feedId] = '';
    $this->fetchFeeds();
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

