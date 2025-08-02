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