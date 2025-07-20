<?php

namespace App\Livewire\Admin\Advertisement;

use Livewire\Component;
use App\Models\Advertisement;
use Masmerise\Toaster\Toaster;
use Livewire\Features\SupportFileUploads\WithFileUploads;

class ManageAdvertisement extends Component
{
    use WithFileUploads;

    // ────────────────────────────────────────────────
    // Form Data
    // ────────────────────────────────────────────────

    public $photos = [];

    public $title;
    public $category;
    public $description;
    public $organization;
    public $location;
    public $event_date;
    public $time;
    public $deadline;
    public $tags;

    // ────────────────────────────────────────────────
    // State and Data
    // ────────────────────────────────────────────────

    public $advertisements = [];
    public $categoryFilter = null;

    // ────────────────────────────────────────────────
    // Computed: Filtered Advertisements
    // ────────────────────────────────────────────────

    public function getFilteredAdvertisementsProperty()
    {
        return $this->categoryFilter
            ? Advertisement::where('category', $this->categoryFilter)->latest()->get()
            : Advertisement::latest()->get();
    }

    // ────────────────────────────────────────────────
    // Lifecycle
    // ────────────────────────────────────────────────

    public function mount()
    {
        $this->fetchAdvertisements();
    }

    // ────────────────────────────────────────────────
    // Fetch all ads (initial load or refresh)
    // ────────────────────────────────────────────────

    public function fetchAdvertisements()
    {
        $this->advertisements = Advertisement::latest()->get();
    }

    // ────────────────────────────────────────────────
    // Create New Advertisement
    // ────────────────────────────────────────────────

    public function createAdvertisement()
    {
        $validated = $this->validate([
            'title'        => 'required|string|max:255',
            'category'     => 'required|string|max:255',
            'description'  => 'nullable|string|max:2000',
            'organization' => 'nullable|string|max:255',
            'location'     => 'nullable|string|max:255',
            'event_date'   => 'nullable|date',
            'time'         => 'nullable',
            'deadline'     => 'nullable|date',
            'tags'         => 'nullable|string|max:500',
        ]);

        Advertisement::create($validated);

        Toaster::success('Advertisement published successfully!');

        $this->reset([
            'title', 'category', 'description', 'organization',
            'location', 'event_date', 'time', 'deadline', 'tags'
        ]);

        $this->modal('add-advertisement')->close();
    }

    // ────────────────────────────────────────────────
    // Render Component
    // ────────────────────────────────────────────────

    public function render()
    {
        return view('livewire.admin.advertisement.manage-advertisement');
    }
}
