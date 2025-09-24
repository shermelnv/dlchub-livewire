<flux:modal name="edit-post">
    <form wire:submit.prevent="updatePost">
        <div class="space-y-6">
            <!-- Header -->
            <div>
                <flux:heading size="lg">Edit Feed Post</flux:heading>
                <flux:text class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                    Update your announcement or event post.
                </flux:text>
            </div>

            <!-- Fields -->
            <div class="flex flex-col gap-4">
                <flux:input
                    label="Post Title"
                    wire:model.defer="showPost.title"
                    placeholder="Post Title"
                />

                <flux:textarea
                    label="Post Content"
                    wire:model.defer="showPost.content"
                    placeholder="What's on your mind? (Max 2000 Characters)"
                />

                <!-- Image Upload -->
                <div class="grid grid-cols-2 gap-4">
                    <flux:field>
                        <div class="flex items-center justify-between">
                            <flux:label badge="Optional">Image</flux:label>

                            @if ($photo)
                                <flux:modal.trigger name="preview-feed-photo">
                                    <flux:button size="xs" variant="outline">Preview</flux:button>
                                </flux:modal.trigger>
                            @endif
                        </div>

                        <input
                            type="file"
                            wire:model="photo"
                            accept="image/*"
                            class="mt-2 max-w-[15rem] block border border-gray-300 rounded-md p-2 text-sm"
                        />

                        @error('photo')
                            <p class="text-red-500 text-xs">{{ $message }}</p>
                        @enderror
                    </flux:field>
                    
                 
               
                    <flux:input
                        type="text"
                        label="Type"
                        wire:model.defer="showPost.type"
                        placeholder="ex. Event, Announcement, etc."
                        autocomplete="off"
                    />

               
                    
                </div>

   @if(auth()->user()->role === 'org')
                    <flux:select label="Privacy" wire:model.defer="showPost.privacy" placeholder="Public / Private">
                        <flux:select.option value="public">Public</flux:select.option>
                        <flux:select.option value="private">Private</flux:select.option>
                    </flux:select>
                    @endif



            </div>

            <!-- Actions -->
            <div class="flex justify-end gap-2">
                <flux:modal.close>
                    <flux:button variant="ghost">Cancel</flux:button>
                </flux:modal.close>
                <flux:button 
                    type="submit" 
                    variant="primary"
                    wire:loading.attr="disabled" 
                    wire:target="photo"
                    >
                    Update</flux:button>
            </div>
        </div>
    </form>
</flux:modal>

    <!-- Single Image Preview Modal -->
    <flux:modal name="preview-feed-photo" class="md:w-[30rem]">
        <div class="space-y-4">
            <div class="flex justify-between items-center">
                <flux:heading>Preview Image</flux:heading>

            </div>

            @if ($photo)
                <img
                    src="{{ $photo->temporaryUrl() }}"
                    alt="Uploaded preview"
                    class="w-full h-64 object-cover rounded-xl shadow border border-gray-300 dark:border-zinc-700"
                />
            @endif
        </div>
    </flux:modal>


<flux:modal name="edit-comment" class="md:w-96">
    <form wire:submit.prevent="updateComment" class="space-y-6">
        <div>
            <flux:heading size="lg">Edit Comment</flux:heading>
            <flux:text class="mt-2">Make changes to your comment.</flux:text>
        </div>
        <div>
            <flux:textarea
                label="Comment"
                wire:model.defer="showComment.comment"
                placeholder="Edit your comment..."
            />
        </div>
        <div class="flex">
            <flux:spacer />
            <flux:button type="submit" variant="primary">Save</flux:button>
        </div>
    </form>
</flux:modal>
