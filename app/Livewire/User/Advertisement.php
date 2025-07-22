<?php

namespace App\Livewire\User;

use Livewire\Component;
use App\Models\Advertisement as UserAdvertisement;

class Advertisement extends Component
{
    public $photos = [];


    public $category;
 
    public $advertisements = [];
    public $categoryFilter = null;

    
        public function mount()
    {
        $this->fetchAdvertisements();
    }

        public function fetchAdvertisements()
    {
        $this->advertisements = UserAdvertisement::latest()->get();
    }
        public function getFilteredAdvertisementsProperty()
    {
        return $this->categoryFilter
            ? UserAdvertisement::where('category', $this->categoryFilter)->latest()->get()
            : UserAdvertisement::latest()->get();
    }
    public function render()
    {
        return view('livewire.user.advertisement');
    }
}
