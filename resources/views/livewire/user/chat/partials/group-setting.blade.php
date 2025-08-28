<flux:modal name="group-settings-large-devices" variant="flyout" class="">
    <flux:heading>Group Settings</flux:heading>

    <!-- Group Info -->
    <div class="flex flex-col items-center gap-2 w-full">
        <flux:avatar circle src="https://unavatar.io/x/calebporzio" class="size-30" />
        <div class="text-center font-bold">{{ $selectedGroup->name }}</div>
        <div class="text-blue-500 cursor-pointer">Change name or image</div>
    </div>

    <hr class="my-4 border-gray-300 dark:border-gray-700" />

    <!-- Members Section -->
    <div class="space-y-3">
        <flux:heading size="sm">Members</flux:heading>
        @foreach ($selectedGroup->members as $member)
            <div class="flex items-center justify-between p-2 bg-zinc-100 dark:bg-zinc-800 rounded-xl">
                <div class="flex items-center gap-3">
                    <flux:avatar src="{{ $member->avatar_url }}" class="size-8" />
                    <div>{{ $member->name }}</div>
                </div>
                <flux:button size="xs" variant="danger" wire:click="removeMember({{ $member->id }})">Remove</flux:button>
            </div>
        @endforeach
    </div>

    <hr class="my-4 border-gray-300 dark:border-gray-700" />

    <!-- Member Requests Section -->
    <div class="space-y-3">
            <div class="flex justify-between">
                <flux:heading size="sm">Join Requests</flux:heading>
                <flux:modal.trigger name="rejected-list">
                    <flux:button size="xs">Rejected List</flux:button>
                </flux:modal.trigger>
                
    </div>
        @forelse ($selectedGroup->requests()->where('status', 'pending')->with('user')->get() as $request)
            <div class="border p-4 rounded-lg flex justify-between items-center">
                                <div class="">
                                    <p class="font-bold text-white truncate">{{ $request->user->name }}</p>
                                    <p class="text-sm text-gray-300 truncate">{{ $request->user->email }}</p>
                                </div>
                                <div class="flex gap-2">
                                    <flux:button size="xs" wire:click="approveRequest({{ $request->id }})">Approve</flux:button>
                                    <flux:button size="xs" variant="danger" wire:click="rejectRequest({{ $request->id }})">Reject</flux:button>
                                </div>
                            </div>
        @empty
            <div class="text-gray-500 dark:text-gray-400 text-sm">No pending requests.</div>

        @endforelse
    </div>
</flux:modal>


<flux:modal name="group-settings-mobile" variant="flyout" class="min-w-xs">
    <flux:heading>Group Settings</flux:heading>

    <!-- Group Info -->
    <div class="flex flex-col items-center gap-4 w-full">
        <flux:avatar circle src="https://unavatar.io/x/calebporzio" class="size-30" />
        <div class="text-center font-bold">{{ $selectedGroup->name }}</div>
        <div class="text-blue-500 cursor-pointer">Change name or image</div>
    </div>

    <hr class="my-4 border-gray-300 dark:border-gray-700" />

    <!-- Members Section -->
    <div class="space-y-3">
        <flux:heading size="sm">Members</flux:heading>
        @foreach ($selectedGroup->members as $member)
            <div class="flex items-center justify-between p-2 bg-zinc-100 dark:bg-zinc-800 rounded-xl">
                <div class="flex items-center gap-3 max-w-40">
                    <flux:avatar src="{{ $member->avatar_url }}" class="size-8" />
                    <div class="truncate">{{ $member->name }}  @if($selectedGroup->group_owner_id === $member->id) <br> <flux:badge size="sm">Admin</flux:badge> @endif </div>

                </div>
                @if(auth()->user()->id !== $member->id && auth()->user()->id === $selectedGroup->group_owner_id)
                <flux:button size="xs" variant="danger" wire:click="removeMember({{ $member->id }})">Remove</flux:button>
                @endif
            </div>
        @endforeach
    </div>

    <hr class="my-4 border-gray-300 dark:border-gray-700" />

    <!-- Member Requests Section -->
    <div 
    x-data
    x-init="


    "
    class="space-y-3">
    <div class="flex justify-between">
                <flux:heading size="sm">Join Requests</flux:heading>
                <flux:button size="sm">Rejected List</flux:button>
    </div>

        @forelse ($selectedGroup->requests()->where('status', 'pending')->with('user')->get() as $request)
            <div class="border p-4 rounded-lg flex justify-between items-center gap-2">
                                <div class="flex items-center gap-2 ">
                                    <flux:avatar src="{{ $member->avatar_url }}" class="size-8" />
                                    <div class="max-w-24">
                                        <div class="truncate">{{ $request->user->name }}</div>
                                        <div class="truncate">{{ $request->user->email }}</div>
                                    </div>
                                </div>
                                <div class="grid gap-1">
                                    <flux:button size="xs" wire:click="approveRequest({{ $request->id }})">Approve</flux:button>
                                    <flux:button size="xs" variant="danger" wire:click="rejectRequest({{ $request->id }})">Reject</flux:button>
                                </div>
                            </div>
        @empty
            <div class="text-gray-500 dark:text-gray-400 text-sm">No pending requests.</div>

        @endforelse
    </div>
</flux:modal>


<flux:modal name="rejected-list">
    <flux:heading size="sm">Rejected Join Requests</flux:heading>
    
    @forelse ($selectedGroup->requests()->where('status', 'rejected')->with('user')->get() as $rejected)
        <div class="border p-4 rounded-lg flex justify-between items-center">
            <div>
                <p class="font-bold">{{ $rejected->user->name }}</p>
                <p class="text-sm text-gray-500">{{ $rejected->user->email }}</p>
            </div>
            <flux:button size="xs" wire:click="approveRejected({{ $rejected->id }})">
                Approve
            </flux:button>
        </div>
    @empty
        <div class="text-gray-500 dark:text-gray-400 text-sm">
            No rejected requests.
        </div>
    @endforelse
</flux:modal>

