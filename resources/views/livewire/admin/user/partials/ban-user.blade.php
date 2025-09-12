    <flux:modal name="ban-user" class="min-w-[22rem]">
        <form wire:submit.prevent="banUser" class="space-y-6">
            <div>
                <flux:heading size="lg">Ban User?</flux:heading>
                <flux:text class="mt-2">
                    <p>Are you sure you want to ban this user?</p>
                </flux:text>
            </div>
            <div class="flex gap-2">
                <flux:spacer />
                <flux:modal.close>
                    <flux:button variant="ghost">Cancel</flux:button>
                </flux:modal.close>
                <flux:button type="submit">Ban</flux:button>
            </div>
        </form>
    </flux:modal>