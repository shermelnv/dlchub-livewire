<div class="w-full p-2 h-auto rounded-lg shadow-sm">
    <div class="flex justify-between items-center mb-4">
        <h2 class="font-semibold text-lg flex gap-2 items-center">
            Active Voting
        </h2>
    </div>

    @forelse ($activeVotings as $voting)
        <a href="{{ route('voting.room', $voting->id) }}" 
            class="flex justify-between items-center p-3 mb-3 hover:bg-gray-100 dark:hover:bg-gray-700 transition rounded-lg">
           
           <div class="rounded-lg ">
            <h3 class="font-semibold text-md text-gray-900 dark:text-gray-100">
                {{ $voting->title }}
            </h3>

            <p class="text-gray-700 dark:text-gray-300 text-sm mb-1">
                {{ $voting->description }}
            </p>

            <p class="text-gray-500 dark:text-gray-400 text-xs">
                {{ \Carbon\Carbon::parse($voting->start_time)->format('M d, Y H:i') }} 
                - 
                {{ \Carbon\Carbon::parse($voting->end_time)->format('M d, Y H:i') }}
            </p>
            </div>
            
            <flux:icon.arrow-right class="w-4 h-4 text-gray-500 dark:text-gray-400"/>
        </a>
    @empty
        <p class="text-gray-500 dark:text-gray-400">No active votings available.</p>
    @endforelse
</div>
