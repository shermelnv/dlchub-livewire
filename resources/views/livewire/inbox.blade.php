<div 
    x-data 
    x-init="
        {{-- Echo.private(`App.Models.User.${@js(auth()->id())}`)
            .notification((notification) => {
                console.log('Realtime notif:', notification);
                Livewire.dispatch('notificationReceived', { notification });
            }); --}}
    "
    class="grid lg:grid-cols-3"
>

<div class="col-span-2 space-y-4">
    <div class=" p-4 sticky top-14 lg:top-0
           bg-white/60 dark:bg-black/30
           backdrop-blur-md 
           z-1 shadow-sm space-y-4" >
        <div class="flex justify-between items-center">
            <h2 class="text-lg font-bold text-gray-800 dark:text-gray-100 flex items-center gap-4">
                {{-- <flux:icon.bell class="text-blue-300"/> --}}
                Notifications
            </h2>
            {{-- unread notif --}}
            @if($notifications->count() && $notifications->where('read_at', null)->count() > 0)
                    <flux:button 
                        wire:click="markAllAsRead"
                        variant="filled"
                        size="sm"
                    >
                        Mark all as read
                    </flux:button>
            @endif
        </div>
        <div class="max-w-sm">
            <flux:input icon="magnifying-glass" placeholder="Search Notification" wire:model.live="search"/>
        </div>
    </div>
    <div class="space-y-3 px-10">
        @forelse($notifications as $notif)
            @php
                $isRead = !is_null($notif->read_at);
                $user = isset($notif->data['user_id']) ? \App\Models\User::find($notif->data['user_id']) : null;
            @endphp

            <div class="grid grid-cols-[auto_1fr] p-3 rounded-xl shadow-sm
                        {{ $isRead ? 'bg-gray-50 dark:bg-gray-900 opacity-60' : 'bg-white dark:bg-gray-800' }}
                        text-gray-800 dark:text-gray-200 hover:shadow-md transition">
                
                {{-- Icon + Content --}}
                <div class="flex items-center space-x-3">
                    
                    @if ($user)
                        @if ($user->profile_image)
                            <flux:avatar 
                            circle 
                            {{-- src="{{ asset('storage/' . $user->profile_image) }}"  --}}
                            src="{{ Storage::disk('digitalocean')->url($user->profile_image) }} "
                            />
                            
                        @else
                            <flux:avatar circle :initials="$user->initials()" />
                        @endif
                    @else
                        <flux:avatar icon="user-group" circle />
                    @endif

                    <div >
                        <p class="text-sm font-medium">
                            {{ ucfirst($notif->data['type']) }}
                        </p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">
                            {{ $notif->data['message'] }}
                        </p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">
                            {{ $notif->created_at->diffForHumans() }}

                        </p>
                    </div>
                </div>

                {{-- Only show button if unread --}}
                @unless($isRead)
                    <div class="flex justify-end items-center">
                        <flux:button size="xs" variant="primary" color="blue" wire:click="markAsRead('{{ $notif->id }}')" >Mark as Read</flux:button>
                    </div>
                @endunless
            </div>
        @empty
            <div class="p-4 text-center text-gray-500 dark:text-gray-400 
                        bg-gray-50 dark:bg-gray-900 rounded-xl">
                No new notifications ✨
            </div>
        @endforelse

    </div>
</div>
<livewire:right-sidebar />
</div>
