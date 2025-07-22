<?php

namespace App\Livewire\User;

use Livewire\Component;
use Masmerise\Toaster\Toaster;
use App\Models\Feed as FeedModel;
use Illuminate\Support\Facades\Auth;

class Feed extends Component
{
    public $title;
    public $content;
    public $search = '';
    public $category = '';
    public $appliedCategory = '';
    public $department = '';
    public $appliedDepartment = '';
    public $dateFrom;
    public $dateTo;
    public $appliedDateFrom;
    public $appliedDateTo;

    public $feeds = [];

    public function mount()
    {
        $this->fetchFeeds();
    }

    public function fetchFeeds()
    {
        $this->feeds = FeedModel::latest('published_at')->get();
    }

    public function getFilteredFeedsProperty()
    {
        return FeedModel::query()
            ->when($this->appliedDepartment, fn($q) => $q->where('department', $this->appliedDepartment))
            ->when($this->appliedCategory, fn($q) => $q->where('category', $this->appliedCategory))
            ->when($this->appliedDateFrom, fn($q) => $q->whereDate('published_at', '>=', $this->appliedDateFrom))
            ->when($this->appliedDateTo, fn($q) => $q->whereDate('published_at', '<=', $this->appliedDateTo))
            ->latest('published_at')
            ->get();
    }



    public function filter()
    {
        $this->appliedCategory = $this->category;
        $this->appliedDepartment = $this->department;
        $this->appliedDateFrom = $this->dateFrom;
        $this->appliedDateTo = $this->dateTo;
    }

    public function toggleCategory($cat)
    {
        $this->category = $this->category === $cat ? '' : $cat;
    }

    public function resetFilters()
    {
        $this->reset([
            'category', 'appliedCategory',
            'department', 'appliedDepartment',
            'dateFrom', 'dateTo', 'appliedDateFrom', 'appliedDateTo',
        ]);
    }

    public function render()
    {
        return view('livewire.user.feed');
    }
}