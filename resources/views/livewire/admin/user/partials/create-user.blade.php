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
                <flux:select.option value="org">Org</flux:select.option>
                <flux:select.option value="admin">Admin</flux:select.option>
                <flux:select.option value="superadmin">Superadmin</flux:select.option>
            </flux:select>

            {{-- <flux:toggle label="Active?" wire:model.defer="is_active" /> --}}

            <div class="flex justify-end space-x-2 pt-4">
                <flux:modal.close>
                <flux:button variant="ghost">Cancel</flux:button>
            </flux:modal.close>
                <flux:button wire:click="createUser" spinner="createUser">Create</flux:button>
            </div>
        </div>
    </flux:modal>