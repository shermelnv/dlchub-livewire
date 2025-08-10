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

        Echo.private(`group.${groupId}`)
            .listen('.group.join.request', (e) => {
                    if (e.groupId === groupId) { 
                    console.log('received', e);
                    Livewire.dispatch('newJoinRequest');
                    }
            });
    "
    class="flex flex-col h-[calc(100dvh-5rem)] lg:h-[100dvh]"

>

    <!-- Chat Layout -->
    <div class="grid grid-rows-[auto_1fr_auto] flex-1 min-h-0">

        @if ($selectedGroup)
        <!-- Chat Header -->
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <div>
                        <div class="text-lg font-bold text-gray-800 dark:text-white">{{ $selectedGroup->name }}</div>
                        <div class="text-sm text-gray-500 dark:text-gray-400">{{ $selectedGroup->description }}</div>
                        <div class="text-sm text-gray-500 dark:text-gray-400">{{ $selectedGroup->group_code }}</div>
                    </div>
                    
                    <flux:modal.trigger name="group-settings-large-devices" class="hidden lg:block">
                        <flux:button icon="cog" variant="ghost" />
                    </flux:modal.trigger>

                    <flux:modal.trigger name="group-settings-mobile" class="lg:hidden">
                        <flux:button icon="cog" variant="ghost" />
                    </flux:modal.trigger>
        </div>

        <!-- Messages -->
                <div class="overflow-y-auto min-h-0 p-6 space-y-4 chat-messages">


                    @php
                        $lastTimestamp = null;
                        $lastUserId = null;
                        $lastDate = null;
                    @endphp

                    @forelse ($messages as $index => $message)
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
                            @if ($msg->user_id !== auth()->id())
                                    <img src="https://i.pravatar.cc/100?u={{ $msg->user_id }}" class="w-9 h-9 rounded-full object-cover" />
                            @endif

                            {{-- Message bubble --}}
                            <div>
                                @if (!$sameUserAsPrevious)
                                    <div class="text-xs text-gray-400 dark:text-gray-500 mb-1 {{ $msg->user_id === auth()->id() ? 'text-right pr-1' : 'text-left pl-1' }}">
                                        {{ $msg->user_id === auth()->id() ? 'You' : $msg->user['name'] }}
                                    </div>
                                @endif

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
                                        <img src="https://i.pravatar.cc/100?u={{ auth()->id() }}" class="w-9 h-9 rounded-full object-cover" />
                                @else
                                    <div class="w-9"></div>
                                @endif
                            @endif
                        </div>

                    @empty
                    no chat yet
                    @endforelse
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
                    Select a group on sidebar to start chatting
                </div>
            @endif
    </div>

    @if($selectedGroup)
    @include('livewire.user.chat.partials.group-setting')
    @endif
</div>
