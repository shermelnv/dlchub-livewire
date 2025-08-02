<flux:modal name="post-feed">
    <form wire:submit.prevent="createPost">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">Create Feed Post</flux:heading>
                <flux:text class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                    Share an announcement, event, or important update.
                </flux:text>
            </div>

            <div class="flex flex-col gap-4">
    <flux:input label="Post Title" wire:model.defer="title" placeholder="Post Title" />
    <flux:textarea label="Post Content" wire:model.defer="content" placeholder="What's on your mind? (Max 2000 Characters)" />

    <div>
        
            <div class="flex items-center justify-between">
                <flux:label class="p-2">Image</flux:label>
                @if ($photo)
                    <flux:modal.trigger name="preview-image">
                        <flux:button size="sm" variant="subtle">Preview</flux:button>
                    </flux:modal.trigger>
                @endif
            </div>
        
        <flux:input type="file" wire:model="photo" accept="image/*" />
        @if ($photo)
            <flux:modal name="preview-image">
                <flux:heading>Preview Image</flux:heading>
                <div class="p-4">
                    <img src="{{ $photo->temporaryUrl() }}" alt="Uploaded preview" class="h-64 w-full object-cover rounded-xl shadow border border-gray-300 dark:border-zinc-700" />
                </div>
            </flux:modal>
        @endif
    </div>

    <div class="grid grid-cols-2 gap-4">
        <flux:input type="text" label="Type" wire:model.defer="type" placeholder="ex. Event, Announcement, etc." autocomplete="off" />
        <flux:select label="Organization" wire:model.defer="organization" placeholder="Organization">
            <flux:select.option selected>All</flux:select.option>
            @foreach ($orgs as $org)
                <flux:select.option value="{{ $org->name }}">{{ $org->name }}</flux:select.option>
            @endforeach
        </flux:select>
    </div>
</div>


            <div class="flex justify-end gap-2">
                <flux:modal.close>
                    <flux:button variant="ghost">Cancel</flux:button>
                </flux:modal.close>
                <flux:button type="submit" variant="primary">Post</flux:button>
            </div>
        </div>
    </form>
</flux:modal>
