    <flux:modal name="approve-user" class="min-w-[22rem]">
        <form wire:submit.prevent="approveUser" class="space-y-6">
            <div>
                <flux:heading size="lg">Approve User?</flux:heading>
                <flux:text class="mt-2">
                    <p>Are you sure you want to approve this user?</p>
                </flux:text>
            </div>
            <div class="flex gap-2">
                <flux:spacer />
                <flux:modal.close>
                    <flux:button variant="ghost">Cancel</flux:button>
                </flux:modal.close>
                <flux:button type="submit" >Approve</flux:button>
            </div>
        </form>
    </flux:modal>