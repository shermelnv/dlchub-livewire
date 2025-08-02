<flux:modal name="group-settings" class="md:w-[42rem]">
    <div class="space-y-6">
        {{-- Modal Header --}}
        <flux:heading size="lg">Group Settings</flux:heading>
        <flux:text class="text-sm text-gray-500 dark:text-gray-400">
            Manage your group's name, description, and member access.
        </flux:text>

        {{-- Group Info --}}
        <div class="grid gap-4">
            <flux:input label="Group Name" placeholder="e.g. STEM Society" value="Mock Group A" />
            <flux:textarea label="Description" placeholder="Group purpose or details...">
                Lorem ipsum dolor sit amet, consectetur adipiscing elit.
            </flux:textarea>
        </div>

        <div x-data="{tab: 'pending-member-request' }">
            <div class="grid grid-cols-2 mb-8">
                <button
                    @click="tab = 'pending-member-request' "
                    :class="tab === 'pending-member-request' ? 'bg-red-900 text-white' : 'bg-white text-gray-900 dark:bg-gray-800 dark:text-gray-200' "
                    class="py-4 transition-all">
                    Member Request
                </button>
                <button
                    @click="tab = 'members' "
                    :class="tab === 'members' ? 'bg-red-900 text-white' : 'bg-white text-gray-900 dark:bg-gray-800 dark:text-gray-200' "
                    class="py-4 transition-all">
                    Members
                </button>
            </div>

            <div x-show="tab === 'pending-member-request' " class="space-y-3">
                    <flux:heading size="sm">Pending Member Requests</flux:heading>

                    @foreach ([
                        ['name' => 'Alice Reyes', 'email' => 'alice@email.com'],
                        ['name' => 'Ben Cruz', 'email' => 'ben@email.com'],
                    ] as $user)
                        <div class="flex justify-between items-center p-3 bg-gray-100 dark:bg-gray-800 rounded-lg">
                            <div>
                                <p class="font-medium text-gray-800 dark:text-white">{{ $user['name'] }}</p>
                                <p class="text-sm text-gray-500">{{ $user['email'] }}</p>
                            </div>
                            <div class="flex gap-2">
                                <flux:button size="xs" color="green" icon="check">Accept</flux:button>
                                <flux:button size="xs" color="red" icon="x-mark">Reject</flux:button>
                            </div>
                        </div>
                    @endforeach
            </div>
            <div x-show="tab === 'members' " class="space-y-3">
            <flux:heading size="sm">Current Members</flux:heading>

            @foreach ([
                ['name' => 'Charlie Santos', 'email' => 'charlie@email.com'],
                ['name' => 'Dana Lopez', 'email' => 'dana@email.com'],
            ] as $member)
                <div class="flex justify-between items-center border p-3 rounded-lg dark:border-gray-700">
                    <div>
                        <p class="font-medium text-gray-900 dark:text-white">{{ $member['name'] }}</p>
                        <p class="text-sm text-gray-500">{{ $member['email'] }}</p>
                    </div>
                    <flux:button size="xs" variant="ghost" icon="trash" color="red">Remove</flux:button>
                </div>
            @endforeach
        </div>
        </div>

        {{-- Modal Footer --}}
        <div class="flex justify-between items-center">

                <flux:button variant="danger" size="sm">Leave Group</flux:button>
                <div class="flex items-center space-x-2">
                    <flux:modal.close>
                        <flux:button variant="ghost">Close</flux:button>
                    </flux:modal.close>
                    <flux:button icon="check" color="primary">Save Changes</flux:button>
                </div>
   
            
        </div>
    </div>
</flux:modal>
