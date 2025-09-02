<div 
    x-data 
    x-init="
        Echo.private(`App.Models.User.${@js(auth()->id())}`)
            .notification((notification) => {
                console.log('Realtime notif:', notification);
                Livewire.dispatch('notificationReceived', { notification });
            });
    "
    class="p-4  mx-auto"
>
    <h2 class="text-lg font-bold mb-4  text-gray-800 dark:text-gray-100 flex items-center gap-4">
        <flux:icon.bell class="text-blue-300"/>
        Notifications
    </h2>

    <div class="space-y-3">
        @forelse($notifications as $notif)
            <div class="flex items-center justify-between p-3 rounded-xl shadow-sm
                        bg-white dark:bg-gray-800 
                        text-gray-800 dark:text-gray-200
                        hover:shadow-md transition">
                
                {{-- Icon + Content --}}
                <div class="flex items-start space-x-3">
                    <div class="w-10 h-10 flex items-center justify-center rounded-full
                                {{ $notif->data['type'] === 'advertisement' ? 'bg-blue-100 text-blue-600 dark:bg-blue-900 dark:text-blue-300' : '' }}
                                {{ $notif->data['type'] === 'voting' ? 'bg-green-100 text-green-600 dark:bg-green-900 dark:text-green-300' : '' }}
                                {{ $notif->data['type'] === 'feed' ? 'bg-purple-100 text-purple-600 dark:bg-purple-900 dark:text-purple-300' : '' }}">
                        @if($notif->data['type'] === 'advertisement')
                            📢
                        @elseif($notif->data['type'] === 'voting')
                            🗳️
                        @elseif($notif->data['type'] === 'feed')
                            📰
                        @else
                            🔔
                        @endif
                    </div>

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
        @empty
            <div class="p-4 text-center text-gray-500 dark:text-gray-400 
                        bg-gray-50 dark:bg-gray-900 rounded-xl">
                No new notifications ✨
            </div>
        @endforelse
    </div>
</div>
