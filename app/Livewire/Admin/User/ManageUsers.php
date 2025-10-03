<?php

namespace App\Livewire\Admin\User;

use App\Models\User;
use Livewire\Component;
use App\Models\GroupChat;
use App\Models\VotingRoom;
use Illuminate\Support\Str;
use Livewire\Attributes\On;
use Livewire\WithPagination;
use App\Mail\AccountVerified;
use App\Models\Advertisement;
use App\Events\DashboardStats;
use App\Events\UserRegistered;
use Masmerise\Toaster\Toaster;
use App\Mail\EmailVerification;
use App\Mail\UserAccountCreated;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class ManageUsers extends Component
{
    use WithPagination;

    public $user;
    public array $showUser = [];
    public $name, $email, $password;
    public $organization, $role, $bio, $avatar_url, $is_active = true;

    protected $paginationTheme = 'tailwind';

    public ?int $deleteUserId = null;
    public ?int $approveUserId = null;
    public ?int $rejectUserId = null;
    public ?int $banUserId = null;
    public ?int $unbanUserId = null;

    public string $search = '';
    public string $status = 'all';


    public function archiveUser()
    {
        DB::transaction(function () {
        $students = User::where('role', 'user')->get();

        $archiveData = $students->map(function ($student) {
            return [
                'user_id' => $student->id,
                'name' => $student->name,
                'username' => $student->username,
                'email' => $student->email,
                'role' => $student->role,
                'status' => $student->status,
                'document' => $student->document,
                'profile_image' => $student->profile_image,
                'archived_at' => now(),
            ];
        })->toArray();

            DB::table('archives')->insert($archiveData);
            User::where('role', 'user')->delete();
        });

        Toaster::success('All students have been archived.');
    }

    public function mount()
    {
        $this->user = auth()->user();
        $this->roles = User::pluck('role', 'id')->toArray();
    }

    public function createUser()
    {
        $validated = $this->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
        ]);

        $email = $validated['email'];
        $localPart = strstr($email, '@', true);
        $domainPart = substr(strstr($email, '@'), 1);

        // Validate domain is Pampanga State University
        if ($domainPart !== 'pampangastateu.edu.ph' || !preg_match('/^\d+$/', $localPart)) {
            $this->addError('email', 'Email must be a Pampanga State University account.');
            return;
        }

        $randomPassword       = Str::random(10);
        $validated['password'] = Hash::make($randomPassword);
        $validated['status']   = 'approved';
        $validated['username'] = $localPart;

        $user = User::create($validated);

        // Email the random password
        Mail::to($user->email)->send(new UserAccountCreated($user, $randomPassword));

        event(new DashboardStats([
            'students'      => User::where('role', 'user')->count(),
            'groupChats'    => GroupChat::count(),
            'activeVotings' => VotingRoom::where('status', 'Ongoing')->count(),
            'advertisements'=> Advertisement::count(),
        ]));

        $this->reset(['name', 'email']);
        $this->modal('create-user')->close();

        Toaster::success('User created successfully!');
    }

    public function viewUser($id)
    {
        $this->showUser = User::findOrFail($id)->toArray();
        $this->modal('view-user')->show();
    }

    public function getUser($id)
    {
        $this->showUser = User::findOrFail($id)->toArray();
        $this->modal('edit-user')->show();
    }

    public function updateUser()
    {
        $this->validate([
            'showUser.name'         => 'required|string|max:255',
            'showUser.organization' => 'nullable|string|max:255',
            'showUser.role'         => 'required|in:user,org,admin,superadmin',
            'showUser.status'       => 'required|in:pending,approved,rejected',
        ]);

        $user          = User::findOrFail($this->showUser['id']);
        $wasNotVerified = $user->status === 'pending';
        $newStatus      = $this->showUser['status'];

        $user->update([
            'name'   => $this->showUser['name'],
            'role'   => $this->showUser['role'],
            'status' => $newStatus,
        ]);

        // Send account verified email if newly verified
        if ($wasNotVerified && $newStatus === 'approved') {
            Mail::to($user->email)->send(new AccountVerified($user));
        }

        $this->reset('showUser');
        $this->modal('edit-user')->close();

        Toaster::success('User updated successfully!');
    }

    public function confirmDelete(int $id)
    {
        $this->deleteUserId = $id;
        $this->modal('delete-user')->show();
    }


    public $roles = [];
    public function updateRole($userId)
    {
        $user = User::findOrFail($userId);
        $user->role = $this->roles[$userId];
        $user->save();


        Toaster::success('User role updated successfully!');
    }

    public function deleteUser()
    {
        if ($this->deleteUserId) {
            User::findOrFail($this->deleteUserId)->delete();

            $this->reset(['name', 'email', 'password', 'showUser', 'deleteUserId']);
            $this->modal('delete-user')->close();

            Toaster::success('User deleted successfully!');
        }
    }

    public function confirmApprove(int $id)
    {
        $this->approveUserId = $id;
        $this->modal('approve-user')->show();
    }

    public function approveUser()
    {
        if ($this->approveUserId) {
            $user         = User::findOrFail($this->approveUserId);
            $user->status = 'approved';
            $user->save();

            // broadcast(new UserRegistered());
            Mail::to($user->email)->send(new EmailVerification($user));

            Toaster::success('Approved Successfully!');
            $this->modal('approve-user')->close();
        }
    }

    public function confirmReject(int $id)
    {
        $this->rejectUserId = $id;
        $this->modal('reject-user')->show();
    }

    public function rejectUser()
    {
        if ($this->rejectUserId) {
            $user         = User::findOrFail($this->rejectUserId);
            $user->status = 'rejected';
            $user->save();

            Toaster::success('Rejected Successfully!');
            $this->modal('reject-user')->close();
        }
    }

    public function confirmBan(int $id)
    {
        $this->banUserId = $id;
        $this->modal('ban-user')->show();
    }

    public function banUser()
    {
        if ($this->banUserId) {
            $user         = User::findOrFail($this->banUserId);
            $user->status = 'banned';
            $user->save();

            Toaster::success('Banned Successfully!');
            $this->modal('ban-user')->close();
        }
    }

    public function confirmUnban(int $id)
    {
        $this->unbanUserId = $id;
        $this->modal('unban-user')->show();
    }

    public function unbanUser()
    {
        if ($this->unbanUserId) {
            $user         = User::findOrFail($this->unbanUserId);
            $user->status = 'approved';
            $user->save();

            Toaster::success('Unbanned Successfully!');
            $this->modal('unban-user')->close();
        }
    }

    #[On('newUser')]
    public function handleNewUserRealtime()
    {
        Toaster::success('A new user just registered');
    }

    public function updatedStatus()
    {
        $this->resetPage();
    }
    public function updatedSearch()
{
    $this->resetPage();
}



public function render()
{
    $currentUser = auth()->user();

    $query = User::query()
        ->when($currentUser->role === 'admin', function ($q) {
            // Admins can only see users (not admins or superadmins)
            $q->where('role', 'user');
        })
        ->when($currentUser->role === 'superadmin', function ($q) {
            // Superadmins can see both users and admins (not other superadmins or orgs)
            $q->whereIn('role', ['user', 'admin']);
        })
        ->when($this->search, function ($q) {
            $q->where(function ($sub) {
                $sub->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('email', 'like', '%' . $this->search . '%')
                    ->orWhere('username', 'like', '%' . $this->search . '%');
            });
        })
        ->when($this->status !== 'all', function ($q) {
            $q->where('status', $this->status);
        })
        ->orderByRaw("CASE 
            WHEN role = 'admin' THEN 1 
            WHEN role = 'user' THEN 2 
            ELSE 3 
        END")
        ->orderBy('created_at', 'desc');

    $manageUsers = $query->paginate(7);

    return view('livewire.admin.user.manage-users', [
        'manageUsers' => $manageUsers,
    ]);
}

}
