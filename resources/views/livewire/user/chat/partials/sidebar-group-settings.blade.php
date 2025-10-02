<div>
    <div class="hidden lg:block">
        <div>


        <!-- Group Info -->
        <div class="flex flex-col items-center gap-2 w-full">

                    @if ($selectedGroup->group_profile )
                                    <flux:avatar
                                        circle
                                        {{-- src="{{ asset('storage/' . $selectedGroup->group_profile) }}" --}}
                                        src="{{ Storage::disk('digitalocean')->url($selectedGroup->group_profile) }}"
                                        class="size-40"
                                    />
                                @else
                                    <flux:avatar
                                        circle
                                        name="{{$selectedGroup->name}}"
                                        class="size-40 text-2xl"
                                    />
                                @endif
            

            <div class="text-center font-bold">{{ $selectedGroup->name }}</div>

            {{-- <flux:modal.trigger name="edit-group-info"> --}}
                <flux:button wire:click.prevent="editGroupInfo({{$selectedGroup->id}})" variant="ghost" class="text-blue-500 cursor-pointer">
                    Change name or image
                </flux:button>
            {{-- </flux:modal.trigger> --}}

        </div>

        <hr class="my-4 border-gray-300 dark:border-gray-700" />

        <!-- Members Section -->
        <div class="space-y-3">
            <flux:heading size="sm">Members</flux:heading>
            @foreach ($selectedGroup->members as $member)
                <div class="flex items-center justify-between p-2 bg-zinc-100 dark:bg-zinc-800 rounded-xl">
                    <div class="flex items-center gap-3">
                        @if ($member->profile_image)
                            <flux:avatar circle 
                            {{-- src="{{ asset('storage/' . $member->profile_image) }}"  --}}
                            src="{{ Storage::disk('digitalocean')->url($member->profile_image) }}"
                            class="size-8 rounded-full object-cover overflow-hidden"
                            />
                        @else
                            <flux:avatar circle :initials="$member->initials()" />
                        @endif
                        <div>{{ $member->name }}</div>
                    </div>
                    @if(auth()->user()->id !== $member->id && auth()->user()->id === $selectedGroup->group_owner_id)
                        {{-- <flux:button size="xs" variant="danger" wire:click="removeMember({{ $member->id }})">Remove</flux:button> --}}
                        <flux:dropdown  position="left">
                            <flux:button icon="ellipsis-vertical" size="xs" variant="ghost"/>

                            <flux:menu>
                                
                                <flux:menu.item icon="cog-6-tooth" 
                                    wire:click="makeAdmin({{ $member->id }})">
                                    View User
                                </flux:menu.item>
                                <flux:menu.item 
                                    icon="trash" 
                                    variant="danger" 
                                    wire:click="removeMember({{ $member->id }})">
                                    Remove
                                </flux:menu.item>
                            </flux:menu>
                        </flux:dropdown>
                    
                    @endif
                    @if ($member->id === $selectedGroup->group_owner_id)
                        <flux:badge>Admin</flux:badge>
                    @endif

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
                    <div>
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



        </div>
            <div class="">
                <flux:button size="sm" variant="danger" wire:click="leaveGroup({{$selectedGroup->id}})">
                    Leave
                </flux:button>
            </div>
    </div>

    <flux:modal name="group-settings" variant="flyout">
        <div class="min-w-sm">
            
        </div>

    </flux:modal>
</div>