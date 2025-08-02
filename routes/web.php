<?php

use App\Models\Org;
use App\Models\User;

// USER
use App\Mail\TestMail;
use App\Events\Example;
use Livewire\Volt\Volt;
use App\Models\GroupChat;

// ADMIN / SUPERADMIN

use App\Livewire\User\Chat;
use App\Livewire\User\Feed;
use App\Livewire\OrgProfile;
use App\Livewire\VotingRoom;
use Illuminate\Http\Request;

// GLOBAL

use App\Livewire\User\Voting;


// MODELS


use App\Events\RecentActivities;
use App\Models\User as  UserModel;
use App\Livewire\User\Advertisement;
use Illuminate\Support\Facades\Mail;
use App\Livewire\Admin\Org\ManageOrg;
use Illuminate\Support\Facades\Route;
use App\Livewire\Admin\Chat\ManageChat;
use App\Livewire\Admin\Feed\ManageFeed;
use App\Livewire\Admin\User\ManageUsers;
use App\Livewire\DashboardRecentActivity;
use App\Livewire\Admin\Voting\ManageVoting;
use App\Events\GroupChat as  GroupChatEvent;
use App\Models\VotingRoom as  VotingRoomModel;
use App\Models\Advertisement as  AdvertisementModel;
use App\Livewire\Admin\Advertisement\ManageAdvertisement;

Route::get('/test-email', function () {
    Mail::to('carreon.carll@gmail.com')->send(new TestMail());
    return 'Email sent!';
});

Route::view('/registered-success', 'registered-successfully')->name('registered-success');
Route::view('/not-verified', 'not-verified')->name('not-verified');

Route::get('/', function () {


    if (auth()->check()) {
        return redirect()->route('redirectToPage');
    } else {
        return redirect()->route('login');
    }
})->name('home');

Route::get('redirectAfter_LoginOrRegister', function () {


    

    if (!auth()->check()) {
        return redirect()->route('login'); 
    }

    if (auth()->user()->role === 'admin' || auth()->user()->role === 'superadmin' ) {
        return redirect()->route('dashboard');
    } elseif(auth()->user()->role === 'user' ) {
        return redirect()->route('user.feed');
    } elseif(auth()->user()->role === 'org') {
        return redirect()->route('admin.feed.manage-feed');
    }

})->name('redirectToPage');


Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
    

    Route::middleware('admin.only')->group(function () {
        Route::get('/dashboard', function () {

            return view('dashboard');
        })->name('dashboard');
        

        Route::get('admin/user/manage-users', ManageUsers::class)->name('admin.user.manage-users');
        Route::get('admin/org/manage-org', ManageOrg::class)->name('admin.org.manage-org');
    });

    Route::middleware('sharedRole')->group(function() {

        
        Route::get('admin/voting/manage-voting', ManageVoting::class)->name('admin.voting.manage-voting');
        Route::get('admin/chat/manage-chat', ManageChat::class)->name('admin.chat.manage-chat');
        Route::get('admin/advertisement/manage-advertisement', ManageAdvertisement::class)->name('admin.advertisement.manage-advertisement');
        Route::get('admin/feed/manage-feed', ManageFeed::class)->name('admin.feed.manage-feed');
        

    });

    // Route::middleware('org.only')->group(function () {

    //     Route::get('admin/user/manage-users', ManageUsers::class)->name('admin.user.manage-users');
    //     Route::get('admin/voting/manage-voting', ManageVoting::class)->name('admin.voting.manage-voting');
    //     Route::get('admin/chat/manage-chat', ManageChat::class)->name('admin.chat.manage-chat');
    //     Route::get('admin/advertisement/manage-advertisement', ManageAdvertisement::class)->name('admin.advertisement.manage-advertisement');
    //     Route::get('admin/feed/manage-feed', ManageFeed::class)->name('admin.feed.manage-feed');
    //     Route::get('admin/org/manage-org', ManageOrg::class)->name('admin.org.manage-org');
    // });

    Route::middleware('user.only')->group(function () {
            Route::get('user/feed', Feed::class)->name('user.feed');
            Route::get('user/advertisement', Advertisement::class)->name('user.advertisement');
        // routes/web.php

            Route::get('user/chat/{groupCode?}', Chat::class)->name('user.chat');

            Route::get('user/voting', Voting::class)->name('user.voting');
            // });

            
            // Route::get('/chat/{groupChat}', Chat::class)->name('chat.room');

    });

    // ALL
    Route::get('/voting-room/{id}', VotingRoom::class)->name('voting.room');
    Route::get('org-profile/{org}', OrgProfile::class)->name('org.profile');
    // Route::get('org-profile', OrgProfile::class)->name('org.profile');



    Route::get('/broadcast-test', function () {
        $group = GroupChat::first();
        $message = $group->messages()->latest()->first();

        broadcast(new GroupChatEvent($message));

        return 'Broadcast sent';
    });



});

require __DIR__.'/auth.php';
