    <flux:modal name="unban-user" class="min-w-[22rem]">
        <form wire:submit.prevent="unbanUser" class="space-y-6">
            <div>
                <flux:heading size="lg">unban User?</flux:heading>
                <flux:text class="mt-2">
                    <p>Lorem ipsum dolor sit amet consectetur.</p>
                </flux:text>
            </div>
            <div class="flex gap-2">
                <flux:spacer />
                <flux:modal.close>
                    <flux:button variant="ghost">Cancel</flux:button>
                </flux:modal.close>
                <flux:button type="submit">Unban</flux:button>
            </div>
        </form>
    </flux:modal>