<?php

namespace App\Livewire\Admin\Org;

use App\Models\Org;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Masmerise\Toaster\Toaster;

class ManageOrg extends Component
{
    use WithPagination;



    public array $showOrg = [];
    public $name, $password, $email, $org;

    protected $paginationTheme = 'tailwind';

    public ?int $deleteOrgId = null;

    public function createOrg()
    {

        $org = Org::create([
            'name' => $this->name,
        ]);

        User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => bcrypt($this->password),

            // use explode to remove the '@ up to the end of the email' in the password then add 'password' to the end
            'password' => bcrypt(explode('@', $this->email)[0] . 'password'),
            'role' => 'org',
            'status' => 'approved',
        ]);

        $this->reset('name');
        $this->modal('create-org')->close();
        Toaster::success('Organization created successfully!');
    }

    public function viewOrg($id)
    {
        $this->showOrg = Org::findOrFail($id)->toArray();
        $this->modal('view-org')->show();
    }

    public function getOrg($id)
    {
        $this->showOrg = Org::findOrFail($id)->toArray();
        $this->modal('edit-org')->show();
    }

    public function updateOrg()
    {
        $org = Org::findOrFail($this->showOrg['id']);

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
            Org::findOrFail($this->deleteOrgId)->delete();

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
        $manageOrgs = Org::orderBy('created_at', 'desc')->paginate(7);

        return view('livewire.admin.org.manage-org', [
            'manageOrgs' => $manageOrgs,
        ]);
    }
}
