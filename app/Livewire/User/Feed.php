<?php

namespace App\Livewire\User;


use App\Models\Org;
use App\Models\Type;
use Livewire\Component;
use Livewire\Attributes\On;
use Masmerise\Toaster\Toaster;
use App\Models\Feed as FeedModel;


class Feed extends Component
{
    public $dateFrom , $dateTo;
    public $orgs, $types;
    public $filterOrganization, $filterType;
    public $feeds;
    
    public $organizationFilter = null;
    public $typeFilter = null; // for filtering



    public function mount()
    {
        $this->fetchFeeds();
        $this->orgs = Org::all();
    }

    #[On('newFeedPosted')]
    public function newFeedPosted()
    {
        Toaster::info('new feed just posted!');
        $this->fetchFeeds();
    }

    #[On('newAdPosted')]
    public function newAdPosted()
    {
        Toaster::info('new ad just posted!');
    }

    public function fetchFeeds()
    {
        $this->feeds = FeedModel::latest()->get();

        $this->orgs = Org::all();
        $this->types = Type::all();
    }


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


    


    public function render()
    {
        return view('livewire.user.feed.feed');
    }
}