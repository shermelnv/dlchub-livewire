<?php

namespace App\Livewire;

use App\Models\Feed;
use App\Models\User;
use App\Models\Comment;
use Livewire\Component;
use App\Models\Reaction;
use App\Models\Advertisement;
use Masmerise\Toaster\Toaster;
use Illuminate\Support\Facades\Auth;
use App\Notifications\UniversalNotification;
use Illuminate\Support\Facades\Notification;

class OrgProfile extends Component
{

    public $org;      // this will hold the organization user
    public $feeds;    // optional: the org's feeds
    public $ads;      // optional: the org's advertisements



    public $comments = [];
    
    public function mount($orgId)
    {

       $this->org = User::with('organizationInfo')
                     ->where('id', $orgId)
                     ->where('role', 'org')
                     ->firstOrFail();

        // Feed type posts
        $this->feeds = Feed::with(['user', 'comments.user', 'reactions'])
            ->where('org_id', $orgId)
            ->visibleToUser(auth()->user())
            ->latest()
            ->get();

        // Advertisement type posts
        $this->ads = Advertisement::with('photos')
            ->where('org_id', $orgId)
            ->visibleToUser(auth()->user())
            ->latest()
            ->get();

                // prefill orgInfo fields from DB
        $this->orgInfo = [
            'about'    => $this->org->organizationInfo->about    ?? '',
            'email'    => $this->org->organizationInfo->email    ?? '',
            'facebook' => $this->org->organizationInfo->facebook ?? '',
        ];

    }

    public $orgInfo = [
        'about'    => '',
        'email'    => '',
        'facebook' => '',
    ];



    public function saveAbout()
    {
        $this->validate([
            'orgInfo.about'    => 'nullable|string|max:255',
            'orgInfo.email'    => 'nullable|email',
            'orgInfo.facebook' => 'nullable|url',
        ]);
        
        $this->org->organizationInfo()->updateOrCreate(
            ['user_id' => $this->org->id],
            $this->orgInfo
        );

        $this->org->load('organizationInfo');

        // close modal after saving
       $this->modal('edit-about')->close();
    }


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
        $feed = Feed::find($feedId);
        $feedOwner = User::find($feed->user_id);

        
        if($feedOwner !== $user){

        Notification::send($feedOwner, new UniversalNotification(
                    'feed',
                    "$user->name commented on your post \"$feed->title\"",
    $user->id,
                ));
        }

        $this->comments[$feedId] = '';
        // $this->fetchFeeds();
    }

    // ───── Reactions ─────
    public function toggleHeart(Feed $feed)
    {
        $reaction = Reaction::where('feed_id', $feed->id)
            ->where('user_id', Auth::id())
            ->where('type', 'heart')
            ->first();

        $user = Auth::user();
        $feedOwner = User::find($feed->user_id);

        if ($reaction) {
            $reaction->delete();
            $action = 'removed a heart on your post';
        } else {
            Reaction::create([
                'feed_id' => $feed->id,
                'user_id' => $user->id,
                'type' => 'heart',
            ]);
            $action = 'reacted ❤️ on your post ';
        }

            if($feedOwner !== $user){

        Notification::send($feedOwner, new UniversalNotification(
                    'feed',
                    "$user->name $action \"$feed->title\" ",
    $user->id,
                ));
        }

        // $this->fetchFeeds();
    }

         public ?int $commentToEdit = null;
        public $showComment = [
            'comment' => '', // 👈 match the textarea's wire:model.defer
        ];
    public function editComment($commentId)
    {

        $comment = Comment::findOrFail($commentId);

        // Optional: check if user owns the comment
        // if ($comment->user_id !== Auth::id()) {
        //     abort(403);
        // }

            $this->commentToEdit = $comment->id;
            $this->showComment['comment'] = $comment->comment;

            $this->modal('edit-comment')->show();
    }

        // Save edited comment
        public function updateComment()
        {
            $this->validate([
                'showComment.comment' => 'required|string|max:500',
            ]);

            $comment = Comment::findOrFail($this->commentToEdit);

            // if ($comment->user_id !== Auth::id()) {
            //     abort(403);
            // }

            $comment->update([
                'comment' => $this->showComment['comment'],
            ]);

            $this->reset(['commentToEdit', 'showComment']);
            $this->modal('edit-comment')->close();
            // $this->fetchFeeds();

            Toaster::success('Comment updated!');
        }

    public ?int $commentToDelete = null;
    public function confirmDeleteComment( $commentId)
    {
        $this->commentToDelete = $commentId;
        $this->modal('delete-comment')->show();
    }

    // Delete comment
    public function deleteComment()
    {
        if ($this->commentToDelete) {
            $comment = Comment::findOrFail($this->commentToDelete);

            // Optional ownership check:
            // if ($comment->user_id !== Auth::id()) abort(403);

            $comment->delete();

            $this->reset('commentToDelete');
            $this->modal('delete-comment')->close();
            // $this->fetchFeeds();

            Toaster::success('Comment deleted.');
        }
    }



    public function render()
    {
        return view('livewire.org-profile');
    }
}
