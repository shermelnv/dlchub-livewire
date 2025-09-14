<?php

namespace App\Livewire;

use App\Models\Feed;
use App\Models\User;
use App\Models\Comment;
use Livewire\Component;
use App\Models\Reaction;
use App\Models\Advertisement;
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
            'orgInfo.about'    => 'nullable|string|max:5000',
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

    public function render()
    {
        return view('livewire.org-profile');
    }
}
