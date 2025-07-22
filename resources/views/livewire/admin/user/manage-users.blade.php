<div class="space-y-8">

    {{-- Header --}}
    <div class="relative grid grid-cols-2">
    <section>        
        <flux:heading size="xl" level="1">Manage Users</flux:heading>
        <flux:subheading size="lg" class="mt-2 text-gray-600 dark:text-gray-400">
            Manage your user accounts, edit details or remove users as needed.
        </flux:subheading>
    </section>
    <section class="flex justify-end items-center">
        <flux:modal.trigger name="create-user">
            <flux:button icon="plus">Create User</flux:button>
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
                    <th class="px-4 py-3">Email</th>
                    <th class="px-4 py-3">Role</th>
                    <th class="px-4 py-3">Org</th>
                    <th class="px-4 py-3 text-center">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-zinc-900 divide-y divide-gray-200 dark:divide-zinc-700">
                @forelse ($manageUsers as $manageUser)
                    <tr class="hover:bg-gray-50 dark:hover:bg-zinc-800">
                        <td class="px-4 py-3 font-medium text-black dark:text-white">{{ $manageUser->id }}</td>
                        <td class="px-4 py-3 font-medium text-maroon-900 dark:text-rose-300">{{ $manageUser->name }}</td>
                        <td class="px-4 py-3 text-gray-700 dark:text-gray-400">{{ $manageUser->email }}</td>
                        <td class="px-4 py-3 text-gray-700 dark:text-gray-400">{{ $manageUser->role ?? 'User' }}</td>
                        <td class="px-4 py-3 text-gray-700 dark:text-gray-400">{{ $manageUser->organization ?? 'No Org' }}</td>
                        <td class="px-4 py-3 text-center">
                            <flux:dropdown position="left">
                                <flux:button icon="ellipsis-horizontal" variant="ghost" />
                                <flux:menu>
                                    <flux:menu.item icon="exclamation-circle" wire:click="viewUser({{ $manageUser->id }})">
                                        View Detail
                                    </flux:menu.item>
                                    <flux:menu.item icon="pencil-square" wire:click="getUser({{ $manageUser->id }})">
                                        Edit Detail
                                    </flux:menu.item>
                                    <flux:menu.item icon="trash" variant="danger" wire:click="confirmDelete({{ $manageUser->id }})">
                                        Delete
                                    </flux:menu.item>

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

    {{-- CREATE USER --}}
<flux:modal name="create-user" class="md:w-[40rem]">
    <div class="space-y-6">
        <div>
            <flux:heading size="lg">Create New User</flux:heading>
            <flux:text class="mt-2">Fill out the form to register a new user.</flux:text>
        </div>

        
        <flux:input label="Full Name" wire:model.defer="name" />
        <flux:input label="Email" type="email" wire:model.defer="email" />
        <flux:input label="Password" type="password" wire:model.defer="password" />
        <flux:input label="Organization" wire:model.defer="organization" />
        
        
        <flux:select label="Role" wire:model.defer="role">
            <flux:select.option selected>Select Role</flux:select.option>
            <flux:select.option value="user">User</flux:select.option>
            <flux:select.option value="admin">Admin</flux:select.option>
            <flux:select.option value="superadmin">Superadmin</flux:select.option>
        </flux:select>

        {{-- <flux:toggle label="Active?" wire:model.defer="is_active" /> --}}

        <div class="flex justify-end space-x-2 pt-4">
            <flux:button variant="ghost" @click="$modal('create-user').close()">Cancel</flux:button>
            <flux:button wire:click="createUser" spinner="createUser">Create</flux:button>
        </div>
    </div>
</flux:modal>


{{-- EDIT USER --}}
<flux:modal name="edit-user" class="md:w-[40rem]">
    <div class="space-y-6">
        <div>
            <flux:heading size="lg">Edit User</flux:heading>
            <flux:text class="mt-2">Update user information.</flux:text>
        </div>

        <flux:input label="Full Name" wire:model.defer="showUser.name" />
        <flux:input label="Email" wire:model.defer="showUser.email" disabled />
        <flux:input label="Organization" wire:model.defer="showUser.organization" />
        
        <flux:select label="Role" wire:model.defer="showUser.role">
            <option value="user">User</option>
            <option value="admin">Admin</option>
            <option value="superadmin">Superadmin</option>
        </flux:select>

        {{-- <flux:toggle label="Active?" wire:model.defer="showUser.is_active" /> --}}

        <div class="flex justify-end space-x-2 pt-4">
            <flux:button variant="ghost" @click="$modal('edit-user').close()">Cancel</flux:button>
            <flux:button wire:click="updateUser" spinner="updateUser">Update</flux:button>
        </div>
    </div>
</flux:modal>



{{-- VIEW USER --}}
<flux:modal name="view-user" class="md:w-[40rem]">
    @if ($showUser)
        <div class="space-y-6">
            <flux:heading size="lg">User Details</flux:heading>
            <flux:text class="mt-2">This is a read-only view of the user profile.</flux:text>

            <flux:input label="Name" value="{{ $showUser['name'] ?? '' }}" readonly />
            <flux:input label="Email" value="{{ $showUser['email'] ?? '' }}" readonly />
            <flux:input label="Organization" value="{{ $showUser['organization'] ?? '' }}" readonly />
            <flux:input label="Role" value="{{ ucfirst($showUser['role'] ?? 'user') }}" readonly />
            <flux:input label="Active Status" value="{{ $showUser['is_active'] ? 'Active' : 'Inactive' }}" readonly />
            @if (!empty($showUser['bio']))
                <flux:textarea label="Bio" rows="2" readonly>
                    {{ $showUser['bio'] }}
                </flux:textarea>
            @endif

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



    <flux:modal name="delete-user" class="min-w-[22rem]">
    <form wire:submit.prevent="deleteUser" class="space-y-6">
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
