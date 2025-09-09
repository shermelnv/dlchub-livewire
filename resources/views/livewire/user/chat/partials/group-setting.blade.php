{{-- Group Settings Modal (Large Devices) --}}
<flux:modal name="group-settings" variant="flyout" class="min-w-xs h-screen">
    <div class="h-full grid grid-rows-[1fr_auto] gap-4">
        <div class=" grid grid-rows-[auto_1fr_1fr] overflow-hidden">

            <!-- Group Info -->
            <div class="flex flex-col items-center gap-2 w-full">

                        @if ($selectedGroup->group_profile )
                                        <flux:avatar
                                            circle
                                            src="{{ asset('storage/' . $selectedGroup->group_profile) }}"
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

            

            <!-- Members Section -->
            <div class="flex flex-col gap-4 flex-1 p-2 overflow-hidden scrollbar-hover">
                <flux:heading size="sm" 
                class="flex justify-between items-center"
            
                >Members
                <flux:badge size="sm" inset="top bottom">{{$selectedGroup->members->count()}}</flux:badge>
            </flux:heading>
                <div class="flex-1 overflow-y-auto space-y-2">
                @foreach ($selectedGroup->members as $member)
                    <div class="flex items-center justify-between  bg-zinc-100 dark:bg-zinc-800 rounded-xl">
                        <div class="flex items-center gap-3">
                            @if ($member->profile_image)
                                <flux:avatar circle src="{{ asset('storage/' . $member->profile_image) }}" />
                            @else
                                <flux:avatar circle :initials="$member->initials()" />
                            @endif
                            <div>{{ $member->name }}</div>
                        </div>
                        @if(auth()->user()->id !== $member->id && auth()->user()->id === $selectedGroup->group_owner_id)
                            <flux:button size="xs" variant="danger" wire:click="removeMember({{ $member->id }})">Remove</flux:button>
                            {{-- <flux:dropdown  position="bottom">
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
                            </flux:dropdown> --}}
                        
                        @endif
                        @if ($member->id === $selectedGroup->group_owner_id)
                            <flux:badge>Admin</flux:badge>
                        @endif

                    </div>
                @endforeach
                </div>
            </div>

        

            <!-- Member Requests Section -->
            <div class="flex flex-col flex-1 gap-4 p-2 overflow-hidden scrollbar-hover">
                <div class="flex justify-between">
                    <flux:heading size="sm">Join Requests</flux:heading>
                    <flux:badge size="sm" inset="top bottom">{{$selectedGroup->requests()->where('status', 'pending')->count()}}</flux:badge>
                </div>
                <div class="max-h-50 overflow-y-auto scrollbar-hover ">
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

        </div>
        <div class="">
            <flux:modal.trigger name="delete-group">
                @if(auth()->user()->id !== $member->id && auth()->user()->id === $selectedGroup->group_owner_id)
                    <flux:button size="sm" variant="danger" >
                        Delete
                    </flux:button>
                @else
                    <flux:button size="sm" variant="danger" >
                        Leave
                    </flux:button>
                @endif
            </flux:modal.trigger>
        </div>
    </div>
</flux:modal>

<flux:modal name="edit-group-info" class="min-w-sm">
    <flux:heading>Edit Group Info</flux:heading>

    <div class="flex flex-col items-center gap-4 w-full">
        <!-- Group Photo -->
        <div>
            @if ($group_profile)
                <div class="size-40 rounded-full overflow-hidden border border-gray-300 dark:border-gray-700"> 
                    <img src="{{ $group_profile->temporaryUrl() }}" class="object-cover w-full h-full" />
                </div> 
            @elseif ($selectedGroup->group_profile)
                <flux:avatar
                    circle
                    src="{{ asset('storage/' . $selectedGroup->group_profile) }}"
                    class="size-40"
                />
            @else
                <flux:avatar
                    circle
                    name="{{$selectedGroup->name}}"
                    class="size-40 text-2xl"
                />
            @endif
            <div wire:loading wire:target="group_profile" class="mt-2 flex items-center gap-2 text-sm text-gray-500">
                Uploading…
            </div>
        </div>

        <!-- Change Photo Input -->
        <div class="w-full">
            <flux:input wire:model="group_profile" type="file" label="Select Image" accept="image/*" />
        </div>
        <!-- Group Name Input -->
 
        <div class="w-full">
            <flux:input wire:model="editGroup.name" type="text" label="Group Name" placeholder="Enter new group name" class="w-full mt-1" />
        </div>

        <!-- Action Buttons -->
        <div class="flex justify-end gap-2 w-full pt-4">
            <flux:modal.close>
                <flux:button variant="ghost">Cancel</flux:button>
            </flux:modal.close>
            <flux:button variant="primary" wire:click="updateGroupInfo">Save</flux:button>
        </div>
    </div>
</flux:modal>


{{-- Rejected Requests Modal --}}
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
        <div class="text-gray-500 dark:text-gray-400 text-sm">No rejected requests.</div>
    @endforelse
</flux:modal>


{{-- DELETE OR LEAVE GROUP --}}
<flux:modal name="delete-group" class="min-w-[22rem]">
    <div class="space-y-6">
        <div>
            <flux:heading size="lg">
                {{auth()->user()->id !== $member->id && auth()->user()->id === $selectedGroup->group_owner_id ?
                'Delete' : 'Leave' }} Group?
            </flux:heading>
            <flux:text class="mt-2">
                <p>You're about to 
                {{auth()->user()->id !== $member->id && auth()->user()->id === $selectedGroup->group_owner_id ?
                'Delete this' : 'Leave in this' }}
                  Group.</p>
                <p>This action cannot be reversed.</p>
            </flux:text>
        </div>
        <div class="flex gap-2">
            <flux:spacer />
            <flux:modal.close>
                <flux:button variant="ghost">Cancel</flux:button>
            </flux:modal.close>
            <flux:button type="submit" variant="danger" wire:click="leaveGroup({{$selectedGroup->id}})">
                {{auth()->user()->id !== $member->id && auth()->user()->id === $selectedGroup->group_owner_id ?
                'Delete' : 'Leave' }}
                Group
            </flux:button>
        </div>
    </div>
</flux:modal>