<div 
    x-data 
    x-init="
        {{-- Echo.private(`App.Models.User.${@js(auth()->id())}`)
            .notification((notification) => {
                console.log('Realtime notif:', notification);
                Livewire.dispatch('notificationReceived', { notification });
            }); --}}
    "
    class="space-y-4"
>

    <div class="flex justify-between items-center p-4 sticky top-0 
           bg-white/60 dark:bg-black/30
           backdrop-blur-md 
           z-50 shadow-sm">
        <h2 class="text-lg font-bold text-gray-800 dark:text-gray-100 flex items-center gap-4">
            <flux:icon.bell class="text-blue-300"/>
            Notifications
        </h2>
        @if($notifications->count())
                <flux:button 
                    wire:click="markAllAsRead"
                    variant="filled"
                    size="sm"
                >
                    Mark all as read
                </flux:button>
            @endif
    </div>
    <div class="space-y-3 px-10">
        @forelse($notifications as $notif)

            @if($notif->data['user_id'] == null)
                <div class="flex items-center justify-between p-3 rounded-xl shadow-sm
                            bg-white dark:bg-gray-800 
                            text-gray-800 dark:text-gray-200
                            hover:shadow-md transition">
                    
                    {{-- Icon + Content --}}
                    <div class="flex items-start space-x-3">
                        <flux:avatar
                            icon="user-group"
                            circle
                        />

                        <div>
                            <p class="text-sm font-medium">
                                {{ ucfirst($notif->data['type']) }}
                            </p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                {{ $notif->data['message'] }}
                            </p>
                        </div>
                    </div>

                    {{-- Action --}}
                    <button 
                        wire:click="markAsRead('{{ $notif->id }}')" 
                        class="text-xs font-medium px-3 py-1 rounded-lg
                            bg-blue-100 text-blue-600 hover:bg-blue-200
                            dark:bg-blue-900 dark:text-blue-300 dark:hover:bg-blue-800 
                            transition"
                    >
                        Mark as read
                    </button>
                </div>
            @else 
                @php
                $user = isset($notif->data['user_id']) ? \App\Models\User::find($notif->data['user_id']) : null;
            @endphp
                <div class="flex items-center justify-between p-3 rounded-xl shadow-sm
                            bg-white dark:bg-gray-800 
                            text-gray-800 dark:text-gray-200
                            hover:shadow-md transition">
                    
                    {{-- Icon + Content --}}
                    <div class="flex items-start space-x-3">
                        @if ($user->profile_image)
                                    <flux:avatar
                                        circle
                                        src="{{ asset('storage/' . $user->profile_image) }}"
                                        
                                    />
                                @else
                                    <flux:avatar
                                        circle
                                        :initials="$user->initials()"
                                        
                                    />
                                @endif

                        <div>
                            <p class="text-sm font-medium">
                                {{ ucfirst($notif->data['type']) }}
                            </p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                {{ $notif->data['message'] }}
                            </p>
                        </div>
                    </div>

                    {{-- Action --}}
                    <button 
                        wire:click="markAsRead('{{ $notif->id }}')" 
                        class="text-xs font-medium px-3 py-1 rounded-lg
                            bg-blue-100 text-blue-600 hover:bg-blue-200
                            dark:bg-blue-900 dark:text-blue-300 dark:hover:bg-blue-800 
                            transition"
                    >
                        Mark as read
                    </button>
                </div>
            @endif
            
        @empty
            <div class="p-4 text-center text-gray-500 dark:text-gray-400 
                        bg-gray-50 dark:bg-gray-900 rounded-xl">
                No new notifications ✨
            </div>
        @endforelse
    </div>
</div>
