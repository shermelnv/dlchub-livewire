<div
    x-data
    x-init="
        window.addEventListener('activity-created', (e) => {
            $wire.addActivity(e.detail)
        });
    "
    class="col-span-1 md:col-span-2 flex flex-col rounded-xl bg-white dark:bg-zinc-800 border border-neutral-200 dark:border-neutral-700 shadow overflow-hidden"
>
 <div class="p-4 text-lg font-semibold text-gray-700 dark:text-white">
        Recent Activities
    </div>

    <div class="flex-1  p-4 space-y-3 text-sm text-gray-600 dark:text-gray-300">
        @forelse ($activities as $activity)
            <div class="flex gap-3 items-start py-3 border-b dark:border-neutral-700">
                {{-- Icon Bubble --}}
                <flux:avatar circle src="{{ 'https://i.pravatar.cc/100?u=' . $activity->type}}" />
                {{-- Message + Status + Timestamp --}}
                <div class="flex-1">
                    <div class="text-sm text-gray-700 dark:text-white">
                        {!! $activity->message !!}
                    </div>

                    @if ($activity->status && $activity->status == 'started')
                        <flux:badge icon="check-circle" variant="solid" color="green">Started</flux:badge>
                    @elseif($activity->status && $activity->status == 'closed')
                        <flux:badge icon="x-circle" variant="solid" color="red">Closed</flux:badge>
                    @endif

                    <div class="text-xs text-gray-400 mt-0.5">
                        {{ $activity->created_at->format('d-m-Y h:i A') }}
                    </div>
                </div>
            </div>
        @empty
            <p class="text-gray-400">No recent activities yet.</p>
        @endforelse
    </div>
</div>

