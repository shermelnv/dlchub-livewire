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

        Echo.private(`chat.${groupId}`)
            .listen('.group.user.approved', (e) => {
                console.log('User approved!', e);
                Livewire.dispatch('user-approved', e);
            });


    "
    class="flex flex-col h-[calc(100svh-3.5rem)] lg:h-[100svh] sticky top-13 lg:static lg:grid lg:grid-cols-3"

>

<div class="flex flex-col h-full lg:h-screen col-span-2 border-r border-gray-200 dark:border-gray-700">

    <!-- Chat Layout -->
    
    
        
        @if ($selectedGroup)
        <!-- Chat Header -->
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <div class="flex gap-4 items-center">
                            <a href="{{route('user.chat')}}">
                                <flux:icon.arrow-left/>
                            </a>
                            <div class="flex items-center gap-4">
                                @if ($selectedGroup->group_profile)
                                    <flux:avatar
                                        circle
                                        src="{{ Storage::disk('digitalocean')->url($selectedGroup->group_profile) }}"
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
                        
                        <flux:modal.trigger name="group-settings" >
                            <flux:button icon="cog" variant="ghost" />
                        </flux:modal.trigger>

                        {{-- <flux:modal.trigger name="group-settings-small-devices" class="lg:hidden">
                            <flux:button icon="cog" variant="ghost" />
                        </flux:modal.trigger> --}}
            </div>
            <div class="flex flex-col flex-1 min-h-0">
                <!-- Messages -->
                    <div 
                            class="flex-1 overflow-y-auto p-2 space-y-4 chat-messages relative"
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

                            @if ($msg->user_id === null)
                                <div class="text-center text-sm text-gray-400 dark:text-gray-500 my-2 ">
                                        {{-- <div class="text-xs">
                                            {{ $currentTimestamp->format('g:i A') }}
                                        </div> --}}
                                        {{ $msg->message }}
                                        
               
                                </div>
                            @else
                                
                                {{-- Message Row --}}
                                <div class="flex gap-3 items-end {{ $msg->user_id === auth()->id() ? 'justify-end' : '' }}">
                                        
                                        {{-- Avatar (left for others) --}}
                                    @if ($msg->user_id !== auth()->id())
                                        @if ($isLastInBlock)
                                                @if ($msg->user && $msg->user->profile_image)
                                                    <flux:avatar circle 
                                                    {{-- src="{{ asset('storage/' . $msg->user->profile_image) }}"  --}}
                                                    src="{{ Storage::disk('digitalocean')->url($msg->user->profile_image) }}"
                                                    />
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
                                                            <flux:avatar circle 
                                                            {{-- src="{{ asset('storage/' . $msg->user->profile_image) }}"  --}}
                                                            src="{{ Storage::disk('digitalocean')->url($msg->user->profile_image) }}"
                                                            />
                                                        @else
                                                            <flux:avatar circle :initials="$msg->user?->initials()" />
                                                        @endif
                                                

                                                @else
                                                    <div class="w-10"></div>
                                                @endif
                                            @endif
                                </div>
                            
                            @endif
                        

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
                            <flux:input wire:model.defer="messageInput" placeholder="Type your message..." class="flex-1" autocomplete="off"/>
                            <flux:button type="submit" icon="arrow-right" class="size-10" />
                        </form>
                    </div>
            </div>
        @else
        <div class="relative w-full space-y-4 h-[calc(100dvh-5rem)] lg:h-[100dvh]">
            <!-- Header -->
            <div class="flex flex-col">
                <flux:heading size="xl" class="flex items-center py-8 px-4 gap-2">
                    Group Chats 
                    <flux:badge 
                        variant="pill" 
                        icon="user-group" 
                        color="{{ $groups->count() == 4 ? 'red' : 'lime' }}" >{{ $groups->count() }} / 4
                    </flux:badge>
                </flux:heading>

                <flux:separator/>
            </div>

            <!-- Group Cards -->
            <div class="space-y-3 px-4">
                @forelse ($groups as $group)
                    <a href="/user/chat/{{ $group->group_code }}"
                    class="block rounded-xl border shadow-sm p-4 transition-all duration-200 
                            {{ request()->is('user/chat/' . $group->group_code) 
                                ? 'bg-gray-100 dark:bg-gray-800 border-gray-300 dark:border-gray-700' 
                                : 'bg-white dark:bg-gray-900 hover:bg-gray-50 dark:hover:bg-gray-800 border-gray-200 dark:border-gray-700' }}">
                        <div class="flex justify-between">
                            <div class="flex items-center gap-4">
                                <!-- Avatar -->
                                    @if ($group->group_profile)
                                            <flux:avatar
                                                circle
                                                {{-- src="{{ asset('storage/' . $group->group_profile) }}" --}}
                                                src="{{ Storage::disk('digitalocean')->url($group->group_profile) }}"
                                                
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
                                    <span  x-data="{
                                                expires: new Date('{{ $group->expires_at }}'),
                                                now: new Date(),
                                                remaining() {
                                                    let diff = Math.max(this.expires - this.now, 0) / 1000; // seconds
                                                    let days = Math.floor(diff / 86400);
                                                    diff %= 86400;
                                                    let hours = Math.floor(diff / 3600);
                                                    diff %= 3600;
                                                    let minutes = Math.floor(diff / 60);
                                                    let seconds = Math.floor(diff % 60);
                                                    return `${days}d ${hours}h ${minutes}m ${seconds}s`;
                                                }
                                            }"
                                            x-init="setInterval(() => { now = new Date(); }, 1000)"
                                            x-text="remaining()"
                                        class="text-xs text-zinc-500 dark:text-zinc-400 truncate">
                                    </span>
                                </div>
                            </div>
                            <div class="flex flex-col text-xs text-zinc-400 justify-center font-light">
                                <div>{{$group->members->count()}} Members</div>
                                
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
            <div class="absolute bottom-0 right-0 p-4">
                <flux:modal.trigger name="create-group">
                    <flux:button circle variant="filled" icon="plus" >
                        Create / Join Group
                    </flux:button>
                </flux:modal.trigger>
            </div>
            {{-- @endif --}}
        </div>

        @endif
    

    @if($selectedGroup)
        @include('livewire.user.chat.partials.group-setting')
    @else
        @include('livewire.user.chat.partials.create-group')
    @endif
</div>
    <livewire:right-sidebar/>
<script>
document.querySelectorAll('input, textarea').forEach(el => {
  el.addEventListener('focus', () => {
    setTimeout(() => {
      el.scrollIntoView({behavior: 'smooth', block: 'center'});
    }, 300);
  });
});
</script>

</div>
