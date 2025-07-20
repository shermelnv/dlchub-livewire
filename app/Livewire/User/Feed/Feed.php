<?php

namespace App\Livewire\User\Feed;

use Livewire\Component;
use Masmerise\Toaster\Toaster;
use App\Models\Feed as FeedModel;
use Illuminate\Support\Facades\Auth;

class Feed extends Component
{


public $title;
public $content;
public $search = '';
public $category = ''; // temporary selected
public $appliedCategory = ''; // actual filter


public $department = '';
public $appliedDepartment = '';

public $dateFrom;
public $dateTo;
public $appliedDateFrom;
public $appliedDateTo;


    public function createPost()
    {
        $validated = $this->validate([
            'title' => 'required|string|max:255', 
            'content' => 'required|string|max:2000',
            'category' => 'required|string|max:255',
            'department' => 'required|string',
        ]);

        FeedModel::create([
            'user_id' => Auth::id(),
            'title' => $validated['title'], 
            'content' => $validated['content'],
            'category' => $validated['category'],
            'department' => $validated['department'],
            'published_at' => now(),
        ]);

        $this->reset(['title', 'content', 'category', 'department']);

        $this->modal('post-feed')->close();
        Toaster::success('Post successfully uploaded');
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
    $feeds = FeedModel::query()
        ->when($this->appliedDepartment, fn($q) => $q->where('department', $this->appliedDepartment))
        ->when($this->appliedCategory, fn($q) => $q->where('category', $this->appliedCategory))
        ->when($this->appliedDateFrom, fn($q) => $q->whereDate('published_at', '>=', $this->appliedDateFrom))
        ->when($this->appliedDateTo, fn($q) => $q->whereDate('published_at', '<=', $this->appliedDateTo))
        ->latest('published_at')
        ->get();

    return view('livewire.user.feed', compact('feeds'));
}


}