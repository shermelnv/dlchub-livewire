{{-- <flux:navlist.item
    x-data 
    x-init="
        Echo.private(`App.Models.User.${@js(auth()->id())}`)
            .notification((notification) => {
                console.log('Realtime notif:', notification);
                Livewire.dispatch('notificationReceived', { notification });
            });
    "
    icon="users" 
    :href="route('inbox')" 
    :current="request()->routeIs('inbox')" 
    wire:navigate 
    :badge="auth()->user()->unreadNotifications()->count()"

>
    {{ __('Inbox') }}
</flux:navlist.item> --}}

<div 
 x-data 
    x-init="
        {{-- Echo.private(`App.Models.User.${@js(auth()->id())}`)
            .notification((notification) => {
                console.log('Realtime notif:', notification);
                Livewire.dispatch('notificationReceived', { notification });
            }); --}}
    "
    class="flex justify-between">
    <div>{{ __('Inbox') }} </div>
    @if($unreadCount > 0)
        <div>{{$unreadCount}}</div>
    @endif
</div>