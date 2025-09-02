<flux:modal name="edit-group-info">
    <flux:heading>Edit Group Info</flux:heading>

    <div class="flex flex-col items-center gap-4 p-6">
        <!-- Group Photo -->
        <div class="size-40 rounded-full overflow-hidden border border-gray-300 dark:border-gray-700">
            @if ($group_image)
                <img src="{{ $group_image->temporaryUrl() }}" class="object-cover w-full h-full" />
            @elseif ($selectedGroup->group_image)
                <img src="{{ asset('storage/' . $selectedGroup->group_image) }}" class="object-cover w-full h-full" />
            @else
                <div class="flex items-center justify-center w-full h-full text-gray-400 text-sm">
                    No avatar
                </div>
            @endif
        </div>

        <!-- Change Photo Input -->
        <flux:input wire:model="group_image" type="file" label="Select Image" accept="image/*" />

        <!-- Group Name Input -->
        <div class="w-full">
            <label class="text-sm font-medium">Group Name</label>
            <flux:input wire:model="selectedGroup.name" type="text" placeholder="Enter new group name" class="w-full mt-1" />
        </div>

        <!-- Action Buttons -->
        <div class="flex justify-end gap-2 w-full pt-4">
            <flux:modal.close>
                <flux:button variant="ghost">Cancel</flux:button>
            </flux:modal.close>
            <flux:button variant="primary" wire:click="updateGroupInfo">Save</flux:button>
        </div>
    </div>
</flux:modal>
