<div>
    @if($requests->count() > 0)
        <h4 class="text-lg font-semibold mb-2">Pending Follow Requests</h4>
        <ul class="space-y-2">
            @foreach($requests as $user)
                <li class="flex justify-between items-center bg-gray-100 dark:bg-gray-800 p-2 rounded">
                    <div class="flex items-center gap-2">
                        <flux:avatar :initials="$user->initials()" class="size-8" />
                        <span>{{ $user->name }}</span>
                    </div>
                    <div class="flex gap-2">
                        <flux:button size="sm" color="green" wire:click="accept({{ $user->id }})">Accept</flux:button>
                        <flux:button size="sm" color="red" wire:click="reject({{ $user->id }})">Reject</flux:button>
                    </div>
                </li>
            @endforeach
        </ul>
    @else
        <p class="text-sm text-gray-500">No pending requests</p>
    @endif
</div>
