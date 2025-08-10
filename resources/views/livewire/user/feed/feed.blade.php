<div
x-data
    x-init="Echo.channel('manage-feeds')
                .listen('.feed.post', (e) => {
                    console.log('new feed post', e.feed);
                    Livewire.dispatch('newFeedPosted');
                });
            Echo.channel('manage-ads')
                .listen('.ads.post', (e) => {
                    console.log('new ads post', e.ads);
                    Livewire.dispatch('newAdPosted');
                });
      
    ">

    <!-- ========== MAIN GRID ========== -->
    <div class="grid grid-cols-1 lg:grid-cols-5 gap-6 h-full p-6 lg:p-6">
        <!-- ========== LEFT COLUMN: FEED AREA ========== -->
        <div class="w-full col-span-3 flex flex-col gap-6 ">

            <!-- ====== FILTER SECTION ====== -->
            <div class="flex justify-between items-center">
                <!-- Organization Filter -->
                <div class="flex space-x-4">
                    <flux:dropdown>
                        <flux:button icon:trailing="chevron-down" size="sm">
                            {{ $organizationFilter ? ucfirst($organizationFilter) : 'All Organization' }}
                        </flux:button>
                        <flux:menu>
                            <flux:menu.item wire:click="$set('organizationFilter', null)">
                                All Organization
                            </flux:menu.item>
                            @foreach ($orgs as $org)
                                <flux:menu.item wire:click="$set('organizationFilter', '{{ $org->name }}')">
                                    {{ $org->name }}
                                </flux:menu.item>
                            @endforeach
                        </flux:menu>
                    </flux:dropdown>

                    <!-- Type Filter -->
                    <flux:dropdown>
                        <flux:button icon:trailing="chevron-down" size="sm">
                            {{ $typeFilter ? ucfirst($typeFilter) : 'All Type' }}
                        </flux:button>
                        <flux:menu>
                            <flux:menu.item wire:click="$set('typeFilter', null)">
                                All Type
                            </flux:menu.item>
                            @foreach ($types as $type)
                                <flux:menu.item wire:click="$set('typeFilter', '{{ $type->type_name }}')">
                                    {{ $type->type_name }}
                                </flux:menu.item>
                            @endforeach
                        </flux:menu>
                    </flux:dropdown>
                    @if ($organizationFilter || $typeFilter)
                        <flux:button variant="ghost" size="xs" wire:click="resetFilters">
                            Reset Filters
                        </flux:button>
                    @endif
                </div>
                
                <div class="flex justify-end lg:hidden pr-2">
                    <flux:modal.trigger name="stats">
                        <flux:icon.bars-3-bottom-right/>
                    </flux:modal.trigger>
                    
                </div>
                    
                
            </div>

            <!-- ====== FEED LIST ====== -->
            <div class="space-y-6">
                @forelse ($this->filteredFeeds as $feed)
                    <div class="bg-white dark:bg-gray-800 shadow-md hover:shadow-xl rounded-2xl overflow-hidden border border-gray-200 dark:border-gray-700 transition duration-300 ease-in-out">
                        @if ($feed->photo_url)
                            <div class="relative w-full bg-gray-100 dark:bg-gray-700 overflow-hidden">
                                <img src="{{ asset('storage/' . $feed->photo_url) }}" loading="lazy" class="object-contain w-full h-full" />
                                @if ($feed->organization)
                                    <span class="absolute top-2 left-2 bg-purple-100 text-purple-800 text-xs font-semibold px-3 py-1 rounded-full dark:bg-purple-900 dark:text-white">
                                        {{ $feed->organization }}
                                    </span>
                                @endif
                            </div>
                        @endif

                        <div class="p-4 space-y-3">
                            <!-- Header -->
                            <div class="flex">
                                <h2 class="text-lg font-semibold text-gray-800 dark:text-white">{{ $feed->title }}</h2>        
                            </div>

                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                Posted {{ \Carbon\Carbon::parse($feed->published_at)->format('Y-m-d') }}
                            </p>

                           <!-- Content -->
                            <div x-data="{ expanded: false, maxHeight: '4rem' }"> {{-- about 3 lines --}}
                                <p 
                                    class="text-sm text-gray-600 dark:text-gray-300 overflow-hidden transition-all duration-300"
                                    :style="expanded ? 'max-height: none' : `max-height: ${maxHeight}`"
                                >
                                    {{ $feed->content }}
                                </p>

                                <button 
                                    @click="expanded = !expanded"
                                    class="mt-1 text-blue-500 hover:underline text-xs"
                                >
                                    <span x-text="expanded ? 'See less' : 'See more'"></span>
                                </button>
                            </div>


                            <!-- Tags -->
                            @if ($feed->type)
                                <div class="flex flex-wrap gap-2 mt-2">
                                    <span class="bg-gray-100 dark:bg-gray-700 text-xs px-2 py-1 rounded-full font-medium text-gray-700 dark:text-gray-200">
                                        {{ $feed->type }}
                                    </span>
                                </div>
                            @endif

                            <!-- Footer -->
                            <div class="flex items-center gap-6 pt-2 text-gray-500 dark:text-gray-400 text-sm">
                                <div class="flex items-center gap-1">
                                    <flux:icon.heart class="w-4 h-4" />
                                    <span>123</span>
                                </div>
                                <div class="flex items-center gap-1">
                                    <flux:icon.chat-bubble-oval-left-ellipsis class="w-4 h-4" />
                                    <span>123</span>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center text-gray-500 dark:text-gray-400">No posts available.</div>
                @endforelse
            </div>
        </div>

        <!-- ========== RIGHT SIDEBAR ========== -->
        <div class="flex flex-col col-span-2 gap-6 xs:hidden h-[calc(100vh-1.5rem)] sticky self-start top-6 shadow overflow-y-auto">
            {{-- search --}}
            <flux:input icon-trailing="magnifying-glass" placeholder="Search" clearable/>
            
            <div class="space-y-4">
                <flux:heading size="lg">Advertisement</flux:heading>
                <div class="grid grid-cols-2 h-auto gap-4">
                    <div class="h-20 w-full bg-white"></div>
                    <div class="grid items-center">Lorem ipsum dolor sit amet!</div>
                </div>
            </div>
            
            <!-- Orgs -->
            <div class="border w-full p-2 rounded-lg bg-white dark:bg-gray-800 shadow">
                <h2 class="font-semibold mb-3 flex gap-2">
                    <flux:icon.building-library /> Organizations
                </h2>
                <div class="max-h-[30vh] overflow-y-auto">
                @forelse ($orgs as $org)
                    <a href="{{ route('org.profile', ['org' => $org->id]) }}" >
                        <div class="flex gap-4 items-center text-sm p-1 hover:bg-gray-100 dark:hover:bg-gray-700 rounded">
                            <flux:avatar circle src="{{$org->profile ?? 'https://i.pravatar.cc/100?u=' . $org->id}}" />
                            <span class="truncate">{{ $org->name }}</span>
                        </div>
                    </a>
                @empty
                    <p class="text-sm text-gray-400">No data available.</p>
                @endforelse
                </div>
            </div>

            <!-- Deadlines -->
            <div class="border w-full p-4 rounded-lg bg-white dark:bg-gray-800 shadow">
                <h2 class="font-semibold mb-3 flex gap-2">
                    <flux:icon.clock /> Upcoming Deadlines
                </h2>
                <p class="text-sm text-gray-400">No upcoming deadlines.</p>
            </div>

            <!-- Help -->
            <div class="border w-full p-4 rounded-lg bg-white dark:bg-gray-800 shadow">
                <h2 class="font-semibold mb-3">💬 Help & Support</h2>
                <ul class="text-sm text-gray-700 dark:text-gray-300 space-y-1">
                    <li><a href="#" class="hover:underline">📘 How to post an advertisement</a></li>
                    <li><a href="#" class="hover:underline">📩 Contact administrator</a></li>
                    <li><a href="#" class="hover:underline">⚙️ Manage your organization</a></li>
                </ul>
            </div>
        </div>

        <flux:modal name="stats" variant="flyout">
            
                        <div class="flex flex-col gap-4 mt-6">
                            <!-- Orgs -->
                            <div class="border w-full p-2 rounded-lg bg-white dark:bg-gray-800 shadow">
                                <h2 class="font-semibold mb-3 flex gap-2">
                                    <flux:icon.building-library /> Organizations
                                </h2>
                                <div class="max-h-[30vh] overflow-y-auto">
                                    @forelse ($orgs as $org)
                                        <a href="{{ route('org.profile', ['org' => $org->id]) }}" >
                                            <div class="flex gap-4 items-center text-sm p-1 hover:bg-gray-100 dark:hover:bg-gray-700 rounded">
                                                <flux:avatar circle src="{{$org->profile ?? 'https://i.pravatar.cc/100?u=' . $org->id}}" />
                                                <span class="truncate">{{ $org->name }}</span>
                                            </div>
                                        </a>
                                    @empty
                                        <p class="text-sm text-gray-400">No data available.</p>
                                    @endforelse
                                </div>
                            </div>

                            <!-- Deadlines -->
                            <div class="border w-full p-4 rounded-lg bg-white dark:bg-gray-800 shadow">
                                <h2 class="font-semibold mb-3 flex gap-2">
                                    <flux:icon.clock /> Upcoming Deadlines
                                </h2>
                                <p class="text-sm text-gray-400">No upcoming deadlines.</p>
                            </div>

                            <!-- Help -->
                            <div class="border w-full p-4 rounded-lg bg-white dark:bg-gray-800 shadow">
                                <h2 class="font-semibold mb-3">💬 Help & Support</h2>
                                <ul class="text-sm text-gray-700 dark:text-gray-300 space-y-1">
                                    <li><a href="#" class="hover:underline">📘 How to post an advertisement</a></li>
                                    <li><a href="#" class="hover:underline">📩 Contact administrator</a></li>
                                    <li><a href="#" class="hover:underline">⚙️ Manage your organization</a></li>
                                </ul>
                            </div>
                        </div>
           
                    </flux:modal>



    </div>
    
</div>