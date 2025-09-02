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
    class="grid grid-cols-1 md:grid-cols-3"

>

<div class="flex flex-col h-[calc(100dvh-5rem)] lg:h-[100dvh] col-span-2 border-r border-gray-200 dark:border-gray-700">

    <!-- Chat Layout -->
    <div class="grid grid-rows-[auto_1fr_auto] flex-1 min-h-0">

        @if ($selectedGroup)
        <!-- Chat Header -->
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex gap-4 items-center">
                        <a href="{{route('user.chat')}}">
                            <flux:icon.arrow-left/>
                        </a>
                        <div class="flex items-center gap-4">
                            @if ($selectedGroup->group_image)
                                <flux:avatar
                                    circle
                                    src="{{ asset('storage/' . $selectedGroup->group_image) }}"
                                    
                                />
                            @else
                                <flux:avatar
                                    circle
                                    name="{{$selectedGroup->name}}"
                                    
                                />
                            @endif
                            <div>
                                <div class="text-lg font-bold text-gray-800 dark:text-white">{{ $selectedGroup->name }}</div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">{{ $selectedGroup->description }}</div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">{{ $selectedGroup->group_code }}</div>
                            </div>
                            
                        </div>

                    </div>
                    
                    <flux:modal.trigger name="group-settings-large-devices" class="">
                        <flux:button icon="cog" variant="ghost" />
                    </flux:modal.trigger>

        </div>

        <!-- Messages -->
                <div 
    class="overflow-y-auto min-h-0 p-6 space-y-4 chat-messages relative"
    x-data="{
        isAtBottom: true,
        checkScroll() {
            const threshold = 50;
            this.isAtBottom = this.$el.scrollTop + this.$el.clientHeight >= this.$el.scrollHeight - threshold;
        },
        scrollToBottom() {
            this.$el.scrollTop = this.$el.scrollHeight;
            this.isAtBottom = true;
        }
    }"
    x-init="scrollToBottom()"
    x-on:scroll="checkScroll"
    x-on:message-received.window="
        if (isAtBottom) { 
            $nextTick(() => scrollToBottom()); 
        }
    "
>

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
                           @if ($isLastInBlock)
                                @if ($msg->user && $msg->user->profile_image)
                                    <flux:avatar circle src="{{ asset('storage/' . $msg->user->profile_image) }}" />
                                @else
                                    <flux:avatar circle :initials="$msg->user?->initials()" />
                                @endif
                                
                                @else
                                    <div class="w-10"></div>
                                @endif
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
                                    
                                        @if ($msg->user && $msg->user->profile_image)
                                            <flux:avatar circle src="{{ asset('storage/' . $msg->user->profile_image) }}" />
                                        @else
                                            <flux:avatar circle :initials="$msg->user?->initials()" />
                                        @endif
                                   

                                @else
                                    <div class="w-10"></div>
                                @endif
                            @endif
                        </div>
                    
                    

                    @empty
                    no chat yet
                    @endforelse


                        <div 
        x-show="!isAtBottom"
        x-transition
        class="sticky bottom-0 flex justify-center"
    >
        <flux:button variant="ghost"
    @click="window.dispatchEvent(new CustomEvent('scroll-to-bottom'))"
    {{-- class="px-3 py-1 bg-blue-500 text-white rounded-lg shadow-md" --}}
>
    <flux:icon.chevron-down/>
</flux:button>
    </div>
   

                </div>

                <!-- Message Input -->
                <div class="border-t border-gray-200 dark:border-gray-700 p-4">
                    <form wire:submit.prevent="sendMessage" class="flex items-center gap-2">
                        <flux:input wire:model.defer="messageInput" placeholder="Type your message..." class="flex-1" />
                        <flux:button type="submit" icon="arrow-right" class="size-10" />
                    </form>
                </div>
            @else
<div class="relative w-full space-y-4 p-4 h-[calc(100vh-5rem)] md:h-screen">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <flux:heading size="xl" class="flex items-center gap-3">
            Group Chats 
            <flux:badge 
                color="{{ $groups->count() == 4 ? 'red' : 'lime' }}" 
                class="text-sm px-2 py-1 rounded-md">
                {{ $groups->count() }} / 4
            </flux:badge>
        </flux:heading>
    </div>

    <!-- Group Cards -->
    <div class="space-y-3">
        @forelse ($groups as $group)
            <a href="/user/chat/{{ $group->group_code }}"
               class="block rounded-xl border shadow-sm p-4 transition-all duration-200 
                      {{ request()->is('user/chat/' . $group->group_code) 
                          ? 'bg-gray-100 dark:bg-gray-800 border-gray-300 dark:border-gray-700' 
                          : 'bg-white dark:bg-gray-900 hover:bg-gray-50 dark:hover:bg-gray-800 border-gray-200 dark:border-gray-700' }}">
                
                <div class="flex items-center gap-4">
                    <!-- Avatar -->
                        @if ($group->group_image)
                                <flux:avatar
                                    circle
                                    src="{{ asset('storage/' . $group->group_image) }}"
                                    
                                />
                            @else
                                <flux:avatar
                                    circle
                                    name="{{$group->name}}"
                                    
                                />
                            @endif

                    <!-- Info -->
                    <div class="flex flex-col truncate">
                        <span class="font-medium text-base truncate">{{ $group->name }}</span>
                        <span class="text-xs text-zinc-500 dark:text-zinc-400 truncate">
                            {{ $group->description ?: 'No Description' }}
                        </span>
                    </div>
                </div>
            </a>
        @empty
            <div class="text-sm text-zinc-500 italic px-2 py-2">
                You're not part of any groups yet.
            </div>
        @endforelse
    </div>

    {{-- @if($groups->count() < 4) --}}
    <!-- Floating Add Group Button -->
    <div class="absolute bottom-4 right-4">
        <flux:modal.trigger name="create-group-desktop">
            <flux:button circle variant="filled" icon="plus" >
                Create / Join Group
            </flux:button>
        </flux:modal.trigger>
    </div>
    {{-- @endif --}}
</div>






            @endif
    </div>

    @if($selectedGroup)
    @include('livewire.user.chat.partials.group-setting')

    @endif
    @include('livewire.user.chat.partials.create-group')
</div>

<livewire:right-sidebar />

</div>
