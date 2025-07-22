<?php

namespace App\Livewire\Admin\User;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Masmerise\Toaster\Toaster;
use Illuminate\Support\Facades\Hash;

class ManageUsers extends Component
{
    use WithPagination;

    public $user;
    public array $showUser = [];
    public $name, $email, $password;

    protected $paginationTheme = 'tailwind';

    public ?int $deleteUserId = null;

public $organization, $role, $bio, $avatar_url, $is_active = true;


    public function mount()
    {
        $this->user = auth()->user();

    }

public function createUser()
{
    $validated = $this->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|string|min:6',
        'organization' => 'nullable|string|max:255',
        'role' => 'required|in:user,admin,superadmin',
        'bio' => 'nullable|string',
        'avatar_url' => 'nullable|url',
        'is_active' => 'boolean',
    ]);

    $validated['password'] = Hash::make($validated['password']);

    User::create($validated);

    $this->reset(['name', 'email', 'password', 'organization', 'role', 'bio', 'avatar_url', 'is_active']);
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
        'showUser.name' => 'required|string|max:255',
        'showUser.organization' => 'nullable|string|max:255',
        'showUser.role' => 'required|in:user,admin,superadmin',
        'showUser.bio' => 'nullable|string',
        'showUser.avatar_url' => 'nullable|url',
        'showUser.is_active' => 'boolean',
    ]);

    $user = User::findOrFail($this->showUser['id']);

    $user->update([
        'name' => $this->showUser['name'],
        'organization' => $this->showUser['organization'],
        'role' => $this->showUser['role'],
        'bio' => $this->showUser['bio'],
        'avatar_url' => $this->showUser['avatar_url'],
        'is_active' => $this->showUser['is_active'],
    ]);

    $this->reset('showUser');

    $this->modal('edit-user')->close();
    Toaster::success('User updated successfully!');
}


public function deleteUser()
{
    if ($this->deleteUserId) {
        User::findOrFail($this->deleteUserId)->delete();

      
        $this->reset(['name', 'email', 'password', 'showUser', 'deleteUserId']);

        $this->modal('delete-user')->close(); // use single modal
        Toaster::success('User deleted successfully!');
    }
}

public function confirmDelete(int $id)
{
    $this->deleteUserId = $id;
    $this->modal('delete-user')->show(); // single modal for all deletes
}




    public function render()
    {
            $manageUsers = User::where('id', '!=', auth()->id())
            ->whereNot('role', 'superadmin') 
            ->orderBy('created_at', 'desc')
            ->paginate(7);

        return view('livewire.admin.user.manage-users', [
            'manageUsers' => $manageUsers,
            
        ]);
    }
}
