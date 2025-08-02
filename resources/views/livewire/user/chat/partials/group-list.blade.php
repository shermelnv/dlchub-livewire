<flux:heading size="md">Groups</flux:heading>

            <div class="space-y-2">
                @foreach ($groups as $group)
                    <div wire:click="openGroup('{{ $group->group_code }}')" class="cursor-pointer p-3 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-800 {{ $selectedGroup && $group->id === $selectedGroup->id ? 'bg-gray-100 dark:bg-gray-800' : '' }}">
                        <div class="font-semibold text-gray-800 dark:text-white">{{ $group->name }}</div>
                        <div class="text-sm text-gray-500 dark:text-gray-400 truncate">{{ $group->description }}</div>
                    </div>
                @endforeach
            </div>

            <!-- Create / Join Group Buttons -->
            <div class="space-y-2 pt-2">
                <flux:modal.trigger name="create-group">
                    <flux:button icon-leading="plus" size="sm" class="w-full">
                        Create Group
                    </flux:button>
                </flux:modal.trigger>

                <flux:modal.trigger name="join-group">
                    <flux:button icon-leading="arrow-right-end-on-rectangle" variant="outline" size="sm" class="w-full">
                        Join Group
                    </flux:button>
                </flux:modal.trigger>
            </div>