<?php

namespace App\Livewire\User;

use App\Models\Org;
use Livewire\Component;
use App\Models\Advertisement as AdvertisementModel;

class Advertisement extends Component
{

    public $organizationFilter = null;
    public $orgs;
    public $trendingOrgs = [];
    public $advertisements = [];

    // ───── Stats ─────

    public $stats = [
        'total_ads' => 0,
        'events' => 0,
        'internships' => 0,
        'jobs' => 0,
        'scholarships' => 0,
    ];
    
    // ───── Lifecycle ─────
    public function mount()
    {
        $this->fetchAdvertisements();
    }
    
    // ───── Data Fetching ─────
    public function fetchAdvertisements()
    {
        $this->advertisements = AdvertisementModel::with('photos')->latest()->get();
        $this->orgs = Org::all();

        $this->trendingOrgs = AdvertisementModel::select('organization')
            ->whereNotNull('organization')
            ->groupBy('organization')
            ->selectRaw('organization, COUNT(*) as ad_count')
            ->orderByDesc('ad_count')
            ->limit(5)
            ->get();
    }

    // ───── Computed ─────
    public function getFilteredAdvertisementsProperty()
    {
        return $this->organizationFilter
            ? AdvertisementModel::with('photos')->where('organization', $this->organizationFilter)->latest()->get()
            : AdvertisementModel::with('photos')->latest()->get();
    }

    public function resetFilters()
    {
    $this->organizationFilter = null;
    $this->typeFilter = null;
    }

  
    public function render()
    {
        return view('livewire.user.advertisement.advertisement');
    }
}
