<flux:modal name="delete-advertisement" class="min-w-[22rem]">
    <div class="space-y-6">
        <div>
            <flux:heading size="lg">Delete Advertisement?</flux:heading>

            <flux:text class="mt-2">
                <p>You're about to delete this advertisement.</p>
                <p>This action cannot be undone.</p>
            </flux:text>
        </div>

        <div class="flex gap-2">
            <flux:spacer />

            <flux:modal.close>
                <flux:button variant="ghost">Cancel</flux:button>
            </flux:modal.close>

            <form wire:submit.prevent="deleteAdvertisement">
                <flux:button type="submit" variant="danger">
                    Delete Advertisement
                </flux:button>
            </form>
        </div>
    </div>
</flux:modal>
