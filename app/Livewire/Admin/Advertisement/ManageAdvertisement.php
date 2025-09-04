<?php

namespace App\Livewire\Admin\Advertisement;

use Storage;
use App\Models\Org;
use App\Models\User;
use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\Advertisement;

use App\Events\DashboardStats;
use App\Models\RecentActivity;
use Livewire\Attributes\Title;
use Masmerise\Toaster\Toaster;
use App\Events\RecentActivities;
use App\Models\AdvertisementPhoto;
use Illuminate\Support\Facades\Auth;
use App\Notifications\UniversalNotification;
use Illuminate\Support\Facades\Notification;
use Livewire\Features\SupportFileUploads\WithFileUploads;
use App\Events\ManageAdvertisement as BroadcastAdvertisement;


#[Title('Advertisement')]
class ManageAdvertisement extends Component
{
    use WithFileUploads;


    public ?string $previewPhoto = null;
public $org_id;


    // ───── Form Fields ─────
    public $photos = [];
    public $title;
    public $description;
    public $organization;
    public $privacy;

    public $editingAdId = null;
    public $deletingAdId = null;
    public $showDeleteConfirm = false;

    // ───── Display Data ─────
    public $adCount;
    public $trendingOrgs = [];
    public $advertisements = [];
    public $organizationFilter = null;
    public $orgs;

    // ───── Stats ─────

    #[On('newAdPosted')]
    public function newAdPosted()
    {
        Toaster::info('new ad just posted!');
        $this->fetchAdvertisements();
    }

    #[On('newFeedPosted')]
    public function newFeedPosted()
    {
        Toaster::info('new feed just posted!');
    }
    // ───── Computed ─────
public function getFilteredAdvertisementsProperty()
{
    $user = Auth::user();

    $query = Advertisement::with('photos')->visibleToUser($user);

    if ($this->organizationFilter) {
        $query->where('org_id', $this->organizationFilter);
    }

    return $query->latest()->get();
}



    
    public function resetFilters()
    {
    $this->organizationFilter = null;
    $this->typeFilter = null;
    }


    public function openPhotoModal($path)
    {
        $this->previewPhoto = $path;
        $this->modal('photo-preview')->show();
    }
    
    // ───── Lifecycle ─────
    public function mount()
    {
        $this->fetchAdvertisements();
    }

    // ───── Data Fetching ─────
public function fetchAdvertisements()
{
    $user = Auth::user();


    // ───── Orgs for filter ─────
    $this->orgs = User::where('role', 'org')->get();

    // ───── Trending Orgs ─────
$this->trendingOrgs = Advertisement::visibleToUser($user)
                                   ->whereNotNull('org_id')
                                   ->select('org_id')
                                   ->selectRaw('org_id, COUNT(*) as ad_count')
                                   ->with('org')
                                   ->groupBy('org_id')
                                   ->orderByDesc('ad_count')
                                   ->limit(5)
                                   ->get();
// Fetch advertisements visible to user
        $this->advertisements = Advertisement::with('photos')
                                             ->visibleToUser($user)
                                             ->latest()
                                             ->get();
    // ───── Advertisement count ─────
    // $this->adCount = $this->advertisements->count();
}



    // ───── Create or Update ─────
    public function createAdvertisement()
    {
        $validated = $this->validate([
            
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:2000',
            // 'org_id' => 'nullable|exists:orgs,id',

            'photos.*' => 'nullable|image|max:2048',
            'privacy' => 'nullable',
        ]);
        $user = Auth::user();
        
        $ad = Advertisement::create([
            'user_id' => Auth::id(), // assign directly
            'org_id' => $user->role === 'org' ? $user->id : null,// assign directly
            ...$validated,
            'privacy' => $validated['privacy'] ?? 'public',
        ]);

       // Upload photos
        foreach ($this->photos as $photo) {
            $path = $photo->store('advertisements', 'public');
            AdvertisementPhoto::create([
                'advertisement_id' => $ad->id,
                'photo_path' => $path,
            ]);
        }

        // Log Activity
        $user = Auth::user();
        $orgName = $ad->organization ?? 'Unknown Org';
  


            // Get all users whose ID is not equal to the authenticated user's ID
            $otherUsers = User::where('id', '!=', $user)->get();

            Notification::send($otherUsers, new UniversalNotification(
                'advertisement',
                "$user->name posted a advertisement \"$ad->title\"",
                $user->id,

            ));
        


        RecentActivity::create([
            'user_id'   => $user->id,
            'message'   => "{$user->name} created a new advertisement: \" $ad->title\" ",
            'type'      => 'advertisement',
            'action'    => 'posted',
        ]);

        
        broadcast(new BroadcastAdvertisement($ad))->toOthers();
        event(new RecentActivities());
        event(new DashboardStats([
            'students' => \App\Models\User::where('role', 'user')->count(),
            'groupChats' => \App\Models\GroupChat::count(),
            'activeVotings' => \App\Models\VotingRoom::where('status', 'Ongoing')->count(),
            'advertisements' => Advertisement::count(),
        ]));

        Toaster::success('Advertisement published!');
        $this->reset(['title', 'description', 'organization', 'photos']);
        $this->modal('add-advertisement')->close();
        $this->fetchAdvertisements();
    }

    // ───── Edit / Delete ─────

    // ───── EDIT SETUP ─────
        public array $showAd = [];
   public function editAdvertisement($id)
    {
        $ad = Advertisement::with('photos')->findOrFail($id);
        $this->editingAdId = $ad->id;
        $this->showAd = $ad->toArray();
        $this->photos = [];

        $this->modal('edit-advertisement')->show();
    }

     public function updateAdvertisement()
    {
        $this->validate([
            'showAd.title' => 'required|string|max:255',
            'showAd.description' => 'nullable|string|max:2000',
            'showAd.organization' => 'nullable|string|max:255',
            'photos.*' => 'nullable|image|max:2048',
        ]);

        $ad = Advertisement::findOrFail($this->editingAdId);
        $ad->update([
            'title' => $this->showAd['title'],
            'description' => $this->showAd['description'],
            'organization' => $this->showAd['organization'],
        ]);

        // Replace photos if new ones uploaded
        if (!empty($this->photos)) {
        // Delete old photos
        foreach ($ad->photos as $photo) {
            Storage::disk('public')->delete($photo->photo_path);
            $photo->delete();
        }

        // Upload new photos
        foreach ($this->photos as $photo) {
            $path = $photo->store('advertisements', 'public');
            AdvertisementPhoto::create([
                'advertisement_id' => $ad->id,
                'photo_path' => $path,
            ]);
        }
        }
        Toaster::success('Advertisement updated!');

        $this->reset(['editingAdId', 'showAd', 'photos']);
        $this->modal('edit-advertisement')->close();
    }



    public function confirmDelete($id)
    {
        $this->deletingAdId = $id;
        $this->modal('delete-advertisement')->show();
    }


    public function deleteAdvertisement()
    {

        $ad = Advertisement::findOrFail($this->deletingAdId);

        foreach ($ad->photos as $photo) {
            \Storage::disk('public')->delete($photo->photo_path);
            $photo->delete();
        }

        $ad->delete();
        $this->deletingAdId = null;

        Toaster::success('Advertisement deleted.');
        $this->modal('delete-advertisement')->close();
        $this->fetchAdvertisements();
    }

    // ───── Render ─────
    public function render()
    {
        return view('livewire.admin.advertisement.manage-advertisement');
    }
}
