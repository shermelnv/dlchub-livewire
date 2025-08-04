<!-- Group Chat UI -->
<div
    x-data
    x-init="
        $nextTick(() => {
            const chatBox = document.querySelector('.chat-messages');
            if (chatBox) {
                chatBox.scrollTop = chatBox.scrollHeight;
            }
        });

        window.addEventListener('scroll-to-bottom', () => {
            requestAnimationFrame(() => {
                const chatBox = document.querySelector('.chat-messages');
                if (chatBox) {
                    chatBox.scrollTop = chatBox.scrollHeight;
                }
            });
        });

        const groupId = @js($selectedGroup?->id);
        if (!groupId) return;

        Echo.private(`chat.${groupId}`)
            .listen('.MessageSent', (e) => {
                console.log('Message received:', e);
                Livewire.dispatch('message-received', e); 
            });
    "
    class="flex flex-col h-[calc(100vh-4rem)]"
>
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
            @include('livewire.user.chat.partials.group-list')
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
                    
                    <flux:modal.trigger name="group-settings">
                        <flux:button icon="cog" variant="ghost" />
                    </flux:modal.trigger>
                </div>

                <!-- Messages -->
                <div class="flex-1 overflow-y-auto p-6 space-y-4 chat-messages">
                    @php
                        $lastTimestamp = null;
                        $lastUserId = null;
                        $lastDate = null;
                    @endphp

                    @foreach ($messages as $index => $message)
                        @php
                            $msg = (object) $message;
                            $currentTimestamp = \Carbon\Carbon::parse($msg->created_at);
                            $currentDate = $currentTimestamp->toDateString();
                            $nextMessage = $messages[$index + 1] ?? null;

                            $sameUserAsPrevious = $lastUserId === $msg->user_id;
                            $sameUserAsNext = $nextMessage && $nextMessage['user_id'] === $msg->user_id;
                            $isLastInBlock = !$sameUserAsNext;

                            $lastUserId = $msg->user_id;
                        @endphp

                        {{-- Date Header --}}
                        @if ($lastDate !== $currentDate)
                            @php $lastDate = $currentDate; @endphp
                            <div class="text-center text-xs text-gray-400 dark:text-gray-500 my-4">
                                @if ($currentTimestamp->isToday())
                                    Today
                                @elseif ($currentTimestamp->isYesterday())
                                    Yesterday
                                @else
                                    {{ $currentTimestamp->format('F j, Y') }}
                                @endif
                            </div>
                        @endif

                        {{-- Message Row --}}
                        <div class="flex gap-3 items-end {{ $msg->user_id === auth()->id() ? 'justify-end' : '' }}">
                            {{-- Avatar (left for others) --}}
                            @if ($profileImage)
                                <img src="{{ $profileImage->temporaryUrl() }}" class="w-24 h-24 rounded-full object-cover" />
                            @elseif (auth()->user()->profile_image)
                                <img src="{{ asset('storage/' . auth()->user()->profile_image) }}" class="w-24 h-24 rounded-full object-cover" />
                            @else
                                <img src="https://i.pravatar.cc/100?u={{ auth()->id() }}" class="w-24 h-24 rounded-full object-cover" />
                            @endif


                            {{-- Message bubble --}}
                            <div>
                                @unless($sameUserAsPrevious)
                                    <div class="text-xs text-gray-400 dark:text-gray-500 mb-1 {{ $msg->user_id === auth()->id() ? 'text-right pr-1' : 'text-left pl-1' }}">
                                        {{ $msg->user_id === auth()->id() ? 'You' : $msg->user['name'] }}
                                    </div>
                                @endunless

                                <div class="{{ $msg->user_id === auth()->id() ? 'bg-blue-100 dark:bg-blue-900 text-gray-900 dark:text-white' : 'bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300' }} p-3 rounded-xl max-w-md text-sm">
                                    {{ $msg->message }}
                                    <div class="text-xs text-gray-400 mt-1 {{ $msg->user_id === auth()->id() ? 'text-right' : 'text-left' }}">
                                        {{ $currentTimestamp->format('g:i A') }}
                                    </div>
                                </div>
                            </div>

                            {{-- Avatar (right for self) --}}
                            @if ($msg->user_id === auth()->id())
                                @if ($isLastInBlock)
                                    <img src="{{ $msg->user->avatar_url ?? 'https://i.pravatar.cc/100?u=' . $msg->user_id }}" class="w-9 h-9 rounded-full" />
                                @else
                                    <div class="w-9"></div>
                                @endif
                            @endif
                        </div>
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

    @include('livewire.user.chat.partials.group-setting')
</div>
