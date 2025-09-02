<?php

namespace App\Livewire\Admin\Org;

use App\Models\Org;
use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Str;
use Livewire\WithPagination;
use Masmerise\Toaster\Toaster;
use App\Mail\UserAccountCreated;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class ManageOrg extends Component
{
    use WithPagination;



    public array $showOrg = [];
    public $name, $password, $email, $org;

    protected $paginationTheme = 'tailwind';

    public ?int $deleteOrgId = null;

    public function createOrg()
    {
         $password = Str::random(10);

        $user = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($password),
            'role' => 'org',
            'status' => 'approved',
        ]);

        Mail::to($user->email)->send(new UserAccountCreated($user, $password));

        $this->reset('name');
        $this->modal('create-org')->close();
        Toaster::success('Organization created successfully!');
    }

    public function viewOrg($id)
    {
        $this->showOrg = User::findOrFail($id)->toArray();
        $this->modal('view-org')->show();
    }

    public function getOrg($id)
    {
        $this->showOrg = User::findOrFail($id)->toArray();
        $this->modal('edit-org')->show();
    }

    public function updateOrg()
    {
        $org = User::findOrFail($this->showOrg['id']);

        $org->update([
            'name' => $this->showOrg['name'],
        ]);

        $this->reset('showOrg');
        $this->modal('edit-org')->close();
        Toaster::success('Organization updated successfully!');
    }

    public function deleteOrg()
    {


        if ($this->deleteOrgId) {
            User::findOrFail($this->deleteOrgId)->delete();

            $this->reset(['name', 'showOrg', 'deleteOrgId']);
            $this->modal('delete-org')->close();
            Toaster::success('Organization deleted successfully!');
        }


    }

    public function confirmDelete(int $id)
    {
        $this->deleteOrgId = $id;

        $this->modal('delete-org')->show();
    }

    public function render()
    {
        // $manageOrgs = User::orderBy('created_at', 'desc')->paginate(7);
        $manageOrgs = User::where('role', 'org')
        ->orderBy('created_at', 'asc')->paginate(7);

        return view('livewire.admin.org.manage-org', [
            'manageOrgs' => $manageOrgs,
        ]);
    }
}
