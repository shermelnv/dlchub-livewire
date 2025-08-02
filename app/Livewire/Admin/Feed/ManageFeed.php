<?php

namespace App\Livewire\Admin\Feed;

use App\Models\Org;
use App\Models\Type;
use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\RecentActivity;
use Masmerise\Toaster\Toaster;
use App\Events\RecentActivities;
use App\Models\Feed as FeedModel;
use Illuminate\Support\Facades\Auth;

class ManageFeed extends Component
{

    use WithFileUploads;

    public $dateFrom , $dateTo;

    public $feeds = [];


    public $showPost = [
        'title' => '',
        'content' => '',
        'organization' => '',
        'type' => '',
    ];


    public $orgs, $types;

    public $organizationFilter = null;
    public $typeFilter = null; // for filtering



    public $title, $content, $organization, $type;
    public $photo;

    public function getFilteredFeedsProperty()
    {
    $query = FeedModel::query();

    if ($this->organizationFilter) {
        $query->where('organization', $this->organizationFilter);
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



    public function mount()
    {
        $this->fetchFeeds();
    }

    public function fetchFeeds()
    {
        $this->feeds = FeedModel::latest()->get();

        $this->orgs = Org::all();
        $this->types = Type::all();
    }

// ───── Create ─────
public function createPost()
{
    $validated = $this->validate([
        'title' => 'required|string|max:255',
        'content' => 'required|string|max:2000',
        'organization' => 'nullable|string|max:255',
        'type' => 'nullable|string|max:100',
        'photo' => 'nullable|image|max:2048',
    ]);

    $photoPath = $this->photo?->store('feeds', 'public');

    $post = FeedModel::create([
        'user_id' => Auth::id(),
        'title' => $validated['title'],
        'content' => $validated['content'],
        'organization' => $validated['organization'],
        'type' => $validated['type'],
        'photo_url' => $photoPath,
        'published_at' => now(),
    ]);

    if ($this->type && !Type::where('type_name', $this->type)->exists()) {
        Type::create(['type_name' => $this->type]);
    }

    $userName = auth()->user()->name;
    $orgName = $post->organization ?? 'Unknown Org';
    $activity = "📰 {$userName} from {$orgName} posted a feed: \"{$post->title}\"";

    RecentActivity::create([
        'message' => $activity,
        'type' => 'feed',
    ]);
    event(new RecentActivities($activity));

    $this->reset(['title', 'content', 'organization', 'type', 'photo']);
    $this->modal('post-feed')->close();
    $this->fetchFeeds();

    Toaster::success('Feed post created!');
}


    
    public ?int $postToEdit = null;
    public string $editContent = '';
    
public function editPost($id)
{
    $post = FeedModel::findOrFail($id);

    $this->postToEdit = $post->id;
    $this->showPost = [
        'title' => $post->title,
        'content' => $post->content,
        'organization' => $post->organization,
        'type' => $post->type,
    ];

    $this->modal('edit-post')->show();
}

public function updatePost()
{
    $this->validate([
        'showPost.title' => 'required|string|max:255',
        'showPost.content' => 'required|string|max:2000',
        'showPost.organization' => 'nullable|string|max:255',
        'showPost.type' => 'nullable|string|max:100',
        'photo' => 'nullable|image|max:2048',
    ]);

    $post = FeedModel::findOrFail($this->postToEdit);

    // Upload new photo
    if ($this->photo) {
        $photoPath = $this->photo->store('feeds', 'public');
        $post->photo_url = $photoPath;
    }

    // Update fields
    $post->update([
        'title' => $this->showPost['title'],
        'content' => $this->showPost['content'],
        'organization' => $this->showPost['organization'],
        'type' => $this->showPost['type'],
        'photo_url' => $post->photo_url,
    ]);

    // Create type if doesn't exist
    if ($this->showPost['type'] && !Type::where('type_name', $this->showPost['type'])->exists()) {
        Type::create(['type_name' => $this->showPost['type']]);
    }

    $this->reset(['showPost', 'photo', 'postToEdit']);
    $this->modal('edit-post')->close();
    $this->fetchFeeds();

    Toaster::success('Feed post updated!');
}



    public ?int $postToDelete = null;


    public function confirmDelete(int $id)
    {
        $this->postToDelete = $id;
        $this->modal('deletePost')->show(); // open modal
    }

    public function deletePost()
    {
    if ($this->postToDelete) {
        $post = FeedModel::findOrFail($this->postToDelete);
        $type = $post->type;

        $post->delete();
        $this->reset('postToDelete');
        Toaster::success('Feed post deleted.');

        // 🧹 Check if this type is now unused and delete it
        if ($type && !FeedModel::where('type', $type)->exists()) {
            Type::where('type_name', $type)->delete();
        }

        $this->modal('deletePost')->close();
        $this->fetchFeeds();
    }
    }





    // public function filter()
    // {
    //     $this->validate([
    //         'filterOrganization' => 'nullable|string',
    //         'filterType' => 'nullable|string',
    //         'dateFrom' => 'nullable|date',
    //         'dateTo' => 'nullable|date|after_or_equal:dateFrom',
    //     ]);

    //     $query = FeedModel::query();

    //     if ($this->filterOrganization) {
    //         $query->where('organization', $this->filterOrganization);
    //     }

    //     if ($this->filterType) {
    //         $query->where('type', $this->filterType);
    //     }

    //     if ($this->dateFrom) {
    //         $query->whereDate('published_at', '>=', $this->dateFrom);
    //     }

    //     if ($this->dateTo) {
    //         $query->whereDate('published_at', '<=', $this->dateTo);
    //     }

    //     $this->feeds = $query->latest('published_at')->get();
    // }





    // public function resetFilters()
    // {
    //     $this->reset([
    //         'filterOrganization', 'filterType', 'dateFrom', 'dateTo',
    //     ]);

    // $this->fetchFeeds();
    // }



    public function render()
    {
        return view('livewire.admin.feed.manage-feed');
    }
}