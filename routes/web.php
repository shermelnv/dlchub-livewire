<?php

use Livewire\Volt\Volt;
use Illuminate\Support\Facades\Route;

// USER
use App\Livewire\User\Feed;
use App\Livewire\User\Voting;
use App\Livewire\User\Chat;
use App\Livewire\User\Advertisement;

// ADMIN / SUPERADMIN

use App\Livewire\Admin\User\ManageUsers;
use App\Livewire\Admin\Feed\ManageFeed;
use App\Livewire\Admin\Voting\ManageVoting;
use App\Livewire\Admin\Advertisement\ManageAdvertisement;
use App\Livewire\Admin\Chat\ManageChat;

// GLOBAL

use App\Livewire\VotingRoom;


// MODELS


use App\Models\User as  UserModel;
use App\Models\GroupChat as  GroupChatModel;
use App\Models\VotingRoom as  VotingRoomModel;
use App\Models\Advertisement as  AdvertisementModel;

Route::get('/', function () {
    return redirect()->route('login');
})->name('home');

Route::get('/dashboard', function () {
    $studentCount = UserModel::where('role', 'user')->count();
    $groupChatCount = GroupChatModel::count();
    $activeVoteCount = VotingRoomModel::where('status', 'Ongoing')
        ->count();
    $adsCount = AdvertisementModel::count();

    return view('dashboard', [
        'studentCount' => $studentCount,
        'groupChatCount' => $groupChatCount,
        'activeVoteCount' => $activeVoteCount,
        'adsCount' => $adsCount,
    ]);
})->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
    

    Route::middleware('admin.only')->group(function () {
        Route::get('admin/user/manage-users', ManageUsers::class)->name('admin.user.manage-users');
        Route::get('admin/voting/manage-voting', ManageVoting::class)->name('admin.voting.manage-voting');
        Route::get('admin/chat/manage-chat', ManageChat::class)->name('admin.chat.manage-chat');
        Route::get('admin/advertisement/manage-advertisement', ManageAdvertisement::class)->name('admin.advertisement.manage-advertisement');
        Route::get('admin/feed/manage-feed', ManageFeed::class)->name('admin.feed.manage-feed');

    });

    // Route::middleware('admin.only')->group(function () {


    // });


    Route::middleware('user.only')->group(function () {
        Route::get('user/feed', Feed::class)->name('user.feed');
        Route::get('user/advertisement', Advertisement::class)->name('user.advertisement');
// routes/web.php

    Route::get('user/chat/{groupCode?}', Chat::class)->name('user.chat');



        Route::get('user/voting', Voting::class)->name('user.voting');
    // });

    Route::get('/voting-room/{id}', VotingRoom::class)->name('voting.room');
    // Route::get('/chat/{groupChat}', Chat::class)->name('chat.room');

    });
});

require __DIR__.'/auth.php';
