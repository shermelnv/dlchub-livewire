<x-layouts.app :title="__('Dashboard')">
               {{-- Header --}}
    

<div class="flex h-full w-full flex-1 flex-col gap-6 rounded-xl p-4">

        {{-- Summary Cards --}}
        <livewire:dashboard-stats/>

        {{-- 📊 Live Statistics + ⚡ Quick Actions --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 h-full">
        {{-- Live Statistics (2/3 width) --}}
        <livewire:dashboard-recent-activity />



        {{-- Quick Actions (1/3 width) --}}
        <div class="col-span-1 flex flex-col rounded-xl bg-white dark:bg-zinc-800 border border-neutral-200 dark:border-neutral-700 shadow min-h-[250px]">
            <div class="p-4 space-y-4">

                    <h2 class="text-lg font-semibold text-neutral-800 dark:text-white">Quick Actions</h2>
                    <div class="grid grid-cols-2 gap-4">
                        {{-- Manage Users --}}
                        <a href="{{ route('admin.user.manage-users') }}"
                        class="flex flex-col items-center justify-center p-4 bg-white dark:bg-zinc-800 border border-neutral-200 dark:border-neutral-700 rounded-lg shadow-sm hover:shadow-md transition text-center">
                            <div class="p-2 rounded-full bg-blue-100 text-blue-600 mb-2">
                                <flux:icon.user class="w-6 h-6" />
                            </div>
                            <div class="text-sm font-medium text-gray-700 dark:text-white">Manage Users</div>
                        </a>

                        {{-- Manage Users --}}
                        <a href="{{ route('admin.org.manage-org') }}"
                        class="flex flex-col items-center justify-center p-4 bg-white dark:bg-zinc-800 border border-neutral-200 dark:border-neutral-700 rounded-lg shadow-sm hover:shadow-md transition text-center">
                            <div class="p-2 rounded-full bg-orange-100 text-orange-600 mb-2">
                                <flux:icon.user-group class="w-6 h-6" />
                            </div>
                            <div class="text-sm font-medium text-gray-700 dark:text-white">Manage Organizations</div>
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
