<div class="space-y-6 p-4 bg-gray-100 dark:bg-gray-900 min-h-screen text-gray-800 dark:text-gray-200">
    <!-- Back -->
    <div class="flex justify-between items-center mb-6">
        <a href="{{ route('admin.voting.manage-voting') }}"
           class="inline-flex items-center text-sm font-medium text-maroon-700 dark:text-maroon-300 hover:underline">
            <flux:icon.chevron-left class="w-4 h-4 mr-1" />
            Back to Rooms
        </a>
        <flux:modal.trigger name="room-option" >
            <flux:icon.cog-6-tooth variant="solid" class="cursor-pointer hover:text-white"/>
        </flux:modal.trigger>
    </div>

    <flux:modal name="room-option" variant="flyout">
    <div class="space-y-6">
        <div>
            <flux:heading size="lg">Room Option</flux:heading>
            <flux:text class="mt-2">Make changes to your personal details.</flux:text>
        </div>
        <flux:input label="Name" placeholder="Your name" />
        <flux:input label="Date of birth" type="date" />
        <div class="flex">
            <flux:spacer />
            <flux:button type="submit" variant="primary">Save changes</flux:button>
        </div>
    </div>
</flux:modal>

<!-- Header -->
<div class="flex justify-between items-start">
    <div>
        <h1 class="text-2xl font-bold text-maroon-900 dark:text-white">{{ $room->title }}</h1>
        <p class="text-gray-600 dark:text-gray-400 text-sm">{{ $room->description }}</p>
    </div>

    <div class="text-sm text-right space-y-1">
        <div class="text-gray-500 dark:text-gray-400">Ends at</div>
        <div class="font-semibold" id="ends-in-clock">
            Loading...
        </div>

        <div class="font-bold {{ $this->statusTextColor }} ">
            Status: {{ $room->status }}
        </div>
    </div>
</div>

    <!-- Metrics -->
@php
    $totalVotes = $positions->flatMap->candidates->sum(fn($c) => $c->votes->count());

    $votingRate = $totalStudents ? round(($totalVotes / $totalStudents) * 100) : 0;
@endphp

    <div class="grid grid-cols-2 md:grid-cols-5 gap-4 text-center">
        <div class="bg-white dark:bg-gray-800 p-4 rounded-xl shadow">
            <div class="text-sm text-gray-500 dark:text-gray-400">Total Votes</div>
            <div class="text-xl font-bold text-maroon-700 dark:text-white">{{ $totalVotes }}</div>
        </div>
        <div class="bg-white dark:bg-gray-800 p-4 rounded-xl shadow">
            <div class="text-sm text-gray-500 dark:text-gray-400">Voting Rate</div>
            <div class="text-xl font-bold text-maroon-700 dark:text-white">{{ $votingRate }}%</div>
            <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2 mt-2">
                <div
                    class="h-2 rounded-full transition-all duration-500 ease-in-out
                        {{ $votingRate < 30 ? 'bg-red-500 dark:bg-red-400' : ($votingRate < 70 ? 'bg-yellow-500 dark:bg-yellow-400' : 'bg-green-600 dark:bg-green-400') }}"
                    style="width: {{ $votingRate }}%;">
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 p-4 rounded-xl shadow">
            <div class="text-sm text-gray-500 dark:text-gray-400">Active Races</div>
            <div class="text-xl font-bold text-maroon-700 dark:text-white">{{ count($positions) }}</div>
        </div>
        <div class="bg-white dark:bg-gray-800 p-4 rounded-xl shadow">
            <div class="text-sm text-gray-500 dark:text-gray-400">Start Time</div>
            <div class="text-xl font-bold text-maroon-700 dark:text-white" id="starts-in-clock">
                Loading...
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 p-4 rounded-xl shadow">
            <div class="text-sm text-gray-500 dark:text-gray-400">Status</div>
            <div class="text-xl font-semibold text-green-600 dark:text-green-400">{{ $room->status }}</div>
        </div>
    </div>

    


<!-- Election Summary -->
<div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6">
    <h3 class="font-bold mb-4 text-maroon-800 dark:text-white">Election Summary</h3>
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4">
        @foreach ($positions as $position)
            @php
                $votes = $position->candidates->sum('vote_count');
            @endphp
            <div class="rounded-lg border border-gray-200 dark:border-gray-700 p-4 shadow-sm bg-gray-50 dark:bg-zinc-900 text-center">
                <div class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">{{ $position->name }}</div>
                <div class="text-2xl font-bold text-maroon-800 dark:text-rose-300">{{ $votes }}</div>
                <div class="text-xs text-gray-400 dark:text-gray-500">votes</div>
            </div>
        @endforeach
    </div>
</div>


        <!-- Per Position Voting Charts -->
<div class="space-y-6">
    @php $hasCompetitivePosition = false; @endphp

    @foreach($positions as $position)
        @if($position->candidates->count() > 1)
            @php
                $hasCompetitivePosition = true;
                $total = $position->candidates->sum('vote_count') ?: 1;
            @endphp
            <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow">
                <h2 class="text-lg font-bold text-maroon-800 dark:text-white">{{ $position->name }}</h2>
                <div class="mt-4 space-y-3">
                    @foreach($position->candidates as $candidate)
                        @php
                            $percent = round(($candidate->vote_count / $total) * 100);
                            $color = '#' . substr(md5($candidate->id), 0, 6);
                        @endphp
                        <div>
                            <div class="flex justify-between items-center">
                                <div class="flex items-center gap-2">
                                    <div class="w-3 h-3 rounded-full" style="background-color: {{ $color }}"></div>
                                    <span class="font-medium">{{ $candidate->name }}</span>
                                </div>
                                <div class="text-sm text-gray-600 dark:text-gray-300">
                                    {{ $candidate->vote_count }} votes ({{ $percent }}%)
                                </div>
                            </div>
                            <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2 mt-1">
                                <div class="h-2 rounded-full transition-all duration-500 ease-in-out" style="width: {{ $percent }}%; background-color: {{ $color }}"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    @endforeach

    @if(!$hasCompetitivePosition)
        <div class="text-center text-gray-500 dark:text-gray-400 py-8">
            No competitive positions available yet. Please add more candidates to see results.

        </div>
    @endif
</div>

    

        <flux:dropdown position="right">
            
            <flux:button icon:trailing="chevron-down" > Add</flux:button>
                <flux:menu>
                    <flux:menu.item icon="plus" wire:click="addPosition">
                        Add Position
                    </flux:menu.item>
                    <flux:menu.item icon="plus" wire:click="addCandidate">
                        Add Candidate
                    </flux:menu.item>
                </flux:menu>
            </flux:dropdown>

            {{-- MODALS --}}

<flux:modal name="add-position" class="md:w-[40rem]">
    <form wire:submit.prevent="createPosition">
        <div class="space-y-6">
            <!-- Header -->
            <div>
                <flux:heading size="lg">Add Position</flux:heading>
                <flux:text class="mt-2">Define a new role or title for this voting room.</flux:text>
            </div>

            <!-- Position Name -->
            <flux:input
                label="Position Name"
                wire:model.defer="newPosition.name"
                placeholder="e.g. President"
                required
            />

            <!-- Optional Order Index -->
            <flux:input
                label="Display Order (optional)"
                type="number"
                wire:model.defer="newPosition.order_index"
                placeholder="e.g. 1"
                min="0"
            />

            <!-- Footer -->
            <div class="flex pt-4">
                <flux:modal.close>
                    <flux:button variant="ghost">Cancel</flux:button>
                </flux:modal.close>
                <flux:spacer />
                <flux:button type="submit" variant="primary">Create</flux:button>
            </div>
        </div>
    </form>
</flux:modal>


           <flux:modal name="add-candidate" class="md:w-[40rem]">
    <form wire:submit.prevent="createCandidate">
        <div class="space-y-6">
            <!-- Header -->
            <div>
                <flux:heading size="lg">Add Candidate</flux:heading>
                <flux:text class="mt-2">Register a new candidate for a specific position.</flux:text>
            </div>

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

            <!-- Color -->
        

            <div>
                <flux:input
                            label="Color (optional)"
                            type="color"
                            wire:model.defer="newCandidate.color"
                            placeholder="#FF0000 or red"
                        />

            </div>

            <!-- Footer -->
            <div class="flex pt-4">
                <flux:modal.close>
                    <flux:button variant="ghost">Cancel</flux:button>
                </flux:modal.close>
                <flux:spacer />
                <flux:button type="submit" variant="primary">Add Candidate</flux:button>
            </div>
        </div>
    </form>
</flux:modal>


    <!-- Candidate Cards -->
    @foreach ($positions as $position)
        <div>
            <h2 class="text-xl font-semibold mb-4 text-maroon-800 dark:text-white">{{ $position->name }} Candidates</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                @foreach ($position->candidates as $candidate)
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow overflow-hidden p-4 flex flex-col">
                        <img src="https://via.placeholder.com/300x200?text={{ urlencode($candidate->name) }}"
                             alt="{{ $candidate->name }}"
                             class="h-40 w-full object-cover rounded-md mb-4">
                        <h4 class="font-bold text-lg">{{ $candidate->name }}</h4>
                        <p class="text-sm text-gray-600 dark:text-gray-400">{{ $candidate->bio ?? 'No bio available.' }}</p>
                        <div class="mt-auto pt-4">
                            <button
    wire:click.prevent="voteCandidate({{ $candidate->id }})"
    class="w-full bg-[#7B2E2E] text-white py-2 rounded hover:bg-[#5c2222] transition">
    Vote for {{ explode(' ', $candidate->name)[0] }}
</button>

                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endforeach
</div>
<script>
    const startAt = new Date("{{ \Carbon\Carbon::parse($room->start_time)->timezone('Asia/Manila')->format('Y-m-d H:i:s') }}");
    const endAt   = new Date("{{ \Carbon\Carbon::parse($room->end_time)->timezone('Asia/Manila')->format('Y-m-d H:i:s') }}");

    function formatCountdown(targetTime, label) {
        const now = new Date();
        let diff = targetTime - now;

        if (diff <= 0) {
            return label === 'start' ? 'Started' : 'Ended';
        }

        const days = Math.floor(diff / (1000 * 60 * 60 * 24));
        diff %= 1000 * 60 * 60 * 24;

        const hours = Math.floor(diff / (1000 * 60 * 60));
        diff %= 1000 * 60 * 60;

        const minutes = Math.floor(diff / (1000 * 60));
        diff %= 1000 * 60;

        const seconds = Math.floor(diff / 1000);

        return [
            days    ? `${days}d`    : '',
            hours   ? `${hours}h`   : '',
            minutes ? `${minutes}m` : '',
            `${seconds}s`
        ].filter(Boolean).join(' ');
    }

    function updateClocks() {
        const startsClock = document.getElementById('starts-in-clock');
        const endsClock = document.getElementById('ends-in-clock');

        if (startsClock) {
            startsClock.textContent = formatCountdown(startAt, 'start');
        }

        if (endsClock) {
            endsClock.textContent = formatCountdown(endAt, 'end');
        }

        // Optional: trigger Livewire update or button disable here
    }

    updateClocks();
    setInterval(updateClocks, 1000);
</script>
