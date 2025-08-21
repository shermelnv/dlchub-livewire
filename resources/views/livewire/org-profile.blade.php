<div class="min-h-screen px-4 sm:px-6 md:px-10 py-5">
    <!-- Top: Organization Info -->
    <div class="grid grid-cols-1 md:grid-cols-5 gap-4 items-center mb-6">
        <!-- Left: Org Info -->
        <section class="flex gap-4 sm:gap-6 col-span-1 md:col-span-4 flex-wrap md:flex-nowrap">
            <div class="flex items-center justify-center flex-shrink-0">
                <flux:avatar class="size-16 sm:size-20 md:size-24" circle
                    src="{{ $org->profile ?? 'https://i.pravatar.cc/100?u=' . $this->org->id }}" />
            </div>
            <div class="flex flex-col justify-center space-y-1 min-w-0">
                <strong class="text-lg sm:text-xl md:text-2xl text-gray-900 dark:text-white truncate">
                    {{ $this->org->name }}
                </strong>
                <span class="text-gray-600 dark:text-gray-300 text-sm break-words">
                    Lorem ipsum dolor sit amet consectetur adipisicing elit. Quis, recusandae.
                </span>
                <div class="flex items-center gap-2 text-gray-500 dark:text-gray-200 text-sm mt-1">
                    <flux:icon.users class="size-4" />
                    <span>2,145 Followers</span>
                </div>
            </div>
        </section>

        <!-- Right: Follow + Modal Button -->
        <section class="flex justify-start md:justify-end gap-4 items-center w-full md:w-auto mt-2 md:mt-0">
            <flux:button class="w-full sm:w-auto">
                <flux:icon.user-plus class="size-4" variant="solid" />
                Follow Organization
            </flux:button>

            <!-- Only visible below md -->
            <flux:modal.trigger name="about" class="md:hidden">
                <flux:button icon-leading="information-circle" icon-trailing="chevron-down">About</flux:button>
            </flux:modal.trigger>
        </section>
    </div>

    <!-- Main Content -->
    <div class="grid grid-cols-1 lg:grid-cols-5 gap-6 lg:gap-10">
        <!-- About Section (hidden below md) -->
        <section
            class="hidden md:block col-span-1 lg:col-span-2 border p-4 rounded-xl bg-white dark:bg-gray-800 h-fit lg:sticky self-start top-6 shadow">
            <h2 class="text-lg font-semibold flex items-center gap-2 mb-4 text-gray-900 dark:text-white">
                <flux:icon.information-circle class="size-6 text-red-900 dark:text-red-800" /> About
            </h2>

            <p class="text-gray-600 dark:text-gray-400 mb-4 leading-relaxed break-words">
                Lorem ipsum dolor sit amet consectetur adipisicing elit. Pariatur natus aut cumque Lorem ipsum dolor sit,
                amet consectetur adipisicing elit. Vel, vero.
            </p>

            <div class="lg:space-y-4 grid grid-cols-2 gap-4 lg:grid-cols-1">
                <div class="flex items-center gap-4">
                    <flux:badge size="lg" color="red">
                        <flux:icon.calendar />
                    </flux:badge>
                    <div>
                        <div class="text-sm text-gray-600 dark:text-gray-300">Founded</div>
                        <div class="font-semibold text-gray-900 dark:text-white">2020</div>
                    </div>
                </div>

                <div class="flex items-center gap-4">
                    <flux:badge size="lg" color="red">
                        <flux:icon.envelope />
                    </flux:badge>
                    <div>
                        <div class="text-sm text-gray-600 dark:text-gray-300">Email</div>
                        <a href="mailto:egames@university.edu"
                            class="text-black dark:text-white underline break-all">egames@university.edu</a>
                    </div>
                </div>

                <div class="flex items-center gap-4">
                    <flux:badge size="lg" color="red">
                        <flux:icon.globe-alt />
                    </flux:badge>
                    <div>
                        <div class="text-sm text-gray-600 dark:text-gray-300">Website</div>
                        <a href="https://www.egames-org.com" target="_blank"
                            class="text-black dark:text-white underline break-all">www.egames-org.com</a>
                    </div>
                </div>

                <div class="flex items-center gap-4">
                    <flux:badge size="lg" color="red">
                        <flux:icon.users />
                    </flux:badge>
                    <div>
                        <div class="text-sm text-gray-600 dark:text-gray-300">Members</div>
                        <div class="font-semibold text-black dark:text-white">12,312 Active Members</div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Main Tabbed Content -->
        <section class="order-1 lg:order-2 col-span-1 lg:col-span-3">
            <div x-data="{ tab: 'feed' }">
                <!-- Tabs -->
                <div class="grid grid-cols-3 shadow-md mb-8">
                    <button @click="tab = 'feed'"
                        :class="tab === 'feed' ? 'bg-red-950 text-white' : 'text-gray-900 dark:text-gray-200'"
                        class="py-3 sm:py-4 transition-all rounded-tl-xl text-xs md:text-base">Feed</button>

                    <button @click="tab = 'ads'"
                        :class="tab === 'ads' ? 'bg-red-950 text-white' : 'text-gray-900 dark:text-gray-200'"
                        class="py-3 sm:py-4 transition-all text-xs md:text-base">Advertisement</button>

                    <button @click="tab = 'members'"
                        :class="tab === 'members' ? 'bg-red-950 text-white' : 'text-gray-900 dark:text-gray-200'"
                        class="py-3 sm:py-4 transition-all rounded-tr-xl text-xs md:text-base">Members</button>
                </div>

                <!-- Tab Content -->
                <div x-show="tab === 'feed'" class="grid grid-cols-1 gap-4">
                    @forelse($feeds as $feed)
                        <div
                            class="bg-white dark:bg-gray-800 shadow-md hover:shadow-xl rounded-2xl overflow-hidden border border-gray-200 dark:border-gray-700 transition duration-300 ease-in-out">
                            @if ($feed->photo_url)
                                <div class="w-full bg-gray-100 dark:bg-gray-700 overflow-hidden">
                                    <img src="{{ asset('storage/' . $feed->photo_url) }}" loading="lazy"
                                        class="object-contain w-full h-full" />
                                </div>
                            @endif
                            <div class="p-4 space-y-3">
                                <div class="flex justify-between">
                            <div>
                                <h2 class="text-lg font-semibold text-gray-800 dark:text-white">{{ $feed->user->name }}</h2>
                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                    Posted {{ \Carbon\Carbon::parse($feed->published_at)->format('Y-m-d') }}
                                </p>
                            </div>
                            @if($feed->user_id === auth()->user()->id)
                            <div>
                                <flux:dropdown position="bottom" align="end">
                                    <button><flux:icon.ellipsis-horizontal /></button>
                                    <flux:menu>
                                        <flux:menu.item wire:click="editPost({{ $feed->id }})">Edit</flux:menu.item>
                                        <flux:menu.item wire:click="confirmDelete({{ $feed->id }})">Delete</flux:menu.item>
                                    </flux:menu>
                                </flux:dropdown>
                            </div>
                            @endif
                            </div>
                            <h2>{{$feed->title}}</h2>
                                <p class="text-sm text-gray-600 dark:text-gray-300">{{ $feed->content }}</p>
                                @if ($feed->type)
                                    <div class="flex flex-wrap gap-2 mt-2">
                                        <span
                                            class="bg-gray-100 dark:bg-gray-700 text-xs px-2 py-1 rounded-full font-medium text-gray-700 dark:text-gray-200">
                                            {{ $feed->type }}
                                        </span>
                                    </div>
                                @endif
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
                        <p class="text-gray-500 dark:text-gray-400">No feed posts found.</p>
                    @endforelse
                </div>

                <!-- Ads -->
                <div x-show="tab === 'ads'" class="grid grid-cols-1 gap-4">
                    @forelse($ads as $ad)
                        <div class="bg-white dark:bg-gray-800 shadow-md hover:shadow-xl rounded-2xl overflow-hidden border border-gray-200 dark:border-gray-700 transition duration-300 ease-in-out">

                            {{-- IMAGE PREVIEW --}}
                            @php
                                $photoCount = $ad->photos->count();
                                $photos = $ad->photos->take(4); // Always max of 4 previewed
                            @endphp

                            @if ($photoCount > 0)
                                <div class="overflow-hidden">
                                    @if ($photoCount === 1)
                                        {{-- Style 1: Single full image --}}
                                        <img src="{{ Storage::url($photos[0]->photo_path) }}"
                                            class="w-full h-64 object-cover rounded"
                                            alt="Ad Image">
                                    @elseif ($photoCount === 2)
                                        {{-- Style 2: 2 horizontal side-by-side --}}
                                        <div class="grid grid-cols-2 gap-1">
                                            @foreach ($photos as $photo)
                                                <img src="{{ Storage::url($photo->photo_path) }}"
                                                    class="w-full h-64 object-cover rounded"
                                                    alt="Ad Image">
                                            @endforeach
                                        </div>
                                    @elseif ($photoCount === 3)
                                    {{-- Style 6: 1 tall image left, 2 stacked images right --}}
                                    <div class="grid grid-cols-2 gap-1 h-64">
                                        {{-- Left: tall image --}}
                                        <div class="h-64">
                                            <img src="{{ Storage::url($photos[0]->photo_path) }}"
                                                class="w-full h-full object-cover rounded"
                                                alt="Ad Image">
                                        </div>

                                        {{-- Right: 2 stacked images --}}
                                        <div class="grid grid-rows-2 gap-1 h-64">
                                            <div class="h-full">
                                                <img src="{{ Storage::url($photos[1]->photo_path) }}"
                                                    class="w-full h-full object-cover rounded"
                                                    alt="Ad Image">
                                            </div>
                                            <div class="h-full">
                                                <img src="{{ Storage::url($photos[2]->photo_path) }}"
                                                    class="w-full h-full object-cover rounded"
                                                    alt="Ad Image">
                                            </div>
                                        </div>
                                    </div>
                                    @elseif ($photoCount >= 4)
                                        {{-- Style 8 or 10: 2x2 grid with overlay if > 4 --}}
                                        <div class="grid grid-cols-2 gap-1">
                                            @foreach ($photos as $index => $photo)
                                                <div class="relative">
                                                    <img src="{{ Storage::url($photo->photo_path) }}"
                                                        class="w-full h-40 object-cover rounded"
                                                        alt="Ad Image">

                                                    @if ($index === 3 && $photoCount > 4)
                                                        <div class="absolute inset-0 bg-black/30 backdrop-blur-sm flex items-center justify-center rounded">
                                                            <span class="text-white text-lg font-semibold">+{{ $photoCount - 4 }}</span>
                                                        </div>
                                                    @endif
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            @endif

                            {{-- TEXT CONTENT --}}
                            <div class="p-4 space-y-3">
                                
                                   <div class="flex justify-between">
                            <div>
                                <h2 class="text-lg font-semibold text-gray-800 dark:text-white">{{ $ad->user->name }}</h2>
                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                    Posted {{ \Carbon\Carbon::parse($ad->published_at)->format('Y-m-d') }}
                                </p>
                            </div>
                            @if($ad->user_id === auth()->user()->id)
                            <div>
                                <flux:dropdown position="bottom" align="end">
                                    <button><flux:icon.ellipsis-horizontal /></button>
                                    <flux:menu>
                                        <flux:menu.item wire:click="editAdvertisement({{ $ad->id }})">Edit</flux:menu.item>
                                        <flux:menu.item wire:click="confirmDelete({{ $ad->id }})">Delete</flux:menu.item>
                                    </flux:menu>
                                </flux:dropdown>
                            </div>
                            @endif
                            </div>

                                
                                <h2>{{$ad->title}}</h2>
                                <p class="text-sm text-gray-600 dark:text-gray-300">{{ $ad->description }}</p>

                                {{-- Tags --}}
                                @if ($ad->type)
                                    <div class="flex flex-wrap gap-2 mt-2">
                                        @foreach (explode(',', $ad->type) as $tag)
                                            <span class="bg-gray-100 dark:bg-gray-700 text-xs px-2 py-1 rounded-full font-medium text-gray-700 dark:text-gray-200">
                                                {{ trim($tag) }}
                                            </span>
                                        @endforeach
                                    </div>
                                @endif

                                {{-- Footer --}}
                                <div class="flex items-center gap-6 pt-2 text-gray-500 dark:text-gray-400 text-sm">
                                    <div class="flex items-center gap-1">
                                        <flux:icon.eye class="w-4 h-4" />
                                        <span>1.2k views</span>
                                    </div>
                                    <div class="flex items-center gap-1">
                                        <flux:icon.chat-bubble-oval-left-ellipsis class="w-4 h-4" />
                                        <span>8 comments</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-500 dark:text-gray-400">No advertisements found.</p>
                    @endforelse
                </div>

                <!-- Members -->
                <div x-show="tab === 'members'">
                    <h2 class="text-xl font-semibold mb-4">Members</h2>
                    <p class="text-gray-600 dark:text-gray-300">This is where the organization's members will be
                        listed.</p>
                </div>
            </div>
        </section>
    </div>
    <flux:modal name="about" variant="flyout" position="bottom">
                        <h2 class="text-lg font-semibold flex items-center gap-2 mb-4 text-gray-900 dark:text-white">
                            <flux:icon.information-circle class="size-6 text-red-900 dark:text-red-800" />
                            About
                        </h2>

                        <p class="text-gray-600 dark:text-gray-400 mb-4 leading-relaxed break-words">
                            Lorem ipsum dolor sit amet consectetur adipisicing elit. Pariatur natus aut cumque Lorem ipsum dolor sit, amet consectetur adipisicing elit. Vel, vero.
                        </p>

                        <div class="lg:space-y-4 grid grid-cols-2 gap-4 lg:grid-cols-1">
                            <div class="flex items-center gap-4">
                                <flux:badge size="lg" color="red">
                                    <flux:icon.calendar/>
                                </flux:badge>
                                <div>
                                    <div class="text-sm text-gray-600 dark:text-gray-300">Founded</div>
                                    <div class="font-semibold text-gray-900 dark:text-white">2020</div>
                                </div>
                            </div>

                            <div class="flex items-center gap-4">
                                <flux:badge size="lg" color="red">
                                    <flux:icon.envelope/>
                                </flux:badge>
                                <div >
                                    <div class="text-sm text-gray-600 dark:text-gray-300">Email</div>
                                    <a href="mailto:egames@university.edu" class="text-black dark:text-white underline break-all">
                                        egames@university.edu
                                    </a>
                                </div>
                            </div>

                            <div class="flex items-center gap-4">
                                <flux:badge size="lg" color="red">
                                    <flux:icon.globe-alt/>
                                </flux:badge>
                                <div>
                                    <div class="text-sm text-gray-600 dark:text-gray-300">Website</div>
                                    <a href="https://www.egames-org.com" target="_blank" class="text-black dark:text-white underline break-all">
                                        www.egames-org.com
                                    </a>
                                </div>
                            </div>

                            <div class="flex items-center gap-4">
                                <flux:badge size="lg" color="red">
                                    <flux:icon.users/>
                                </flux:badge>
                                <div>
                                    <div class="text-sm text-gray-600 dark:text-gray-300">Members</div>
                                    <div class="font-semibold text-black dark:text-white">12,312 Active Members</div>
                                </div>
                            </div>
                        </div>
    </flux:modal>
</div>

                