<div 
    x-data
    x-init="Echo.channel('manage-user')
                .listen('.user.registered', (e) => {
                    console.log('received', e.user);
                    Livewire.dispatch('newUser', { user: e.user });
                });"
    class="space-y-4 p-10">

    {{-- Header --}}
    <div class="relative grid grid-cols-2">

        <div class="flex gap-2">
            <flux:input 
            icon="magnifying-glass" 
            placeholder="Search user" 
            wire:model.live="search"
            />
 <flux:dropdown>
        <flux:button icon:trailing="chevron-down">
            Status
        </flux:button>

        <flux:menu>
            <flux:menu.radio.group wire:model.live="status">
                <flux:menu.radio value="all">All</flux:menu.radio>
                <flux:menu.radio value="pending">Pending</flux:menu.radio>
                <flux:menu.radio value="approved">Approved</flux:menu.radio>
                <flux:menu.radio value="rejected">Rejected</flux:menu.radio>
                <flux:menu.radio value="banned">Banned</flux:menu.radio>
            </flux:menu.radio.group>
        </flux:menu>
    </flux:dropdown>
        </div>
    
    

    <section class="flex justify-end items-center gap-4">
        <flux:modal.trigger name="create-user">
            <flux:button icon="plus">Create User</flux:button>
        </flux:modal.trigger>
    </section>

    </div>
<flux:separator variant="subtle" class="mt-4 col-span-2" />

    {{-- Table --}}
    <div class="overflow-x-auto border border-gray-200 dark:border-gray-700 rounded-xl shadow-sm">
        <table class="min-w-full table-auto divide-y divide-gray-200 dark:divide-gray-700 text-sm">
            <thead class="bg-gray-100 dark:bg-zinc-800 text-left text-gray-600 dark:text-gray-300 uppercase tracking-wide">
                <tr>
        
                    <th class="px-4 py-3">Username</th>
                    <th class="px-4 py-3">Name</th>
                    <th class="px-4 py-3">Email</th>

                    <th class="px-4 py-3">Status</th>
                                        <th class="px-4 py-3">Role</th>
                    <th class="px-4 py-3 text-center">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-zinc-900 divide-y divide-gray-200 dark:divide-zinc-700">
                @forelse ($manageUsers as $manageUser)
                    <tr class="hover:bg-gray-50 dark:hover:bg-zinc-800">
                        <td class="px-4 py-3 font-medium text-black dark:text-white">{{ $manageUser->username }}</td>
                        <td class="px-4 py-3 font-medium text-maroon-900 dark:text-rose-300">{{ $manageUser->name }}</td>
                        <td class="px-4 py-3 text-gray-700 dark:text-gray-400">{{ $manageUser->email }}</td>

                        
                        <td class="px-4 py-3 text-gray-700 dark:text-gray-400">

                            @if($manageUser->status === 'pending')
                                <flux:button size="sm" wire:click="confirmApprove({{ $manageUser->id }})">
                                Approve
                                </flux:button>
                            @else
                                {{ ucfirst($manageUser->status) }}
                            @endif
                        </td>


                        <td class="px-4 py-3 text-gray-700 dark:text-gray-400">
                            <flux:select 
                                size="sm" 
                                wire:model="roles.{{ $manageUser->id }}" 
                                wire:change="updateRole({{ $manageUser->id }})"
                            >
                                <flux:select.option value="user">User</flux:select.option>
                                <flux:select.option value="org">Org</flux:select.option>
                                <flux:select.option value="admin">Admin</flux:select.option>
                            </flux:select>
                        </td>


                        {{-- MENU --}}
                        <td class="px-4 py-3 text-center">
                            <flux:dropdown position="left">
                                <flux:button icon="ellipsis-horizontal" variant="ghost" />
                                    <flux:menu>
                                        
                                        {{-- PROFILE MANAGEMENT --}}
                                        <flux:menu.group heading="Profile Management">
                                            <flux:menu.item icon="exclamation-circle" wire:click="viewUser({{ $manageUser->id }})">
                                                View Detail
                                            </flux:menu.item>
                                            <flux:menu.item icon="pencil-square" wire:click="getUser({{ $manageUser->id }})">
                                                Edit Detail
                                            </flux:menu.item>
                                        </flux:menu.group>

                                        {{--  ACCOUT  REVIEW --}}
                                        @if($manageUser->status === 'pending')
                                        <flux:menu.group heading="Account Review">
                                            <flux:menu.item icon="check" wire:click="confirmApprove({{ $manageUser->id }})">
                                                Approve
                                            </flux:menu.item>
                                            <flux:menu.item icon="x-mark" wire:click="confirmReject({{ $manageUser->id }})">
                                                Reject
                                            </flux:menu.item>
                                        </flux:menu.group>
                                        @endif
                                        
                                        {{-- ACCOUNT CONTROL --}}
                                        @if($manageUser->status !== 'pending')
                                        <flux:menu.group heading="Account Control">
                                            @if(in_array($manageUser->status, ['approved']))
                                            <flux:menu.item icon="no-symbol"  wire:click="confirmBan({{ $manageUser->id }})">
                                                Ban
                                            </flux:menu.item>
                                            @elseif(in_array($manageUser->status, ['banned']))
                                            <flux:menu.item icon="no-symbol"  wire:click="confirmUnban({{ $manageUser->id }})">
                                                Unban
                                            </flux:menu.item>
                                            @else
                                            <flux:menu.item icon="check"  wire:click="confirmApprove({{ $manageUser->id }})">
                                                Approve
                                            </flux:menu.item>
                                            @endif
                                            <flux:menu.item icon="trash" variant="danger" wire:click="confirmDelete({{ $manageUser->id }})">
                                                Delete
                                            </flux:menu.item>
                                        </flux:menu.group>
                                        @endif  
                                        
                                    </flux:menu>
                            </flux:dropdown>
                        </td>
                    </tr>

                    {{-- Delete Modal --}}
                    <flux:modal :name="'delete-user-' . $manageUser->id" class="min-w-[22rem]">
                        <form wire:submit.prevent="deleteUser({{ $manageUser->id }})" class="space-y-6">
                            <div>
                                <flux:heading size="lg">Delete User?</flux:heading>
                                <flux:text class="mt-2">
                                    <p>This will permanently delete <strong>{{ $manageUser->name }}</strong>.</p>
                                    <p class="text-red-500">This action cannot be undone.</p>
                                </flux:text>
                            </div>
                            <div class="flex gap-2">
                                <flux:spacer />
                                <flux:modal.close>
                                    <flux:button variant="ghost">Cancel</flux:button>
                                </flux:modal.close>
                                <flux:button type="submit" variant="danger">Delete</flux:button>
                            </div>
                        </form>
                    </flux:modal>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-6 text-center text-gray-500">No users found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="mt-4">

    
        {{ $manageUsers->links() }}
    
    </div>


    {{-- ========= CREATE USER =========== --}}
    @include('livewire.admin.user.partials.create-user')

    {{-- ========= VIEW USER =========== --}}
    @include('livewire.admin.user.partials.view-user')

    {{-- ========= EDIT USER =========== --}}
    @include('livewire.admin.user.partials.edit-user')



    {{-- ========= DELETE USER =========== --}}
    @include('livewire.admin.user.partials.delete-user')

    {{-- ========= APPROVE USER =========== --}}
    @include('livewire.admin.user.partials.approve-user')

    {{-- ========= REJECT USER =========== --}}
    @include('livewire.admin.user.partials.reject-user')

    {{-- ========= BAN USER =========== --}}
    @include('livewire.admin.user.partials.ban-user')

    {{-- ========= UNBAN USER =========== --}}
    @include('livewire.admin.user.partials.unban-user')



</div>
