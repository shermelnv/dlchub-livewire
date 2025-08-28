<?php

namespace App\Livewire\Admin\Feed;

use App\Models\Org;
use App\Models\Type;
use App\Models\User;
use App\Models\Comment;
use Livewire\Component;
use App\Models\Reaction;
use Livewire\Attributes\On;
use Livewire\WithFileUploads;
use App\Models\RecentActivity;
use Livewire\Attributes\Title;
use Masmerise\Toaster\Toaster;
use App\Events\RecentActivities;
use App\Models\Feed as FeedModel;
use Illuminate\Support\Facades\Auth;
use App\Events\ManageFeed as BroadcastFeed;

#[Title('Feed')]
class ManageFeed extends Component
{
    use WithFileUploads;

    public $dateFrom, $dateTo;
    public $feeds = [];
    public $showPost = [
        'title' => '',
        'content' => '',
        'organization' => '',
        'type' => '',
    ];
    public $orgs, $types;
    public $organizationFilter = null;
    public $typeFilter = null;
    public $title, $content, $organization, $type;
    public $photo;
    public $org_id;

public $privacy;

    public ?int $postToEdit = null;
    public string $editContent = '';

    public ?int $postToDelete = null;

    public $comments = []; // For new comment input

    public function mount()
    {
        $this->fetchFeeds();
    }

    #[On('newFeedPosted')]
    public function newFeedPosted()
    {
        $this->fetchFeeds();
    }

public function fetchFeeds()
{
    $user = Auth::user();

    $this->feeds = FeedModel::with(['comments.user', 'reactions'])
                            ->visibleToUser($user)
                            ->latest()
                            ->get();

    $this->orgs = User::where('role', 'org')->get();
    $this->types = Type::all();
}


public function getFilteredFeedsProperty()
{
    $user = Auth::user();

    $query = FeedModel::with(['comments.user', 'reactions'])
                      ->visibleToUser($user);

    if ($this->organizationFilter) {
        $query->where('org_id', $this->organizationFilter);
    }

    if ($this->typeFilter) {
        $query->where('type', $this->typeFilter);
    }

    return $query->latest()->get();
}


    public function resetFilters()
    {
        $this->organizationFilter = null;
        $this->typeFilter = null;
    }

public function createPost()
{
    $validated = $this->validate([
        'title'   => 'required|string|max:255',
        'content' => 'required|string|max:2000',
        'type'    => 'nullable|string|max:100',
        'photo'   => 'nullable|image|mimes:jpg,jpeg,png|max:5120',
 
    ]);

    $photoPath = $this->photo?->store('feeds', 'public');

    $user = Auth::user();

    $post = FeedModel::create([
        'user_id' => $user->id,
        'org_id'  => $user->role === 'org' ? $user->id : null, // ✅ fixed
        'title'   => $validated['title'],
        'content' => $validated['content'],
        'type'    => $validated['type'],
        'photo_url' => $photoPath,
        'privacy' => $validated['privacy'] ?? 'public',
    ]);

    if ($this->type && !Type::where('type_name', $this->type)->exists()) {
        Type::create(['type_name' => $this->type]);
    }

    $userName = $user->name;
    $orgName  = $post->org?->name ?? 'Public'; // ✅ will show "Public" if no org_id
    $activity = "📰 {$userName} from {$orgName} posted a feed: \"{$post->title}\"";

    RecentActivity::create([
        'message' => $activity,
        'type'    => 'feed',
    ]);

    event(new RecentActivities($activity));
    broadcast(new BroadcastFeed($post));

    $this->reset(['title', 'content', 'org_id', 'type', 'photo', 'privacy']);
    $this->modal('post-feed')->close();
    $this->fetchFeeds();

    Toaster::success('Feed post created!');
}


public function addComment($feedId)
{
    // Ensure the key exists
    $commentText = $this->comments[$feedId] ?? '';

    $this->validate([
        "comments.$feedId" => 'required|string|max:500',
    ]);

    Comment::create([
        'feed_id' => $feedId,
        'user_id' => Auth::id(),
        'comment' => $commentText,
    ]);

    // Clear the input for this feed
    $this->comments[$feedId] = '';

    $this->fetchFeeds();
}


    // ───── Reactions ─────
    public function toggleHeart(FeedModel $feed)
    {
        $reaction = Reaction::where('feed_id', $feed->id)
            ->where('user_id', Auth::id())
            ->where('type', 'heart')
            ->first();

        if ($reaction) {
            $reaction->delete();
        } else {
            Reaction::create([
                'feed_id' => $feed->id,
                'user_id' => Auth::id(),
                'type' => 'heart',
            ]);
        }

        $this->fetchFeeds();
    }

    // ───── Edit / Update Post ─────
    public function editPost($id)
    {
        $post = FeedModel::findOrFail($id);

        $this->postToEdit = $post->id;
        $this->showPost = [
    'title' => $post->title,
    'content' => $post->content,
    'org_id' => $post->org_id,
    'type' => $post->type,
];


        $this->modal('edit-post')->show();
    }

    public function updatePost()
    {
        $this->validate([
    'showPost.title' => 'required|string|max:255',
    'showPost.content' => 'required|string|max:2000',
    'showPost.org_id' => 'nullable|exists:orgs,id',
    'showPost.type' => 'nullable|string|max:100',
    'photo' => 'nullable|image|max:2048',
]);


        $post = FeedModel::findOrFail($this->postToEdit);

        if ($this->photo) {
            $photoPath = $this->photo->store('feeds', 'public');
            $post->photo_url = $photoPath;
        }

        $post->update([
    'title' => $this->showPost['title'],
    'content' => $this->showPost['content'],
    'org_id' => $this->showPost['org_id'],
    'type' => $this->showPost['type'],
    'photo_url' => $post->photo_url,
]);


        if ($this->showPost['type'] && !Type::where('type_name', $this->showPost['type'])->exists()) {
            Type::create(['type_name' => $this->showPost['type']]);
        }

        $this->reset(['showPost', 'photo', 'postToEdit']);
        $this->modal('edit-post')->close();
        $this->fetchFeeds();

        Toaster::success('Feed post updated!');
    }

    // ───── Delete Post ─────
    public function confirmDelete(int $id)
    {
        $this->postToDelete = $id;
        $this->modal('deletePost')->show();
    }

    public function deletePost()
    {
        if ($this->postToDelete) {
            $post = FeedModel::findOrFail($this->postToDelete);
            $type = $post->type;

            $post->delete();
            $this->reset('postToDelete');
            Toaster::success('Feed post deleted.');

            if ($type && !FeedModel::where('type', $type)->exists()) {
                Type::where('type_name', $type)->delete();
            }

            $this->modal('deletePost')->close();
            $this->fetchFeeds();
        }
    }

    public function render()
    {
        return view('livewire.admin.feed.manage-feed');
    }
}
