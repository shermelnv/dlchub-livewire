<flux:modal name="room-option" variant="flyout">
    <div class="space-y-6 overflow-y-auto">
        <!-- Header -->
        <div class="flex flex-col items-center">
            <flux:avatar circle src="https://unavatar.io/x/calebporzio" />
            <flux:text class="mt-2">Voting Room Name</flux:text>
        </div>


        <!-- Position List -->
        <flux:navlist>
            <flux:navlist.group heading="Position" expandable :expanded="false">
                
                <flux:modal.trigger name="add-positionOrcandidate">
                    <flux:navlist.item icon="plus" class="mb-5">
                        Add Position or Candidate
                    </flux:navlist.item>
                </flux:modal.trigger>
                <!-- President -->

                {{-- CANDIDATE AND POSITION LIST --}}
                @foreach ($positions as $position)
                    <flux:navlist.group heading="{{$position->name}}" expandable :expanded="false">
                        
                        @foreach ($position->candidates as $candidate)
                        <flux:modal.trigger wire:click="candidateCard({{$candidate->id}})">
                            <flux:navlist.item >
                                <div class="flex items-center gap-3">
                                    <flux:avatar circle src="https://unavatar.io/{{$candidate->name}}" class="w-6 h-6" />
                                    <span>{{$candidate->name}}</span>
                                </div>
                            </flux:navlist.item>
                        </flux:modal.trigger>
                        @endforeach
                        
                    </flux:navlist.group>
                @endforeach

            </flux:navlist.group>
        </flux:navlist>
    </div>

 



</flux:modal>

    <flux:modal name="add-positionOrcandidate" class="w-xs lg:w-full" :closable="false">
        <div x-data="{ tab: 'create' }" class="w-full h-auto grid gap-6">
            <!-- Tab Buttons -->
            <div class="grid grid-cols-2 gap-2">
                <button
                    @click="tab = 'create'"
                    :class="tab === 'create' ? 'bg-red-900 text-white' : 'bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-white'"
                    class="py-2 rounded-md text-sm font-medium transition"
                >
                    Create Candidate
                </button>
                <button
                    @click="tab = 'join'"
                    :class="tab === 'join' ? 'bg-red-900 text-white' : 'bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-white'"
                    class="py-2 rounded-md text-sm font-medium transition"
                >
                    Create Position
                </button>
            </div>

            <!-- Create Group Form -->
            <div x-show="tab === 'create'"  >
                <form wire:submit.prevent="createCandidate">
                    <div class="space-y-6">

                        <!-- Position Selector -->
                <flux:select label="Position" wire:model.defer="newCandidate.position_id">
                    <option value="">Select Position</option>
                    @foreach ($positions as $position)
                        <option value="{{ $position->id }}">{{ $position->name }}</option>
                    @endforeach
                </flux:select>
                        <!-- Name -->
                <flux:input
                    label="Full Name"
                    wire:model.defer="newCandidate.name"
                    placeholder="e.g. Juan Dela Cruz"
                    required
                />

                <!-- Short Name -->
                <flux:input
                    label="Short Name (optional)"
                    wire:model.defer="newCandidate.short_name"
                    placeholder="e.g. Juan"
                />

                <!-- Bio -->
                <flux:textarea
                    label="Biography (optional)"
                    wire:model.defer="newCandidate.bio"
                    placeholder="Tell us something about this candidate..."
                />

                <!-- Photo URL -->
                <flux:input
                    label="Photo URL (optional)"
                    wire:model.defer="newCandidate.photo_url"
                    placeholder="https://example.com/photo.jpg"
                    type="url"
                />

                        <div class="flex justify-end gap-4">
                            <flux:modal.close>
                                <flux:button variant="ghost" size="sm">Close</flux:button>
                            </flux:modal.close>
                            
                            <flux:button type="submit" size="sm">Create</flux:button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Join Group Form -->
            <div x-show="tab === 'join'"  >
                <form wire:submit.prevent="createPosition">
                    <div class="space-y-6">
                        <!-- Position Name -->
                        <flux:input
                            label="Position Name"
                            wire:model.defer="newPosition.name"
                            placeholder="e.g. President"
                            required
                        />
                        <div class="flex justify-end gap-4">
                            <flux:modal.close>
                                <flux:button variant="ghost" size="sm">Close</flux:button>
                            </flux:modal.close>
                            <flux:button type="submit" size="sm">Join</flux:button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </flux:modal>

    <flux:modal name="candidate-card" class="min-w-[20rem] max-w-md" :closable="false">
        @if($selectedCandidate)
            <div class="space-y-4">
                <div class="w-full h-40">

                    @if($selectedCandidate->photo_url)
                    <img src="https://i.pravatar.cc/300?u={{ $selectedCandidate->id }}" class="object-cover h-full w-full">
                    @else
                    <img src="https://i.pravatar.cc/300?u={{ $selectedCandidate->id }}" class="object-cover h-full w-full">
                    @endif
                </div>
                <p class="font-bold text-2xl">{{$selectedCandidate->name}}</p>
                <p class="text-justify text-gray-500">{{$selectedCandidate->bio}}</p>
            </div>
            <div class="flex justify-end">
                <flux:modal.close>
                    <flux:button variant="ghost">Close</flux:button>
                </flux:modal.close>
            </div>
        @else
        <div class="p-6 text-center text-gray-400">Loading candidate data...</div>
        @endif
    </flux:modal>
