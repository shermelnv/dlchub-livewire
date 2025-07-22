{{-- Header --}}
<div class="lg:flex hidden items-center justify-between border-b border-neutral-200 dark:border-neutral-700 bg-[#701D0B] p-4 pl-8">
    <h1 class="text-2xl font-bold text-white">Dashboard Overview</h1>
</div>

<x-layouts.app :title="__('Dashboard')">
    <div class="flex h-full w-full flex-1 flex-col gap-6 rounded-xl">

        {{-- Summary Cards --}}
        <div class="grid gap-6 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-4">
            @foreach ([
                ['title' => 'Students', 'icon' => 'user', 'count' => $studentCount],
                ['title' => 'Active Group Chat', 'icon' => 'chat', 'count' => $groupChatCount],
                ['title' => 'Active Voting', 'icon' => 'voting', 'count' => $activeVoteCount],
                ['title' => 'Advertisement', 'icon' => 'ads', 'count' => $adsCount],
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
                                        <flux:icon.user-group class="w-7 h-7" />
                                        @break
                                    @case('chat')
                                        <flux:icon.chat-bubble-left-right class="w-7 h-7" />
                                        @break
                                    @case('voting')
                                        <flux:icon.check-circle class="w-7 h-7" />
                                        @break
                                    @case('ads')
                                        <flux:icon.megaphone class="w-7 h-7" />
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

        {{-- 📊 Live Statistics + ⚡ Quick Actions --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 h-full">
        {{-- Live Statistics (2/3 width) --}}
        <div class="col-span-1 md:col-span-2 flex flex-col rounded-xl bg-white dark:bg-zinc-800 border border-neutral-200 dark:border-neutral-700 shadow min-h-[250px]">
            <div class="p-4 text-lg font-semibold text-gray-700 dark:text-white">
                Live Statistics (Coming soon)
            </div>
            <x-placeholder-pattern class="absolute inset-0 size-full stroke-gray-900/10 dark:stroke-neutral-100/10 pointer-events-none" />
        </div>

        {{-- Quick Actions (1/3 width) --}}
        <div class="col-span-1 flex flex-col rounded-xl bg-white dark:bg-zinc-800 border border-neutral-200 dark:border-neutral-700 shadow min-h-[250px]">
            <div class="p-4 space-y-4">

                    <h2 class="text-lg font-semibold text-neutral-800 dark:text-white">Quick Actions</h2>
                    <div class="grid grid-cols-2 gap-4">
                        {{-- Manage Users --}}
                        <a href="{{ route('admin.user.manage-users') }}"
                        class="flex flex-col items-center justify-center p-4 bg-white dark:bg-zinc-800 border border-neutral-200 dark:border-neutral-700 rounded-lg shadow-sm hover:shadow-md transition text-center">
                            <div class="p-2 rounded-full bg-blue-100 text-blue-600 mb-2">
                                <flux:icon.plus class="w-6 h-6" />
                            </div>
                            <div class="text-sm font-medium text-gray-700 dark:text-white">Manage Users</div>
                        </a>

                        {{-- Manage Vote --}}
                        <a href="{{ route('admin.voting.manage-voting') }}"
                        class="flex flex-col items-center justify-center p-4 bg-white dark:bg-zinc-800 border border-neutral-200 dark:border-neutral-700 rounded-lg shadow-sm hover:shadow-md transition text-center">
                            <div class="p-2 rounded-full bg-green-100 text-green-600 mb-2">
                                <flux:icon.check-circle class="w-6 h-6" />
                            </div>
                            <div class="text-sm font-medium text-gray-700 dark:text-white">Manage Vote</div>
                        </a>

                        {{-- Manage Advertisement --}}
                        <a href="{{ route('admin.advertisement.manage-advertisement') }}"
                        class="flex flex-col items-center justify-center p-4 bg-white dark:bg-zinc-800 border border-neutral-200 dark:border-neutral-700 rounded-lg shadow-sm hover:shadow-md transition text-center">
                            <div class="p-2 rounded-full bg-yellow-100 text-yellow-600 mb-2">
                                <flux:icon.megaphone class="w-6 h-6" />
                            </div>
                            <div class="text-sm font-medium text-gray-700 dark:text-white">Manage Ads</div>
                        </a>

                        {{-- Manage Feed --}}
                        <a href="#"
                        class="flex flex-col items-center justify-center p-4 bg-white dark:bg-zinc-800 border border-neutral-200 dark:border-neutral-700 rounded-lg shadow-sm hover:shadow-md transition text-center">
                            <div class="p-2 rounded-full bg-purple-100 text-purple-600 mb-2">
                                <flux:icon.rss class="w-6 h-6" />
                            </div>
                            <div class="text-sm font-medium text-gray-700 dark:text-white">Manage Feed</div>
                        </a>

                        {{-- ✅ Manage Chat --}}
                        <a href="{{ route('admin.chat.manage-chat') }}"
                        class="flex flex-col items-center justify-center p-4 bg-white dark:bg-zinc-800 border border-neutral-200 dark:border-neutral-700 rounded-lg shadow-sm hover:shadow-md transition text-center">
                            <div class="p-2 rounded-full bg-red-100 text-red-600 mb-2">
                                <flux:icon.chat-bubble-left-right class="w-6 h-6" />
                            </div>
                            <div class="text-sm font-medium text-gray-700 dark:text-white">Manage Chat</div>
                        </a>
                    </div>
            </div>


        </div>


    </div>
</div>


    </div>
</x-layouts.app>
