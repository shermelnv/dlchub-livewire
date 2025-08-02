<div>
    <div class="mb-6">
        <flux:heading size="xl">Manage Voting</flux:heading>
        <flux:subheading size="lg" class="mb-6">
            Manage your voting rooms and settings.
        </flux:subheading>
        <flux:separator variant="subtle" />
    </div>

    {{-- Add Voting Modal Trigger --}}
    <flux:modal.trigger name="add-voting">
        <flux:button>Add Voting</flux:button>
    </flux:modal.trigger>

    {{-- Create Voting Modal --}}
    <flux:modal name="add-voting" class="md:w-[40rem]">
        <form wire:submit.prevent="createVoting">
            <div class="space-y-6">
                <div>
                    <flux:heading size="lg">Create Voting Room</flux:heading>
                    <flux:text class="mt-2">Set the title, description, schedule, and status.</flux:text>
                </div>

                <flux:input label="Title" placeholder="Voting Title" wire:model.defer="title" />
                <flux:textarea label="Description" placeholder="Optional" wire:model.defer="description" />

                <div class="grid grid-cols-2 gap-4">
                    <flux:input label="Start Time" type="datetime-local" wire:model.defer="start_time" />
                    <flux:input label="End Time" type="datetime-local" wire:model.defer="end_time" />
                </div>


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

    {{-- Edit Voting Modal --}}
    <flux:modal name="edit-voting" class="md:w-[40rem]">
        <form wire:submit.prevent="updateVoting">
            <div class="space-y-6">
                <div>
                    <flux:heading size="lg">Edit Voting Room</flux:heading>
                    <flux:text class="mt-2">Update the voting room details.</flux:text>
                </div>

                <flux:input label="Title" wire:model.defer="title" />
                <flux:textarea label="Description" wire:model.defer="description" />

                <div class="grid grid-cols-2 gap-4">
                    <flux:input label="Start Time" type="datetime-local" wire:model.defer="start_time" />
                    <flux:input label="End Time" type="datetime-local" wire:model.defer="end_time" />
                </div>

                <flux:select label="Status" wire:model.defer="status">
                    <option value="Pending">Pending</option>
                    <option value="Ongoing">Ongoing</option>
                    <option value="Closed">Closed</option>
                </flux:select>

                <div class="flex pt-4">
                    <flux:modal.close>
                        <flux:button variant="ghost">Cancel</flux:button>
                    </flux:modal.close>
                    <flux:spacer />
                    <flux:button type="submit" variant="primary">Update</flux:button>
                </div>
            </div>
        </form>
    </flux:modal>

    {{-- Voting Room List --}}
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
                    <button wire:click="editRoom({{ $room->id }})" class="text-blue-500 hover:text-blue-700 text-sm">
                        <flux:icon.pencil class="w-5 h-5" />
                    </button>
                    <button wire:click="deleteRoom({{ $room->id }})" class="text-red-500 hover:text-red-700 text-sm">
                        <flux:icon.trash class="w-5 h-5" />
                    </button>
                </div>
            </div>
        @empty
            <p class="text-gray-500 dark:text-gray-400">No voting rooms found.</p>
        @endforelse
    </div>
</div>
