<div 
    x-data="{ roomId: @js($room->id) }" 
    x-init="
        Echo.private(`voting-room.${roomId}`)
            .listen('.voted.candidate', (e) => {
                Livewire.dispatch('votedCandidate');
                console.log(e);
            });
        
        Echo.channel('manage-user')
            .listen('.user.registered', (e) => {
                console.log('received', e.user);
                Livewire.dispatch('newUser');
            });

        Echo.channel('voting-room')
            .listen('.room.expired', (e) => {
                console.log('received');
                Livewire.dispatch('roomExpired');
            });
    "
    class="space-y-6 p-4 bg-gray-100 dark:bg-gray-900 min-h-screen text-gray-800 dark:text-gray-200"
>
    <!-- Back -->
    <div class="flex justify-between items-center mb-6">
        <a 
            href="{{route('voting')}}"
            wire:navigate

            class="inline-flex items-center text-sm font-medium text-maroon-700 dark:text-maroon-300 hover:underline"
        >
            <flux:icon.chevron-left class="w-4 h-4 mr-1" />
            Back to Rooms
        </a>
        @if($room->status != 'Closed')


        <flux:modal.trigger name="room-option">
            <flux:icon.cog-6-tooth variant="solid" class="cursor-pointer" />
        </flux:modal.trigger>
        @endif
    </div>

    @if($room->status !== 'Closed')
        @include('livewire.room-option')

        <!-- Header -->
        <div class="flex justify-between items-start">
            <div>
                <h1 class="text-2xl font-bold text-maroon-900 dark:text-white">{{ $room->title }}</h1>
                <p class="text-gray-600 dark:text-gray-400 text-sm">{{ $room->description }}</p>
            </div>
              
                    <div>
            Status: 
            <span class="text-xl font-semibold text-green-600 dark:text-green-400">{{ $room->status }}</span>
        </div>
                    
            
        </div>

       
        <!-- Metrics -->
        @php
            $totalVotes = \App\Models\Vote::whereHas('candidate.position', function ($query) use ($room) {
                $query->where('voting_room_id', $room->id);
            })->count();

            $totalUniqueVoters = \App\Models\Vote::whereHas('candidate.position', function ($query) use ($room) {
                $query->where('voting_room_id', $room->id);
            })->distinct('user_id')->count('user_id');

            $totalStudents = \App\Models\User::where('role', 'user')->where('status', 'approved')->count();
            $votingRate = $totalStudents ? round(($totalUniqueVoters / $totalStudents) * 100) : 0;
        @endphp

        @if(auth()->user()->role === 'admin' || auth()->user()->role === 'super-admin' || auth()->user()->id === $room->creator_id)
            <div class="flex justify-end gap-4">
                @if($room->status === 'Ongoing' )
                    <flux:modal.trigger name="voters-list">
                        <flux:button icon="document-text" >Voters List</flux:button>
                    </flux:modal.trigger>
                @elseif($room->status === 'Pending')
                    <flux:modal.trigger name="add-positionOrcandidate">
                    <flux:button icon="plus">Create Position or Candidate</flux:button>
                    </flux:modal.trigger>
                @endif
            </div>
        @endif
            <flux:modal name="voters-list" class="min-w-sm">
                <div class="space-y-4">
                    <flux:heading size="lg">Voters List</flux:heading>
                
                    {{-- <ul class="space-y-2">
                        @forelse ($this->voters() as $vote)
                            <li class="flex items-center gap-2">
                                <flux:avatar src="{{ $vote->user->avatar_url ?? 'https://i.pravatar.cc/50?u=' . $vote->user->id }}" class="size-6"/>
                                <span>{{ $vote->user->name }} ({{ $vote->user->email }})</span>
                            </li>
                        @empty
                            <li class="text-gray-400">No voters yet.</li>
                        @endforelse
                    </ul> --}}

                    <table class="w-full text-sm text-left">
                        <thead class="bg-gray-200 dark:bg-gray-700">
                            <tr>
                                <th class="px-4 py-2">Voter Info</th>
                                <th class="py-2">Action</th>
                            </tr>
                        </thead>
                    </table>

                    <div class="max-h-56 overflow-y-auto scrollbar-hover">
                        <table class="w-full text-sm text-left space-y-6">
                            <tbody>
                                @foreach ($this->voters() as $vote)
                                    <tr class="border-b dark:border-gray-600">
                                        <td class="px-4 py-2 flex items-center gap-2">
                                            @if ($vote->user->profile_image)
                                                <flux:avatar 
                                                    src="{{ Storage::disk('digitalocean')->url($vote->user->profile_image) }}"
                                                    class="size-10 object-contain overflow-hidden" />
                                            @else
                                                <flux:avatar circle :initials="$vote->user->initials()" class="size-10 " />
                                            @endif
                                            <div>
                                                <strong>{{ $vote->user->name }}</strong>
                                                <p class="text-xs">{{ $vote->user->email }}</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    
                      
                    
                </div>
            </flux:modal>

        
        {{-- Voting Stats --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-center">
            <div class="bg-white dark:bg-gray-800 p-4 rounded-xl shadow">
                <div class="text-sm text-gray-500 dark:text-gray-400">Total Votes</div>
                <div class="text-xl font-bold text-maroon-700 dark:text-white">{{ $totalUniqueVoters  . " / " . $totalStudents }}</div>
            </div>

            <div class="bg-white dark:bg-gray-800 p-4 rounded-xl shadow">
                <div class="text-sm text-gray-500 dark:text-gray-400">Voting Rate</div>
                <div class="text-xl font-bold text-maroon-700 dark:text-white">{{ $votingRate }}%</div>
                <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2 mt-2">
                    <div 
                        class="h-2 rounded-full transition-all duration-500 ease-in-out
                            {{ $votingRate < 30 ? 'bg-red-500 dark:bg-red-400' : ($votingRate < 70 ? 'bg-yellow-500 dark:bg-yellow-400' : 'bg-green-600 dark:bg-green-400') }}"
                        style="width: {{ $votingRate }}%;"
                    ></div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 p-4 rounded-xl shadow">
                <div class="text-sm text-gray-500 dark:text-gray-400">Start Time</div>
                {{-- <div class="text-xl font-bold text-maroon-700 dark:text-white" id="starts-in-clock">Loading...</div> --}}
                <div 
                    wire:ignore
                    class="text-xl font-bold text-maroon-700 dark:text-white"
                    x-data="{
                        startTime: new Date('{{ $room->start_time }}'),
                        now: new Date(),
                        remainingText() {
                            let diff = (this.startTime - this.now) / 1000; // seconds
                            if (diff <= 0) return 'Started';

                            let days = Math.floor(diff / 86400);
                            diff %= 86400;
                            let hours = Math.floor(diff / 3600);
                            diff %= 3600;
                            let minutes = Math.floor(diff / 60);
                            let seconds = Math.floor(diff % 60);

                            return `${days}d ${hours}h ${minutes}m ${seconds}s`;
                        }
                    }"
                    x-init="setInterval(() => { now = new Date(); }, 1000)"
                    x-text="remainingText()"
                >
                    Loading...
                </div>

                <div 
                    wire:ignore
                    class="text-sm text-gray-500 dark:text-gray-400"
                    x-data="{
                        expires: new Date('{{ $room->start_time }}'),
                        formatted() {
                            return this.expires.toLocaleString('en-US', {
                                weekday: 'long',    // Saturday
                                hour: 'numeric',    // 12
                                minute: '2-digit',  // 30
                                hour12: true        // PM/AM
                            });
                        }
                    }"
                    x-text="formatted()">
                </div>

            </div>

            <div class="bg-white dark:bg-gray-800 p-4 rounded-xl shadow">
                <div class="text-sm text-gray-500 dark:text-gray-400">End time</div>
                <div 
                            wire:ignore
                            class="text-xl font-bold text-maroon-700 dark:text-white"
                            x-data="{
                                expires: new Date('{{ $room->end_time    }}'),
                                now: new Date(),
                                remaining() {
                                    let diff = Math.max(this.expires - this.now, 0) / 1000; // seconds
                                    let days = Math.floor(diff / 86400);
                                    diff %= 86400;
                                    let hours = Math.floor(diff / 3600);
                                    diff %= 3600;
                                    let minutes = Math.floor(diff / 60);
                                    let seconds = Math.floor(diff % 60);
                                    return `${days}d ${hours}h ${minutes}m ${seconds}s`;
                                }
                            }"
                            x-init="setInterval(() => { now = new Date(); }, 1000)"
                            x-text="remaining()">
                            Loading...
                </div>
                 <div 
                    wire:ignore
                    class="text-sm text-gray-500 dark:text-gray-400"
                    x-data="{
                        expires: new Date('{{ $room->end_time }}'),
                        formatted() {
                            return this.expires.toLocaleString('en-US', {
                                weekday: 'long',    // Saturday
                                hour: 'numeric',    // 12
                                minute: '2-digit',  // 30
                                hour12: true        // PM/AM
                            });
                        }
                    }"
                    x-text="formatted()">
                </div>
            </div>
        </div>

        <!-- Per Position Voting Charts -->
        <div class="space-y-6">
            @foreach($positions as $position)
                @if($position->candidates->count() > 0)
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
                                <div class="relative w-full h-8 md:h-12 overflow-hidden rounded-xs md:rounded-md mb-2 border border-gray-500">
                                    <div class="absolute top-0 left-0 h-full bg-sky-600 transition-all duration-500 ease-in-out" style="width: {{ $percent }}%"></div>
                                    <div class="relative z-10 flex justify-between items-center h-full pr-4 text-white">
                                        <div class="flex items-center gap-3">
                                            <div class="flex items-center gap-2">
                                                <span class="size-8 md:size-12 text-lg bg-red-900 rounded-xs md:rounded-md flex items-center justify-center font-bold">{{ $loop->iteration }}</span>
                                                <span class="font-bold text-black dark:text-white">{{ $candidate->name }}</span>
                                            </div>
                                        </div>
                                        <div class="text-xs md:text-sm font-medium text-black dark:text-white">
                                            {{ $candidate->vote_count }} votes ({{ $percent }}%)
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            @endforeach
        </div>

        <!-- Candidate Cards -->
        @foreach ($positions as $position)
           @php
        // Check if the current user has voted in this position
        $userVotedInPosition = $position->candidates
            ->pluck('votes')
            ->flatten()
            ->contains('user_id', auth()->id());
        $buttonLabel = '';

            if ($room->status !== 'Ongoing') {
                $buttonLabel = 'Voting Unavailable';
            } elseif ($userVotedInPosition) {
                $buttonLabel = 'Already Voted';
            } elseif (auth()->user()->role !== 'user') {
                $buttonLabel = 'Only user can vote';
            } else {
                $buttonLabel = 'Vote';
            }
        @endphp
            <div class="bg-white dark:bg-gray-800 p-4 rounded-lg">
                <flux:heading class="uppercase text-lg lg:text-xl text-black dark:text-white">{{ $position->name }} Candidates</flux:heading>
                <flux:text class="mb-4 text-xs lg:text-base text-black dark:text-white">Choose your {{ $position->name }} candidate</flux:text>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                    @forelse ($position->candidates as $candidate)
                    {{-- <flux:modal.trigger wire:click="candidateCard({{$candidate->id}})"> --}}
                        <div class="bg-white dark:bg-gray-900 rounded-xl shadow overflow-hidden p-4 flex flex-col">
                            @if(auth()->user()->role !== 'user' && auth()->user()->id === $room->creator_id)
                                <div class="flex justify-end items-center gap-2 mb-2 relative" x-data="{ open: false }">
                                    <!-- Ellipsis Button (Vertical) -->
                                    <flux:button size="xs" variant="ghost" @click="open = !open">
                                        ⋮
                                    </flux:button>

                                    <!-- Dropdown -->
                                    <div 
                                        x-show="open" 
                                        @click.outside="open = false"
                                        x-transition
                                        class="absolute right-0 mt-8 w-28 bg-white dark:bg-gray-900 border rounded-md shadow-lg z-50"
                                    >
                                        <button 
                                            class="block w-full text-left px-3 py-1.5 text-sm hover:bg-gray-50 hover:text-black" 
                                            wire:click="edit({{ $candidate->id }})"
                                        >
                                            Edit
                                            <svg wire:loading wire:target="edit" class="animate-spin h-4 w-4 text-gray-600 absolute right-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.37 0 0 5.37 0 12h4z"></path>
                                            </svg>
                                        </button>
                                        <button 
                                            class="block w-full text-left px-3 py-1.5 text-sm text-red-600 hover:bg-red-50"
                                            wire:click="remove({{ $candidate->id }})"
                                        >
                                            Delete
                                            <svg wire:loading wire:target="remove" class="animate-spin h-4 w-4 text-gray-600 absolute right-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.37 0 0 5.37 0 12h4z"></path>
                                            </svg>
                                        </button>
                                </div>
                            </div>

                            @endif

                            @if ($candidate->photo_url)
                                {{-- Show uploaded candidate image --}}
                                <img 
                                  
                                    src="{{ Storage::disk('digitalocean')->url($candidate->photo_url) }}"
                                    alt="{{ $candidate->name }}"
                                    class="h-20 md:h-60 w-full object-cover rounded-md mb-4"
                                >

                                @else
                                    {{-- "no image" placeholder --}}

                                    <div class="h-20 md:h-60 w-full flex items-center justify-center bg-gray-200 rounded-md mb-4 text-gray-500">
                                        No image
                                    </div>
                                @endif
                            <div class="flex justify-between items-start">
                                <h4 class="font-bold text-sm md:text-lg h-[2.5rem]">{{ $candidate->name }}</h4>
                                <flux:button
                                    variant="ghost"
                                    wire:click="candidateCard({{ $candidate->id }})"
                                    wire:loading.attr="disabled"
                                    wire:target="candidateCard({{ $candidate->id }})"
                                    wire:key="candidateCard({{ $candidate->id }})"
                                    size="sm"
                                >
                                    <span class="hidden lg:inline" wire:loading.remove wire:target="candidateCard({{ $candidate->id }})">
                                        View
                                    </span>
                                    <flux:icon.eye wire:loading.remove wire:target="candidateCard({{ $candidate->id }})" class="lg:hidden"/>
                                    <span wire:loading wire:target="candidateCard({{ $candidate->id }})">
                                        <flux:icon.loading/>
                                    </span>
                                </flux:button>

                            </div>
                            
                            <div class="h-[3.5rem]">
                                <p class="text-xs md:text-sm text-gray-600 dark:text-gray-400 break-words line-clamp-3">
                                    {{ $candidate->bio ?? 'No bio available.' }}
                                </p>
                            </div>

                            <div class="relative group my-4">
                                <button
                                    wire:click.prevent="voteCandidate({{ $candidate->id }})"
                                    @disabled($room->status !== 'Ongoing' || $userVotedInPosition || auth()->user()->role !== 'user')
                                    wire:loading.attr="disabled"
                                    wire:target="voteCandidate({{ $candidate->id }})"
                                    class="w-full py-2 rounded transition text-xs md:text-base
                                        {{ $room->status !== 'Ongoing' || $userVotedInPosition || auth()->user()->role !== 'user'
                                            ? 'bg-gray-400 text-white cursor-not-allowed'
                                            : 'bg-[#7B2E2E] text-white hover:bg-[#5c2222] cursor-pointer'
                                        }}"
                                >
                                    <span wire:loading.remove wire:target="voteCandidate({{ $candidate->id }})">
                                        {{ $buttonLabel }}
                                    </span>
                                    <span wire:loading wire:target="voteCandidate({{ $candidate->id }})">
                                        Voting…
                                    </span>
                                    
                                </button>
                                @if($room->status !== 'Ongoing')
                                    <div class="absolute top-full mt-1 text-xs bg-black text-white px-2 py-1 rounded hidden group-hover:block">
                                        Voting opens once the election is ongoing.
                                    </div>
                                @endif
                            </div>
                        </div>
                    {{-- </flux:modal.trigger> --}}
                    @empty
                        <flux:text class="col-span-2 md:col-span-3 text-center text-xs md:text-lg">NO CANDIDATE AT THE MOMENT</flux:text>
                    @endforelse
                </div>
            </div>
        @endforeach


    @else
    
        @php
            $start_time = Carbon\Carbon::parse($room->start_time);
            $end_time = Carbon\Carbon::parse($room->end_time);
            $totalVotes = \App\Models\Vote::whereHas('candidate.position', function ($query) use ($room) {
            $query->where('voting_room_id', $room->id);
            })
            ->distinct('user_id') // count distinct voters
            ->count('user_id');
        @endphp
            <div>
                <h1 class="text-xl font-bold text-gray-900 dark:text-gray-100">{{ $room->title }}</h1>
                <p class="text-gray-600 dark:text-gray-300 text-sm">{{ $room->description }}</p>
                <p class="text-gray-500 dark:text-gray-400 text-xs mt-1">
                    Election Date: {{ $start_time->format('m/d/y') }} - {{ $end_time->format('m/d/y') }} &nbsp;&bull;&nbsp; {{ $totalVotes }} users voted
                </p>
            </div>

 
        @if($positions->isNotEmpty() && $positions->pluck('candidates')->flatten()->isNotEmpty())
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
             @if($positions->isEmpty())
                <div class="col-span-full text-center text-gray-500 dark:text-gray-400 py-10">
                    This voting is blank
                </div>
            @else
            @foreach($positions as $position)
                @php
                    $maxVotes = $position->candidates->max('vote_count');
                    $winners = $position->candidates->where('vote_count', $maxVotes);
                @endphp
                <div class="max-w-xl border-2 p-4 font-sans space-y-6 bg-white dark:bg-gray-900 rounded-md shadow-md transition-colors duration-300">
                    <div class="flex justify-between">
                        <h1 class="text-xl font-bold text-gray-900 dark:text-gray-100">{{ $position->name }}</h1>
                        <div class="flex justify-end">
                            <div class="text-right space-y-0.5">
                                @if($winners->count())
                                    @foreach($winners as $winner)
                                        <p class="text-green-700 dark:text-green-400 font-semibold text-lg">{{ $winner->name }}</p>
                                    @endforeach
                                    <flux:badge color="lime">
                                        {{ $winners->count() > 1 ? 'Winners' : 'Winner' }}
                                    </flux:badge>
                                @else
                                    <p class="text-gray-500">No winner yet</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="space-y-4">
                        @forelse($position->candidates as $candidate)
                            @php
                                $total = $position->candidates->sum('vote_count') ?: 1;
                                $percent = round(($candidate->vote_count / $total) * 100);
                            @endphp
                            <div class="relative w-full h-8 md:h-12 flex items-center border border-gray-500 rounded-md overflow-hidden">
                                <div class="flex-shrink-0 w-10 md:w-12 h-full bg-red-900 flex items-center justify-center font-bold text-white rounded-l-md select-none z-10">
                                    {{ $loop->iteration }}
                                </div>
                                <div class="relative flex-grow h-full">
                                    <div class="absolute top-0 left-0 h-full bg-sky-600 transition-all duration-500 ease-in-out" style="width: {{ $percent }}%"></div>
                                    <div class="relative z-5 flex justify-between items-center h-full px-4 text-white font-semibold select-none">
                                        <div class="truncate">{{ $candidate->name }}</div>
                                        <div class="text-xs md:text-sm font-medium text-black dark:text-white">{{ $candidate->vote_count }} votes ({{ $percent }}%)</div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <flux:text class="text-center text-sm text-gray-500">No candidates available.</flux:text>
                        @endforelse
                    </div>
                </div>
            @endforeach
            @endif

        </div>
            @else
        <div class="text-center text-gray-500 dark:text-gray-400 py-10">
            This voting is blank
        </div>
    @endif
    @endif


</div>

<script>
    const roomStatus = "{{ $room->status }}";
    const startAt = new Date("{{ \Carbon\Carbon::parse($room->start_time)->timezone('Asia/Manila')->format('Y-m-d H:i:s') }}");
    const endAt   = new Date("{{ \Carbon\Carbon::parse($room->end_time)->timezone('Asia/Manila')->format('Y-m-d H:i:s') }}");

    function formatCountdown(targetTime) {
        const now = new Date();
        let diff = targetTime - now;
        if (diff <= 0) return 'Ended';

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
        const now = new Date();
        const startsClock = document.getElementById('starts-in-clock');
        const endsClock = document.getElementById('ends-in-clock');
        if (!startsClock || !endsClock) return;

        if (now < startAt) {
            startsClock.textContent = formatCountdown(startAt);
        } else {
            startsClock.textContent = 'Started';
        }

        if (now < endAt) {
            endsClock.textContent = formatCountdown(endAt);
        } else {
            endsClock.textContent = 'Ended';
            startsClock.textContent = 'Ended';
        }
    }

    document.addEventListener("DOMContentLoaded", function () {
        updateClocks();
        setInterval(updateClocks, 1000);
    });
</script>
