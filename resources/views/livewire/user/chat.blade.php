<!-- Group Chat UI -->
<div class="flex flex-col h-[calc(100vh-4rem)]">

    <!-- Page Header -->
    <div>
        <flux:heading size="xl" level="1">
            {{ __('Group Chat') }}
        </flux:heading>

        <flux:subheading size="lg" class="mb-4">
            {{ __('Stay Updated with the latest news and important information from our university') }}
        </flux:subheading>

        <flux:separator variant="subtle" />
    </div>

    <!-- Chat Layout -->
    <div class="flex flex-1 overflow-hidden pb-4 gap-6">
        <!-- Sidebar -->
        <div class="w-full md:w-1/4 bg-white dark:bg-gray-900 rounded-2xl shadow p-4 space-y-4 overflow-y-auto">
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
        </div>

        <!-- Chat Panel -->
        <div class="w-full md:w-3/4 bg-white dark:bg-gray-900 rounded-2xl shadow flex flex-col overflow-hidden">
            @if ($selectedGroup)
                <!-- Chat Header -->
                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <div>
                        <div class="text-lg font-bold text-gray-800 dark:text-white">{{ $selectedGroup->name }}</div>
                        <div class="text-sm text-gray-500 dark:text-gray-400">{{ $selectedGroup->description }}</div>
                        <div class="text-sm text-gray-500 dark:text-gray-400">{{ $selectedGroup->group_code }}</div>
                    </div>
                    <flux:button icon="cog" variant="ghost" />
                </div>

                <!-- Messages -->
                <div class="flex-1 overflow-y-auto p-6 space-y-4">
                    @foreach ($messages as $message)
                        @if ($message->user_id === auth()->id())
                            <!-- Outgoing -->
                            <div class="flex gap-3 items-start justify-end">
                                <div class="bg-blue-100 dark:bg-blue-900 text-gray-900 dark:text-white p-3 rounded-xl max-w-md">
                                    <div class="text-sm font-medium">You</div>
                                    <div class="text-sm">{{ $message->message }}</div>
                                    <div class="text-xs text-gray-500 mt-1 text-right">{{ $message->created_at->diffForHumans() }}</div>
                                </div>
                                <img src="{{ $message->user->avatar_url ?? 'https://i.pravatar.cc/100?u=' . $message->user_id }}" class="w-9 h-9 rounded-full" />
                            </div>
                        @else
                            <!-- Incoming -->
                            <div class="flex gap-3 items-start">
                                <img src="{{ $message->user->avatar_url ?? 'https://i.pravatar.cc/100?u=' . $message->user_id }}" class="w-9 h-9 rounded-full" />
                                <div class="bg-gray-100 dark:bg-gray-800 p-3 rounded-xl max-w-md">
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $message->user->name }}</div>
                                    <div class="text-sm text-gray-700 dark:text-gray-300">{{ $message->message }}</div>
                                    <div class="text-xs text-gray-500 mt-1">{{ $message->created_at->diffForHumans() }}</div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>

                <!-- Message Input -->
                <div class="border-t border-gray-200 dark:border-gray-700 p-4">
                    <form wire:submit.prevent="sendMessage" class="flex items-center gap-2">
                        <flux:input wire:model.defer="messageInput" placeholder="Type your message..." class="flex-1" />
                        <flux:button type="submit" icon="arrow-right" class="size-10" />
                    </form>
                </div>
            @else
                <div class="flex-1 flex items-center justify-center text-gray-400 dark:text-gray-500">
                    Select a group to start chatting
                </div>
            @endif
        </div>
    </div>

    <!-- Create Group Modal -->
    <flux:modal name="create-group" class="w-full max-w-md">
        <form wire:submit.prevent="createGroup">
            <div class="space-y-6">
                <flux:heading size="lg">Create Group</flux:heading>
                <flux:input wire:model.defer="newGroupName" label="Group Name" placeholder="Enter group name" />
                <flux:textarea wire:model.defer="newGroupDescription" label="Description" placeholder="Enter group description (optional)" />
                <div class="flex justify-end">
                    <flux:button type="submit" size="sm">Create</flux:button>
                </div>
            </div>
        </form>
    </flux:modal>

    <!-- Join Group Modal -->
    <flux:modal name="join-group" class="w-full max-w-md">
        <form wire:submit.prevent="joinGroup">
            <div class="space-y-6">
                <flux:heading size="lg">Join Group</flux:heading>
                <flux:input wire:model.defer="groupCode" label="Group ID" placeholder="Enter Group ID" />
                <div class="flex justify-end">
                    <flux:button type="submit" size="sm">Join</flux:button>
                </div>
            </div>
        </form>
    </flux:modal>

</div>
