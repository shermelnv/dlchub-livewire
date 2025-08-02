<flux:modal name="deletePost" class="min-w-[22rem]">
        <form wire:submit.prevent="deletePost" class="space-y-6">
            <div>
                <flux:heading size="lg">Delete Post?</flux:heading>
                <flux:text class="mt-2">
                    <p>You're about to delete this post.</p>
                    <p>This action cannot be reversed.</p>
                </flux:text>
            </div>
            <div class="flex gap-2">
                <flux:spacer />
                <flux:modal.close>
                    <flux:button variant="ghost">Cancel</flux:button>
                </flux:modal.close>
                <flux:button type="submit" variant="danger">Delete Post</flux:button>
            </div>
        </form>
    </flux:modal>