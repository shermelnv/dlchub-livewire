<flux:modal name="edit-user" class="md:w-[40rem]">
    <div class="space-y-6">
        <div>
            <flux:heading size="lg">Edit User</flux:heading>
            <flux:text class="mt-2">Update user information.</flux:text>
        </div>

        <flux:input label="Full Name" wire:model.defer="showUser.name" />
        <flux:input label="Email" wire:model.defer="showUser.email" disabled />
   
        {{-- <flux:toggle label="Active?" wire:model.defer="showUser.is_active" /> --}}

        <div class="flex justify-end space-x-2 pt-4">
            <flux:modal.close>
                <flux:button variant="ghost">Cancel</flux:button>
            </flux:modal.close>
            <flux:button wire:click="updateUser" spinner="updateUser">Update</flux:button>
        </div>
    </div>
</flux:modal>