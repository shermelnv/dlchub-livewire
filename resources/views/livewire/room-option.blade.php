
    <flux:modal name="room-option" variant="flyout" class="h-screen w-sm">
        <div class="h-full flex flex-col justify-between ">
            <div class="space-y-4">
                <!-- Header -->
                <div>
                    <h1 class="text-xl font-bold text-zinc-800 dark:text-gray-100">Room Settings</h1>
                    <p class="text-sm text-zinc-800 dark:text-gray-100">Configure room details and manage room settings</p>
                </div>

                <!-- Room Information -->
                <div class="space-y-4">
                
                        <h2 class="text-lg font-semibold text-zinc-800 dark:text-gray-100">Room Information</h2>
                        <p>you can edit it here in voting room page <a href="{{route('voting')}}" class="underline text-blue-500">Voting Room</a> </p>
            
                    </div>
                    
                    {{-- SHOW WHEN EDIT ICON IS NOT CLICKED --}}
                    <div class="space-y-4">
                        <label class="text-sm text-zinc-800 dark:text-gray-100">Room Name</label>
                        <div class="mt-1 w-full rounded-lg dark:bg-zinc-800 bg-zinc-100 px-3 py-2 text-zinc-800 dark:text-gray-100">
                            {{ $room->title }}
                        </div>

                        <label class="text-sm text-zinc-800 dark:text-gray-100">Room Description</label>
                        <div class="mt-1 w-full rounded-lg dark:bg-zinc-800 bg-zinc-100 px-3 py-2 text-zinc-800 dark:text-gray-100">
                            {{ $room->description ? $room->description : 'No Description'}}
                        </div>


                        
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="text-sm text-zinc-800 dark:text-gray-100">Start Date</label>
                                <div class="mt-1 w-full rounded-lg dark:bg-zinc-800 bg-zinc-100 px-3 py-2 text-zinc-800 dark:text-gray-100">
                                    {{ \Carbon\Carbon::parse($room->start_time)->format('d/m/Y h:i A') }}
                                </div>
                            </div>

                
                            <div>
                                <label class="text-sm text-zinc-800 dark:text-gray-100">End Date</label>
                                <div class="mt-1 w-full rounded-lg dark:bg-zinc-800 bg-zinc-100 px-3 py-2 text-zinc-800 dark:text-gray-100">
                                    {{ \Carbon\Carbon::parse($room->end_time)->format('d/m/Y h:i A') }}
                                </div>
                            </div>
                        </div>
                    </div>
                
                <!-- Danger Zone -->
                @if(auth()->user()->role !== 'user' && auth()->id() === $room->creator_id)
                <div class="rounded-xl border border-red-600  p-6 space-y-3">
                    <h2 class="text-lg font-semibold text-red-500 flex items-center gap-2">
                        <flux:icon.exclamation-circle class="w-5 h-5" /> Delete {{ $room->title }}
                    </h2>
                    <p class="text-sm text-red-400">
                        Once you delete this {{ $room->title }}, there is no going back. Please be certain.
                    </p>
                    <flux:modal.trigger name="delete-room">
                        <flux:button variant="danger" >Delete {{ $room->title }}</flux:button>
                    </flux:modal.trigger>
            

                </div>
                @endif
            </div>
            <!-- Footer Buttons -->
        
            <flux:modal.close>
                <flux:button variant="ghost">Close</flux:button>
            </flux:modal.close>
            
        </div>
    </flux:modal>

    <flux:modal name="delete-room" class="min-w-sm">
                <div class="space-y-6">
                    <div>
                        <flux:heading size="lg">Delete Room {{ $room->title }}?</flux:heading>
                        <flux:text class="mt-2">
                            <p>You're about to delete this Room: {{ $room->title }}.</p>
                            <p>This action cannot be reversed.</p>
                        </flux:text>
                    </div>
                    <div class="flex gap-2">
                        <flux:spacer />
                        <flux:modal.close>
                            <flux:button variant="ghost">Cancel</flux:button>
                        </flux:modal.close>
                        <flux:button type="submit" variant="danger" wire:click="deleteRoom({{ $room->id }})">Delete {{ $room->title }}</flux:button>
                    </div>
                </div>
            </flux:modal>

    <flux:modal name="add-positionOrcandidate" class="w-xs lg:w-full" :closable="false">
            <div x-data="{ tab: 'newPosition' }" class="w-full min-h-30 grid gap-6">

                <!-- Tab Buttons -->
                <div class="grid grid-cols-2 gap-2">
                    <button
                        @click="tab = 'newPosition'"
                        :class="tab === 'newPosition' ? 'bg-red-900 text-white' : 'bg-zinc-100 dark:dark:bg-zinc-800 text-gray-700 dark:text-white'"
                        class="py-2 rounded-md text-sm font-medium transition"
                    >
                        Create Position
                    </button>
                    <button
                        @click="tab = 'newCandidate'"
                        :class="tab === 'newCandidate' ? 'bg-red-900 text-white' : 'bg-zinc-100 dark:dark:bg-zinc-800 text-gray-700 dark:text-white'"
                        class="py-2 rounded-md text-sm font-medium transition"
                    >
                        Create Candidate
                    </button>
                </div>

                <!-- Create Candidate Form -->
                <div x-show="tab === 'newCandidate'" >
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
                                wire:model="newCandidate.name"
                                placeholder="e.g. Juan Dela Cruz"
                                required
                                class="h-10"
                            />
                            
                            @error('newCandidate.name')
                            <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                    

                            <!-- Short Name -->
                            <flux:input
                                label="Short Name (optional)"
                                wire:model.defer="newCandidate.short_name"
                                placeholder="e.g. Juan"
                                class="h-10"
                            />

                            <!-- Bio -->
                            <flux:textarea
                                label="Biography (optional)"
                                wire:model.defer="newCandidate.bio"
                                placeholder="Tell us something about this candidate..."
                            />

                            <div class="grid lg:grid-cols-2 gap-2">
                                    <!-- File input -->
                                    <div class="overflow-hidden w-full">

                                    
                                        <flux:input 
                                            type="file" 
                                            wire:model="candidate_image" 
                                            accept="image/*"
                                            label="Candidate Image"
                                        />
                                    </div>

                                    <!-- Preview below -->
                                    <div class="flex justify-start lg:justify-end items-end ">
                                        @if($candidate_image)
                                            <flux:modal.trigger name="candidate_image">
                                                <flux:button>View Image</flux:button>
                                            </flux:modal.trigger>
                                            <flux:modal name="candidate_image">
                                                <div class="space-y-4">
                                                    <flux:heading>Candidate Image</flux:heading>

                                                    <img src="{{ $candidate_image->temporaryUrl() }}" class="lg:w-md w-sm h-full object-cover rounded-lg border">
                                                </div>
                                            </flux:modal>
                                        @endif
                                    </div>
                                </div>


                            <div class="flex justify-end gap-4">
                                <flux:modal.close>
                                    <flux:button variant="ghost" size="sm">Close</flux:button>
                                </flux:modal.close>
                                <flux:button 
                                    type="submit" 
                                    size="sm"
                                    wire:loading.attr="disabled" 
                                    wire:target="candidate_image"
                                    >
                                    Create Candidate
                                </flux:button>
                            </div>
                        </div>
                    </form>
                </div>

                
            <!-- Create Position Form -->
            <div x-show="tab === 'newPosition'" >
                <form wire:submit.prevent="createPosition" class="space-y-6">
                    <div x-data="{
                            query: @entangle('newPosition.name'),
                            options: ['President','Vice President','Secretary','Treasurer'],
                            open: false,
                            highlightedIndex: -1,
                            filteredOptions() {
                                return this.options.filter(o => o.toLowerCase().includes(this.query.toLowerCase()));
                            }
                        }"
                        x-init="$watch('query', () => highlightedIndex = -1); $watch('tab', () => open = false)"
                        @keydown.arrow-down.prevent="if (highlightedIndex < filteredOptions().length -1) highlightedIndex++"
                        @keydown.arrow-up.prevent="if (highlightedIndex > 0) highlightedIndexe--"
                        @keydown.enter.prevent="if (highlightedIndex > -1) { query = filteredOptions()[highlightedIndex]; open = false; highlightedIndex = -1 }"
                        @click.outside="open = false"
                        class="relative"
                    >
                        <!-- Input -->
                        <input 
                            type="text" 
                            x-model="query" 
                            @focus="open = true" 
                            placeholder="Choose or type position" 
                            class="border rounded p-2 w-full h-10
                                text-gray-900 
                                dark:dark:bg-zinc-800 bg-zinc-100 dark:text-gray-100 
                                border-gray-300 dark:border-gray-700 
                                focus:ring-2 focus:ring-indigo-500 focus:outline-none"
                        >

                        @error('newPosition.name')
                            <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                        

                        <!-- Dropdown in flow -->
                        <div x-show="open && filteredOptions().length"  class="mt-1">
                            <ul class="w-full mah-40 min-h-[40px] overflow-auto 
                                    dark:dark:bg-zinc-800 bg-zinc-100 border border-gray-300 dark:border-gray-700 
                                    rounded shadow">
                                <template x-for="(option, index) in filteredOptions()" :key="option">
                                    <li 
                                        @mousedown.prevent="query = option; open = false; highlightedIndex = -1" 
                                        :class="index === highlightedIndex ? 'bg-indigo-100 dark:bg-gray-700' : 'hover:bg-gray-100 dark:hover:bg-gray-700'"
                                        class="p-2 cursor-pointer"
                                        x-text="option"
                                    ></li>
                                </template>
                            </ul>
                        </div>
                    </div>

                    <!-- Form Buttons -->
                    <div class="flex justify-end gap-4">
                        <flux:modal.close>
                            <flux:button variant="ghost" size="sm">Close</flux:button>
                        </flux:modal.close>
                        <flux:button type="submit" size="sm">Create Position</flux:button>
                    </div>
                </form>
            </div>


            </div>
    </flux:modal>

    <flux:modal name="candidate-card" class="min-w-[20rem] maw-md" :closable="false">
        @if($selectedCandidate)
            <div class="space-y-4">
                

                    @if ($selectedCandidate->photo_url)
                                {{-- Show uploaded candidate image --}}
                                <img 
                                    src="{{ Storage::disk('digitalocean')->url($selectedCandidate->photo_url) }}"
                                    alt="{{ $selectedCandidate->name }}"
                                    class="h-full w-full object-cover rounded-md mb-4"
                                >
                                @else
                                    {{-- "no image" placeholder --}}

                                    <div class="h-40 lg:h-60 w-full flex items-center justify-center bg-gray-200 rounded-md mb-4 text-gray-500">
                                        No image
                                    </div>
                                @endif

                
                <p class="font-bold text-2xl">{{$selectedCandidate->name}}</p>
                <p class="text-justify text-gray-500 break-words ">{{$selectedCandidate->bio}}</p>
            </div>
            <div class="flex justify-end">
                <flux:modal.close>
                    <flux:button variant="ghost">Close</flux:button>
                </flux:modal.close>
            </div>
        @else
        <div class="p-6 text-center text-zinc-800 dark:text-gray-100">Loading candidate data...</div>
        @endif
    </flux:modal>

    <flux:modal name="edit-candidate" class="min-w-sm">
        @if($editCandidate)
        <div class="space-y-6">
            <form wire:submit.prevent="updateCandidate">
                        <div class="space-y-6">

                            <div >

                            </div>
                            
                            <!-- Name -->
                            <flux:input
                                label="Full Name"
                                wire:model.defer="editCandidate.name"
                                required
                                class="h-10"
                            />
                            
                            <flux:input
                                label="Short Name (optional)"
                                wire:model.defer="editCandidate.short_name"
                            />

                            <flux:textarea
                                label="Biography (optional)"
                                wire:model.defer="editCandidate.bio"
                            />


                            <div class="flex flex-col gap-2">
                                    <!-- File input -->
                                    <div class="overflow-hidden w-full">

                                    
                                        <flux:input 
                                            type="file" 
                                            wire:model="candidate_image" 
                                            accept="image/*"
                                            label="Candidate Image"
                                        />
                                    </div>

                                    <!-- Preview below -->
                              
                                        @if($candidate_image)
                                           
                                          <div class="flex">
                                      
                                                    <img src="{{ $candidate_image->temporaryUrl() }}" class="w-full h-full object-cover rounded-lg border">
                                            
                                          </div>
                                                
                                           
                                        @endif
                               
                                </div>


                            <div class="flex justify-end gap-4">
                                <flux:modal.close>
                                    <flux:button variant="ghost" size="sm">Close</flux:button>
                                </flux:modal.close>
                                <flux:button 
                                    type="submit" 
                                    size="sm"
                                    wire:loading.attr="disabled" 
                                    wire:target="candidate_image"
                                    >
                                    Update Candidate
                                </flux:button>
                            </div>
                        </div>
                    </form>
        </div>
        @endif
    </flux:modal>
    <flux:modal name="delete-candidate" class="min-w-[22rem]">
    <div class="space-y-6">
        <div>
            <flux:heading size="lg">Delete candidate?</flux:heading>
            <flux:text class="mt-2">
                <p>You're about to delete this candidate.</p>
                <p>This action cannot be reversed.</p>
            </flux:text>
        </div>

        <div class="flex gap-2">
            <flux:spacer />

            <flux:modal.close>
                <flux:button variant="ghost">Cancel</flux:button>
            </flux:modal.close>

            <flux:button 
                type="button" 
                variant="danger" 
                wire:click="deleteCandidate"
            >
                Delete candidate
            </flux:button>
        </div>
    </div>
</flux:modal>

