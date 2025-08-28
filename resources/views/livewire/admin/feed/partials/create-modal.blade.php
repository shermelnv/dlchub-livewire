<flux:modal name="post-feed" :dismissible="false" class="w-sm md:w-lg">
    <form wire:submit.prevent="createPost" enctype="multipart/form-data">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">Create Feed Post</flux:heading>
                <flux:text class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                    Share an announcement, event, or important update.
                </flux:text>
            </div>

            <flux:input label="Post Title" wire:model.defer="title" placeholder="Post Title" />


    
    <flux:textarea label="Post Content" wire:model.defer="content" placeholder="What's on your mind? (Max 2000 Characters)" />

                <div class="flex flex-col gap-4">

                    @if(auth()->user()->role === 'org')
                    <div class="grid grid-cols-2 gap-4">
                        <flux:select label="Privacy" wire:model.defer="privacy" placeholder="Public / Private">
                            <flux:select.option value="public">Public</flux:select.option>
                            <flux:select.option value="private">Private</flux:select.option>
                        </flux:select>
                        <flux:input type="text" label="Type" wire:model.defer="type" placeholder="ex. Event, Announcement, etc." autocomplete="off" />
                    </div>
                    @endif
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
        @error('photo')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div class="grid  gap-4">
        

    </div>
</div>


            <div class="flex justify-end gap-2">
                <flux:modal.close>
                    <flux:button variant="ghost">Cancel</flux:button>
                </flux:modal.close>
                <flux:button 
                    type="submit" 
                    variant="primary"
                    wire:loading.attr="disabled" 
                    wire:target="photo">
                    <span wire:loading.remove wire:target="photo">Post</span>
                    <span wire:loading wire:target="photo">Uploading...</span>
                </flux:button>

            </div>
        </div>
    </form>
</flux:modal>
