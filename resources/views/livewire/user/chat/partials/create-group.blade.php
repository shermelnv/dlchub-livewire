<flux:modal name="create-group" class="w-xs lg:w-full" :closable="false">
    <div x-data="{ tab: 'create' }" class="w-full h-auto grid gap-6">
        <!-- Tab Buttons -->
        <div class="grid grid-cols-2 gap-2">
            <button
                @click="tab = 'create'"
                :class="tab === 'create' ? 'bg-red-900 text-white' : 'bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-white'"
                class="py-2 rounded-md text-sm font-medium transition"
            >
                Create Group
            </button>
            <button
                @click="tab = 'join'"
                :class="tab === 'join' ? 'bg-red-900 text-white' : 'bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-white'"
                class="py-2 rounded-md text-sm font-medium transition"
            >
                Join Group
            </button>
        </div>

        <!-- Create Group Form -->
        <div x-show="tab === 'create'"  >
            <form wire:submit.prevent="createGroup">
                <div class="space-y-6">
                    <flux:input wire:model.defer="newGroupName" label="Group Name" placeholder="Enter group name" />
                    <flux:textarea wire:model.defer="newGroupDescription" label="Description" placeholder="Enter group description (optional)" />
                    <div class="flex justify-end gap-4">
                        <flux:modal.close>
                            <flux:button variant="ghost" size="sm">Close</flux:button>
                        </flux:modal.close>
                        
                        <flux:button type="submit" size="sm">Create</flux:button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Join Group Form -->
        <div x-show="tab === 'join'"  >
            <form wire:submit.prevent="joinGroup">
                <div class="space-y-6">
                    <flux:input wire:model.defer="groupCode" label="Group ID" placeholder="Enter Group ID" />
                    <div class="flex justify-end gap-4">
                        <flux:modal.close>
                            <flux:button variant="ghost" size="sm">Close</flux:button>
                        </flux:modal.close>
                        <flux:button type="submit" size="sm">Join</flux:button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</flux:modal>
