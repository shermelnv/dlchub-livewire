<?php

namespace App\Livewire\Admin\Advertisement;

use Storage;
use App\Models\Org;
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
use Livewire\Features\SupportFileUploads\WithFileUploads;
use App\Events\ManageAdvertisement as BroadcastAdvertisement;

#[Title('Advertisement')]
class ManageAdvertisement extends Component
{
    use WithFileUploads;


    public ?string $previewPhoto = null;


    // ───── Form Fields ─────
    public $photos = [];
    public $title;
    public $description;
    public $organization;

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
        return $this->organizationFilter
            ? Advertisement::with('photos')->where('organization', $this->organizationFilter)->latest()->get()
            : Advertisement::with('photos')->latest()->get();
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
        $this->advertisements = Advertisement::with('photos')->latest()->get();
        $this->orgs = Org::all();

        $this->trendingOrgs = Advertisement::select('organization')
            ->whereNotNull('organization')
            ->groupBy('organization')
            ->selectRaw('organization, COUNT(*) as ad_count')
            ->orderByDesc('ad_count')
            ->limit(5)
            ->get();
        $this->adCount = Advertisement::count();
    }

    // ───── Create or Update ─────
    public function createAdvertisement()
    {
        $validated = $this->validate([
            
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:2000',
            'organization' => 'nullable|string|max:255',
            'photos.*' => 'nullable|image|max:2048',
        ]);

        $ad = Advertisement::create([
            'user_id' => Auth::id(), // assign directly
            ...$validated,
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
        $activity = $user->name . ' created a new advertisement for ' . $orgName;

        RecentActivity::create([
            'message' => $activity,
            'type' => 'advertisement',
        ]);
        broadcast(new BroadcastAdvertisement($ad));
        event(new RecentActivities($activity));
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


        $user = Auth::user();
        $activity = $user->name . ' updated an advertisement for ' . ($ad->organization ?? 'Unknown Org');

        RecentActivity::create([
            'message' => $activity,
            'type' => 'advertisement',
        ]);

        event(new RecentActivities($activity));

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
