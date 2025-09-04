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
                @if(auth()->user()->role != 'user')
                <flux:modal.trigger name="add-positionOrcandidate">
                    <flux:navlist.item icon="plus" class="mb-5">
                        Add Position or Candidate
                    </flux:navlist.item>
                </flux:modal.trigger>
                @endif
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
<div x-data="{
        tab: 'join'
    }" class="w-full min-h-30 grid gap-6">

    <!-- Tab Buttons -->
    <div class="grid grid-cols-2 gap-2">
        <button
            @click="tab = 'join'"
            :class="tab === 'join' ? 'bg-red-900 text-white' : 'bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-white'"
            class="py-2 rounded-md text-sm font-medium transition"
        >
            Create Position
        </button>
        <button
            @click="tab = 'create'"
            :class="tab === 'create' ? 'bg-red-900 text-white' : 'bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-white'"
            class="py-2 rounded-md text-sm font-medium transition"
        >
            Create Candidate
        </button>
    </div>

    <!-- Create Candidate Form -->
    <div x-show="tab === 'create'" >
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
                    class="h-10"
                />

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

                <div class="flex justify-end gap-4">
                    <flux:modal.close>
                        <flux:button variant="ghost" size="sm">Close</flux:button>
                    </flux:modal.close>
                    <flux:button type="submit" size="sm">Create Candidate</flux:button>
                </div>
            </div>
        </form>
    </div>

    
   <!-- Create Position Form -->
<div x-show="tab === 'join'" >
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
            @keydown.arrow-up.prevent="if (highlightedIndex > 0) highlightedIndex--"
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
                       bg-white text-gray-900 
                       dark:bg-gray-800 dark:text-gray-100 
                       border-gray-300 dark:border-gray-700 
                       focus:ring-2 focus:ring-indigo-500 focus:outline-none"
            >

            <!-- Dropdown in flow -->
            <div x-show="open && filteredOptions().length"  class="mt-1">
                <ul class="w-full max-h-40 min-h-[40px] overflow-auto 
                           bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 
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
