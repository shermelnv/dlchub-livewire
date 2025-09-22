<div class="grid grid-cols-3 gap-4">


    {{-- Voting Room List --}}
    <div class="p-10 col-span-2">
        <flux:heading size="lg" class="mb-4">Existing Chat Rooms</flux:heading>

        @forelse ($rooms as $room)
            <div class="flex justify-between items-center py-2 border-b dark:border-gray-700">
                <div>
                    <div class="font-semibold text-gray-800 dark:text-gray-100">{{ $room->name }}</div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">{{ $room->description }}</div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">{{ $room->owner->name }}</div>
                </div>
                <div class="flex gap-2">

                        <button 
                            wire:click="viewRoom({{ $room->id }})"
                            wire:target="viewRoom({{ $room->id }})"
                            wire:key="viewRoom({{ $room->id }})"
                            class="cursor-pointer"
                        >
                            <flux:icon.eye wire:loading.remove wire:target="viewRoom({{ $room->id }})"
                                class="w-5 h-5 text-green-500 hover:text-green-700 text-sm" />
                            <flux:icon.loading wire:loading wire:target="viewRoom({{ $room->id }})"
                                class="w-5 h-5 animate-spin" />
                        </button>

                </div>
            </div>
        @empty
            <p class="text-gray-500 dark:text-gray-400">No voting rooms found.</p>
        @endforelse

        <flux:modal name="roomDetails" class="min-w-sm">
            @if($selectedRoom)
                <div class="p-6 rounded-2xl ">
                    <flux:heading size="lg" class="mb-6 text-gray-900 dark:text-gray-100">Room Details</flux:heading>

                    <div class="space-y-3 mb-6">
                        <p class="text-gray-800 dark:text-gray-200">
                            <strong>Name:</strong> {{ $selectedRoom->name }}
                        </p>
                        <p class="text-gray-800 dark:text-gray-200">
                            <strong>Description:</strong> {{ $selectedRoom->description }}
                        </p>
                        <p class="text-gray-800 dark:text-gray-200">
                            <strong>Owner:</strong> {{ $selectedRoom->owner->name }}
                        </p>
                        <p class="text-gray-800 dark:text-gray-200">
                            <strong>Code:</strong> {{ $selectedRoom->group_code }}
                        </p>
                    </div>

                    <div class="flex justify-end">
                        <flux:modal.close>
                            <flux:button 
                                variant="ghost" 
                                class="text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors rounded-md px-4 py-2">
                                Close
                            </flux:button>
                        </flux:modal.close>
                    </div>
                </div>
            @else
                <p class="text-gray-500 dark:text-gray-400">No room selected.</p>
            @endif
        </flux:modal>

    </div>

    <livewire:right-sidebar />
</div>