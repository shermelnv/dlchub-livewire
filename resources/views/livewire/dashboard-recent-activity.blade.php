<div
    x-data
    x-init="
         Echo.channel('dashboard.activity')
            .listen('.activity.created', (e) => {
                console.log('Broadcast received:', e)
                Livewire.dispatch('activity-created', e)
                // Call Livewire method with payload
                {{-- $wire.addActivity(e.activity) --}}
            });


    "
    class="col-span-1 md:col-span-2 flex flex-col rounded-xl bg-white dark:bg-zinc-800 border border-neutral-200 dark:border-neutral-700 shadow p-4 "
>
    <div class="pb-2 text-lg font-semibold text-gray-700 dark:text-white flex justify-between">
        <p>Recent Activities</p>
        <div class="flex gap-2 items-center">
            <flux:input placeholder="Search" icon="magnifying-glass" clearable size="sm" wire:model.live="search" />
            
            <flux:dropdown>
                <flux:button icon:trailing="chevron-down" size="sm">
                    {{ $filterType ? ucfirst($filterType) : 'All' }}
                </flux:button>

                <flux:menu>
                    <flux:menu.radio.group wire:model.live="filterType">
                        <flux:menu.radio value="">All</flux:menu.radio>
                        <flux:menu.radio value="feed">Feed</flux:menu.radio>
                        <flux:menu.radio value="voting">Voting</flux:menu.radio>
                        <flux:menu.radio value="advertisement">Advertisement</flux:menu.radio>
                    </flux:menu.radio.group>
                </flux:menu>
            </flux:dropdown>
        </div>
    </div>

    <div class="flex-1 max-h-[60vh] overflow-y-auto text-sm text-gray-600 dark:text-gray-300 bg-zinc-100 dark:bg-zinc-900 rounded-lg relative">

    <table class="w-full text-left border-collapse">
        <thead class="bg-zinc-100 dark:bg-zinc-700 uppercase sticky text-sm lg:text-lg top-0">
            <tr>
                <th class="px-3 py-2">Type</th>
                <th class="px-3 py-2">Activity</th>
                <th class="px-3 py-2">Date</th>
                <!--<th class="px-3 py-2">Time</th>-->
                <th class="px-3 py-2">Status</th>
            </tr>
        </thead>
                <tbody>
                    @forelse ($activities as $activity)
                        <tr >
                            <!-- Type -->
                            <td class="px-3 py-2 text-xs lg:text-base capitalize">{{ $activity->type }}</td>

                            <!-- Message -->
                            <td class="px-3 py-2 text-xs lg:text-base">{{ $activity->message }}</td>

                            <!-- Date -->
                            <td class="px-3 py-2 text-xs lg:text-base">
                                {{ $activity->created_at->format('d M, Y h:i A') }}
                            </td>

                            <!-- Time -->
                            <!--<td class="px-3 py-2">-->
                            <!--    {{ $activity->created_at->format('h:i A') }}-->
                            <!--</td>-->

                            <!-- Status -->
                            <td class="px-3 py-2">
                                <flux:badge 
                                    color="{{
                                        in_array($activity->action, ['created', 'posted']) ? 'lime' :
                                        ($activity->action === 'active' ? 'blue' :
                                        ($activity->action === 'ended' ? 'red' : 'gray'))
                                    }}"

                                    variant="solid">
                                    {{ $activity->action }}
                                </flux:badge>
                            </td>
                        </tr>
                        
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-4">NONE</td>
                    </tr>
                    
                    @endforelse
                </tbody>

    
    </table>
</div>



