<div>
          <div class="relative mb-6 w-full">
            <flux:heading size="xl" level="1">{{ __('Voting') }}</flux:heading>
            <flux:subheading size="lg" class="mb-6">{{ __('Manage your profile and account settings') }}</flux:subheading>
            <flux:separator variant="subtle" />
            {{-- Flash message --}}
           
        </div>

            <div class="mt-10">
        <flux:heading size="lg" class="mb-4">Existing Voting Rooms</flux:heading>

        @forelse ($rooms as $room)
            <div class="flex justify-between items-center py-2 border-b dark:border-gray-700">
                <div>
                    <div class="font-semibold text-gray-800 dark:text-gray-100">{{ $room->title }}</div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">{{ $room->description }}</div>
                    <div class="text-xs text-gray-400 dark:text-gray-500">
                        {{ $room->start_time ? \Carbon\Carbon::parse($room->start_time)->format('M d, Y h:i A') : 'No start time' }}
                        &mdash;
                        {{ $room->end_time ? \Carbon\Carbon::parse($room->end_time)->format('M d, Y h:i A') : 'No end time' }}
                        • <span class="font-semibold">{{ $room->status }}</span>
                    </div>
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('voting.room', $room->id) }}" class="text-green-500 hover:text-green-700 text-sm">
                        <flux:icon.eye class="w-5 h-5" />
                    </a>
                </div>
            </div>
        @empty
            <p class="text-gray-500 dark:text-gray-400">No voting rooms found.</p>
        @endforelse
    </div>
</div>
