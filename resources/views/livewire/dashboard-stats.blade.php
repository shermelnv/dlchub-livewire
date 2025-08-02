
<div
    x-data
    x-init="
        window.addEventListener('stats-updated', (e) => {
            $wire.updateCounts(e.detail);
        });
    "
    class="grid gap-6 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-4"
>
    @foreach ([
                ['title' => 'Students', 'icon' => 'user', 'count' => $studentCount],
                ['title' => 'Organizations', 'icon' => 'org', 'count' => $orgCount],
                ['title' => 'Active Group Chat', 'icon' => 'chat', 'count' => $groupChatCount],
                ['title' => 'Active Voting', 'icon' => 'voting', 'count' => $activeVoteCount],
            ] as $item)
                @php
                    $percent = isset($item['voted']) && isset($item['total'])
                        ? ($item['voted'] / $item['total']) * 100
                        : null;
                @endphp
                <div class="relative aspect-video overflow-hidden rounded-xl  dark:bg-zinc-800 border border-neutral-200 dark:border-neutral-700 shadow">
                    <div class="p-4 flex flex-col justify-between h-full gap-2">
                        <div class="flex items-center gap-2 text-gray-500 dark:text-gray-400">
                            {{-- Icons --}}
                          <div class="text-gray-500 dark:text-gray-400">
                                @switch($item['icon'])
                                    @case('user')
                                        <flux:icon.users class="w-7 h-7" />
                                        @break
                                    @case('org')
                                        <flux:icon.user-group class="w-7 h-7" />
                                        @break
                                    @case('chat')
                                        <flux:icon.chat-bubble-left-right class="w-7 h-7" />
                                        @break
                                    @case('voting')
                                        <flux:icon.check-circle class="w-7 h-7" />
                                        @break
                                    
                                @endswitch
                            </div>
                            {{-- Title --}}
                            <h2 class="text-lg font-semibold text-gray-700 dark:text-white">{{ $item['title'] }}</h2>
                            
                        </div>

                        {{-- Count or Title --}}
                        <div class="text-5xl font-bold text-maroon-900 dark:text-rose-300">
                            {{ is_numeric($item['count']) ? $item['count'] : $item['count'] }}
                        </div>

                        

                        <x-placeholder-pattern class="absolute inset-0 size-full stroke-gray-900/10 dark:stroke-neutral-100/10 pointer-events-none" />
                    </div>
                </div>
            @endforeach
</div>
