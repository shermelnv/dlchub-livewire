<div class="min-h-screen px-4 sm:px-6 md:px-10 py-5">
    <!-- Top: Organization Info -->
    <livewire:follow-org :org="$org" />

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
                    <div x-show="tab === 'feed'" class="space-y-6">
                        @forelse($feeds as $feed)
                            <div class="pb-4 bg-white dark:bg-gray-800 shadow-md hover:shadow-xl rounded-2xl overflow-hidden border border-gray-200 dark:border-gray-700 transition duration-300 ease-in-out">
                                
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

                                <div class="p-2 pb-0 space-y-2">
                                    <!-- Header -->
                                    <div class="flex justify-between">
                                        <div class="flex gap-2 items-center">
                                            @if ($feed->user->profile_image)
                                                <flux:avatar avatar="{{ asset('storage/' . $feed->user->profile_image) }}" class="w-8 h-8 rounded-full object-cover" />
                                            @else
                                                <flux:avatar circle :initials="$feed->user->initials()" class="w-8 h-8 rounded-full" />
                                            @endif

                                            <div>
                                                <h2 class="text-lg font-semibold text-gray-800 dark:text-white">{{ $feed->user->name }}</h2>
                                                <p class="text-xs text-gray-500 dark:text-gray-400 flex items-center">
                                                    Posted {{ \Carbon\Carbon::parse($feed->published_at)->format('Y-m-d') }} ・ 
                                                    @if($feed->privacy === 'public') 
                                                        <flux:icon.globe-asia-australia class="size-4" /> 
                                                    @else 
                                                        <flux:icon.lock-closed class="size-4" />
                                                    @endif
                                                </p>
                                            </div>
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

                                    <!-- Title & Content -->
                                    <h2 class="text-base font-semibold text-gray-800 dark:text-white">{{ $feed->title }}</h2>
                                    <p class="text-sm text-gray-600 dark:text-gray-300">{{ $feed->content }}</p>

                                    <!-- Tags -->
                                    @if ($feed->type)
                                        <div class="flex flex-wrap gap-2 mt-2">
                                            <span class="bg-gray-100 dark:bg-gray-700 text-xs px-2 py-1 rounded-full font-medium text-gray-700 dark:text-gray-200">
                                                {{ $feed->type }}
                                            </span>
                                        </div>
                                    @endif

                                    <!-- Footer: reactions and comments -->
                                    <div class="flex flex-col gap-2 text-gray-500 dark:text-gray-400 text-sm">
                                        <div class="flex items-center gap-6">
                                            <!-- Heart -->
                                            <div class="flex items-center gap-1 cursor-pointer" wire:click="toggleHeart({{ $feed->id }})">
                                                @php
                                                    $userReacted = $feed->reactions->where('user_id', auth()->id())->where('type', 'heart')->count() > 0;
                                                    $count = $feed->reactions->where('type', 'heart')->count();
                                                @endphp

                                                @if($userReacted)
                                                    <flux:icon.heart variant="solid" color="red"/>
                                                @else
                                                    <flux:icon.heart/>
                                                @endif

                                                <span>{{ $count }}</span>
                                            </div>

                                            <!-- Comment count -->
                                            <div class="flex items-center gap-1 cursor-pointer">
                                                <flux:icon.chat-bubble-oval-left-ellipsis />
                                                <span>{{ $feed->comments->count() }}</span>
                                            </div>
                                        </div>
                                         <!-- Comment box -->
                                   <!-- Comment box -->
                                @if($feed->comments->count() >= 10)
                                    <div class="text-xs text-red-500">Comment limit reached.</div>
                                @endif
                                <form wire:submit.prevent="addComment({{ $feed->id }})" 
                                    class="gap-2 mt-1 {{ $feed->comments->count() >= 10 ? 'hidden' : 'flex' }}">
                                    <flux:input.group>
                                        <flux:input wire:model.defer="comments.{{ $feed->id }}" placeholder="Add a comment..." />
                                        <flux:button icon="paper-airplane" type="submit" />
                                    </flux:input.group>
                                </form>

                                <!-- Comments Section -->
                                <div x-data="{ open: false }" class="mt-2 text-sm text-gray-700 dark:text-gray-300">
                                    @php
                                        $sortedComments = $feed->comments->sortByDesc('created_at');
                                    @endphp

                                    @if($sortedComments->count() > 1)
                                        <!-- Toggle button -->
                                        <flux:button variant="ghost" @click="open = !open" 
                                                class="w-full flex items-center justify-center gap-1 text-xs text-blue-500 m-1">
                                            <span x-text="open ? 'Hide comments' : 'View all comments'"></span>

                                            <flux:icon.chevron-down x-bind:class="open ? 'rotate-180' : ''" 
                                                class="w-4 h-4 transition-transform duration-200" />
                                        </flux:button>
                                    @endif

                                    @if($sortedComments->count() <= 1)
                                        <!-- Just show all comments if 3 or fewer -->
                                        <div class="space-y-1">
                                            @foreach($sortedComments as $comment)
                                                <div class="flex items-start gap-2">
                                                    @if ($comment->user->profile_image)
                                                <flux:profile
                                                circle

                                                    avatar="{{ asset('storage/' . $comment->user->profile_image) }}"
                                                    icon:trailing="chevrons-up-down"
                                                    class="w-8 h-8 rounded-full overflow-hidden object-cover"
                                                />
                                                @else
                                                    <flux:profile
                                                    circle
                                                        
                                                        :initials="$comment->user->initials()"
                                                        icon:trailing="chevrons-up-down"
                                                        class="w-8 h-8 rounded-full"
                                                    />
                                                @endif
                                                    <div>
                                                        <div class="bg-gray-700 rounded-lg p-2">
                                                            <div class="font-semibold">{{ $comment->user->name }}:</div>
                                                            <div class="max-w-xl break-words">{{ $comment->comment }}</div>
                                                        </div>
                                                        <div class="text-xs text-gray-500 dark:text-gray-400">
                                                            {{ \Carbon\Carbon::parse($comment->created_at)->justDiffForHumans() }}
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <!-- Show latest 3 by default -->
                                        <div class="space-y-1">
                                            @foreach($sortedComments->take(1) as $comment)
                                                <div class="flex items-start gap-2">
                                                    @if ($comment->user->profile_image)
                                                <flux:profile
                                                circle

                                                    avatar="{{ asset('storage/' . $comment->user->profile_image) }}"
                                                    icon:trailing="chevrons-up-down"
                                                    class="w-8 h-8 rounded-full overflow-hidden object-cover"
                                                />
                                                @else
                                                    <flux:profile
                                                    circle
                                                        
                                                        :initials="$comment->user->initials()"
                                                        icon:trailing="chevrons-up-down"
                                                        class="w-8 h-8 rounded-full"
                                                    />
                                                @endif
                                                    <div>
                                                        <div class="bg-gray-700 rounded-lg p-2">
                                                            <div class="font-semibold">{{ $comment->user->name }}:</div>
                                                            <div class="max-w-xl break-words">{{ $comment->comment }}</div>
                                                        </div>
                                                        <div class="text-xs text-gray-500 dark:text-gray-400">
                                                            {{ \Carbon\Carbon::parse($comment->created_at)->justDiffForHumans() }}
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>

                                        <!-- Hidden comments -->
                                        <div class="space-y-1 mt-2" x-show="open" x-collapse>
                                            @foreach($sortedComments->skip(1) as $comment)
                                                <div class="flex items-start gap-2">
                                                    @if ($comment->user->profile_image)
                                                <flux:profile
                                                circle

                                                    avatar="{{ asset('storage/' . $comment->user->profile_image) }}"
                                                    icon:trailing="chevrons-up-down"
                                                    class="w-8 h-8 rounded-full overflow-hidden object-cover"
                                                />
                                                @else
                                                    <flux:profile
                                                    circle
                                                        
                                                        :initials="$comment->user->initials()"
                                                        icon:trailing="chevrons-up-down"
                                                        class="w-8 h-8 rounded-full"
                                                    />
                                                @endif
                                                    <div>
                                                        <div class="bg-gray-700 rounded-lg p-2">
                                                            <div class="font-semibold">{{ $comment->user->name }}:</div>
                                                            <div class="max-w-lg break-words">{{ $comment->comment }}</div>
                                                        </div>
                                                        <div class="text-xs text-gray-500 dark:text-gray-400">
                                                            {{ \Carbon\Carbon::parse($comment->created_at)->justDiffForHumans() }}
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center text-gray-500 dark:text-gray-400">No feed posts available.</div>
                        @endforelse
                    </div>

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
                        <div class="mt-4">
                            <ul class="mt-2 space-y-2">
                                @foreach($org->followers as $follower)
                                    <li class="flex items-center space-x-2">
                                        <img src="{{ $follower->profile_image ?? 'https://ui-avatars.com/api/?name='.$follower->name }}" 
                                            class="w-8 h-8 rounded-full" alt="{{ $follower->name }}">
                                        <span>{{ $follower->name }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>

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

                