<div 
    x-data
    x-init="Echo.channel('manage-feeds')
                .listen('.feed.post', (e) => {
                    console.log('new feed post', e.feed);
                    Livewire.dispatch('newFeedPosted');
                });">

    <!-- ========== MAIN GRID ========== -->
    <div class="grid grid-cols-1 lg:grid-cols-5 gap-6 h-full  lg:p-6">
        <!-- ========== LEFT COLUMN: FEED AREA ========== -->
        <div class="w-full col-span-3 flex flex-col gap-6">

            <!-- ====== CREATE POST SECTION ====== -->
            @if(auth()->user()->role !== 'user')
            <section class="flex bg-gray-900 rounded-lg gap-4 p-4">
                <flux:avatar circle src="https://unavatar.io/x/calebporzio" />
                <flux:modal.trigger name="post-feed">
                    <flux:button class="w-full">What's on your mind?</flux:button>
                </flux:modal.trigger>
            </section>
            @endif
            <div class="flex justify-between">
            <!-- ====== FILTER SECTION ====== -->
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
                </div>

                @if ($organizationFilter || $typeFilter)
                    <flux:button color="gray" size="sm" wire:click="resetFilters">
                        Reset Filters
                    </flux:button>
                @endif
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
                            <div class="flex justify-between">
                                <h2 class="text-lg font-semibold text-gray-800 dark:text-white">{{ $feed->title }}</h2>
                                <flux:dropdown position="bottom" align="end">
                                    <button><flux:icon.ellipsis-horizontal /></button>
                                    <flux:menu>
                                        <flux:menu.item wire:click="editPost({{ $feed->id }})">Edit</flux:menu.item>
                                        <flux:menu.item wire:click="confirmDelete({{ $feed->id }})">Delete</flux:menu.item>
                                    </flux:menu>
                                </flux:dropdown>
                            </div>

                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                Posted {{ \Carbon\Carbon::parse($feed->published_at)->format('Y-m-d') }}
                            </p>

                            <!-- Content -->
                            <p class="text-sm text-gray-600 dark:text-gray-300">{{ $feed->content }}</p>

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
    </div>

    <!-- ====== DELETE POST MODAL ====== -->
    @include('livewire.admin.feed.partials.delete-modal')

    <!-- ====== CREATE POST MODAL ====== -->
    @include('livewire.admin.feed.partials.create-modal')

    <!-- ====== EDIT POST MODAL ====== -->
    @include('livewire.admin.feed.partials.edit-modal')
</div>
