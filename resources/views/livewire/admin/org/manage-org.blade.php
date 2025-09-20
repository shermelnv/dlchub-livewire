<div class="space-y-4 p-10">

    {{-- Header --}}
    <div class="relative grid grid-cols-2">
        <section class="flex gap-2">
            <flux:input 
            icon="magnifying-glass" 
            placeholder="Search user" 
            wire:model.live="search"
            />
        </section>
    <section class="flex justify-end items-center">
        <flux:modal.trigger name="create-org">
            <flux:button icon="plus">Create Org</flux:button>
        </flux:modal.trigger>
    </section>

        <flux:separator variant="subtle" class="mt-4 col-span-2" />
    </div>


    {{-- Table --}}
    <div class="overflow-x-auto border border-gray-200 dark:border-gray-700 rounded-xl shadow-sm">
        <table class="min-w-full table-auto divide-y divide-gray-200 dark:divide-gray-700 text-sm">
            <thead class="bg-gray-100 dark:bg-zinc-800 text-left text-gray-600 dark:text-gray-300 uppercase tracking-wide">
                <tr>
                    <th class="px-4 py-3">ID</th>
                    <th class="px-4 py-3">Name</th>
                    
                    <th class="px-4 py-3 text-center">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-zinc-900 divide-y divide-gray-200 dark:divide-zinc-700">
                @forelse ($manageOrgs as $manageOrg)
                    <tr class="hover:bg-gray-50 dark:hover:bg-zinc-800">
                        <td class="px-4 py-3 font-medium text-black dark:text-white">{{ $manageOrg->id }}</td>
                        
                            <td class="px-4 py-3 font-medium text-maroon-900 dark:text-rose-300">
                                <a href="{{ route('org.profile', ['orgId' => $manageOrg->id]) }}" wire:navigate >
                                    {{ $manageOrg->name }}
                                </a>
                            </td>
                        
                        <td class="px-4 py-3 text-center">
                            <flux:dropdown position="left">
                                <flux:button icon="ellipsis-horizontal" variant="ghost" />
                                <flux:menu>
                                    <a href="{{ route('org.profile', ['orgId' => $manageOrg->id]) }}" wire:navigate >
                                        <flux:menu.item icon="exclamation-circle">
                                            View Profile
                                        </flux:menu.item>
                                    </a>
                                    
                                    <flux:menu.item icon="pencil-square" wire:click="getOrg({{ $manageOrg->id }})">
                                        Edit Detail
                                    </flux:menu.item>
                                    <flux:menu.item icon="trash" variant="danger" wire:click="confirmDelete({{ $manageOrg->id }})">
                                        Delete
                                    </flux:menu.item>

                                </flux:menu>
                            </flux:dropdown>
                        </td>
                    </tr>
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
        {{ $manageOrgs->links() }}
    </div>

    {{-- CREATE USER --}}
    <flux:modal name="create-org" class="md:w-[40rem]">
        <form wire:submit.prevent="createOrg" >
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">Create New Org</flux:heading>
                <flux:text class="mt-2">Fill out the form to register a new user.</flux:text>
            </div>
            

            <flux:input label="Username" wire:model.defer="username" />
            <flux:input label="Full Name" wire:model.defer="name" />
            <flux:input label="Email" wire:model.defer="email" type="email"/>
    
            <div class="flex justify-end space-x-2 pt-4">
                <flux:modal.close>
                    <flux:button variant="ghost">Cancel</flux:button>
                </flux:modal.close>
                <flux:button type="submit">Create</flux:button>
            </div>
        </div>
        </form>
    </flux:modal>


    {{-- EDIT USER --}}
    <flux:modal name="edit-org" class="md:w-[40rem]">
        <form wire:submit.prevent="updateOrg">
    
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">Edit User</flux:heading>
                <flux:text class="mt-2">Update user information.</flux:text>
            </div>

            <flux:input label="Full Name" wire:model.defer="showOrg.name" />
            <flux:input label="Full Name" wire:model.defer="showOrg.email" readonly />

            <div class="flex justify-end space-x-2 pt-4">
                <flux:modal.close>
                    <flux:button variant="ghost">Cancel</flux:button>
                </flux:modal.close>
                <flux:button type="submit">Update</flux:button>
            </div>
        </div>       
        </form>
    </flux:modal>




    {{-- VIEW USER --}}
    <flux:modal name="view-org" class="md:w-[40rem]">
        @if ($showOrg)
            <div class="space-y-6">
                <flux:heading size="lg">User Details</flux:heading>
                <flux:text class="mt-2">This is a read-only view of the user profile.</flux:text>

                <flux:input label="Name" value="{{ $showOrg['name'] ?? '' }}" readonly />
                <flux:input label="Name" value="{{ $showOrg['email'] ?? '' }}" readonly />


                <div class="flex">
                    <flux:spacer />
                    <flux:modal.close>
                        <flux:button variant="ghost">Close</flux:button>
                    </flux:modal.close>
                </div>
            </div>
        @else
            <div class="p-6 text-center">
                <flux:icon.loading class="w-5 h-5 animate-spin text-primary mx-auto" />
                <flux:text class="mt-2">Loading user data...</flux:text>
            </div>
        @endif
    </flux:modal>



    <flux:modal name="delete-org" class="min-w-[22rem]">
    <form wire:submit.prevent="deleteOrg" class="space-y-6">
        <div>
            <flux:heading size="lg">Delete User?</flux:heading>
            <flux:text class="mt-2">
                <p>This will permanently delete this user.</p>
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


</div>
