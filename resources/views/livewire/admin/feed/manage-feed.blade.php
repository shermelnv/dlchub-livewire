<div 
    x-data
    x-init="Echo.channel('manage-feeds')
                .listen('.feed.post', (e) => {
                    console.log('new feed post', e.feed);
                    Livewire.dispatch('newFeedPosted');
                });"
    class="px-5">

    <!-- ========== MAIN GRID ========== -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 h-full">
        <!-- ========== LEFT COLUMN: FEED AREA ========== -->
        <div class="w-full col-span-2 flex flex-col gap-6 py-5 scrollbar-auto-hide lg:px-20">

            <!-- ====== CREATE POST SECTION ====== -->
            @if(auth()->user()->role !== 'user')
            <section class="flex bg-zinc-100 dark:bg-zinc-800 text-black dark:text-white rounded-lg gap-4 p-4">
                @if(auth()->user()->profile_image)
                    <flux:avatar 
                        circle 
                        {{-- src="{{ asset('storage/' . auth()->user()->profile_image) }}"  --}}
                        src="{{ Storage::disk('digitalocean')->url(auth()->user()->profile_image) }}"
                        />
                @else
                    <flux:avatar
                        circle
                        :initials="auth()->user()->initials()"   
                        />
                @endif
   
                                
                            
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
                            {{ $organizationFilter
                                ? $orgs->firstWhere('id', $organizationFilter)?->name
                                : 'All Organizations' }}
                        </flux:button>

                        <flux:menu>
                            <flux:menu.item wire:click="$set('organizationFilter', null)">
                                All Organizations
                            </flux:menu.item>

                            @foreach ($orgs as $org)
                                <flux:menu.item wire:click="$set('organizationFilter', {{ $org->id }})">
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
                        
                            <div class="relative w-full bg-gray-100  dark:bg-gray-700 overflow-hidden cursor-pointer" wire:click="viewPostInfo({{ $feed->id }})">
                                <img src="{{ Storage::disk('digitalocean')->url($feed->photo_url) }}" loading="lazy" class="object-contain w-full h-full" />

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
                                    <flux:avatar
                                        {{-- avatar="{{ asset('storage/' . $feed->user->profile_image) }}" --}}
                                        src="{{ Storage::disk('digitalocean')->url($feed->user->profile_image) }}"
                                        icon:trailing="chevrons-up-down"
                                        class="w-8 h-8 rounded-full overflow-hidden object-cover"
                                    />
                                    @else
                                        <flux:avatar
                                        circle
                                            :initials="$feed->user->initials()"
                                            icon:trailing="chevrons-up-down"
                                            class="w-8 h-8 rounded-full"
                                        />
                                    @endif
                                <div>
                                    <a href="{{ route('org.profile', ['orgId' => $feed->user->id]) }}" wire:navigate>
                                        <h2 class="text-lg font-semibold text-gray-800 dark:text-white">{{ $feed->user->name }}</h2>
                                    </a>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 flex items-center">
                                        Posted {{ \Carbon\Carbon::parse($feed->published_at)->format('Y-m-d') }} ・ 
                                            @if($feed->privacy === 'public') 
                                                Public
                                            @else 
                                                Private
                                            @endif 
                                    </p>
                                </div>
                            </div>
                            @if($feed->user_id === auth()->user()->id)
                            <div>
                                {{-- <flux:dropdown position="bottom" align="end">
                                    <button><flux:icon.ellipsis-horizontal /></button>
                                    <flux:menu>
                                        <flux:menu.item wire:click="editPost({{ $feed->id }})">Edit</flux:menu.item>
                                        <flux:menu.item wire:click="confirmDelete({{ $feed->id }})">Delete</flux:menu.item>
                                    </flux:menu>
                                </flux:dropdown> --}}

                                <flux:button 
                                    wire:click="editPost({{ $feed->id }})"
                                    wire:loading.attr="disabled" 
                                    wire:target="editPost({{ $feed->id }})"
                                    size="sm"
                                >
                                    {{-- normal state --}}
                                    <span wire:loading.remove wire:target="editPost({{ $feed->id }})" class="flex items-center">
                                        <flux:icon.pencil class="size-4 mr-1" />
                                        Edit
                                    </span>

                                    {{-- loading state --}}
                                    <span wire:loading wire:target="editPost({{ $feed->id }})" class="flex items-center">
                                        <flux:icon.loading class="size-4" />
                                    </span>
                                </flux:button>



                        <flux:button 
                            wire:click="confirmDelete({{ $feed->id }})"
                            wire:loading.attr="disabled" 
                            wire:target="confirmDelete({{ $feed->id }})"
                            size="sm"
                            variant="danger"
                        >
                            {{-- Normal state --}}
                            <span 
                                wire:loading.remove 
                                wire:target="confirmDelete({{ $feed->id }})" 
                                class="flex items-center"
                            >
                                <flux:icon.trash class="mr-1 size-4" />
                                Delete
                            </span>

                            {{-- Loading state --}}
                            <span 
                                wire:loading 
                                wire:target="confirmDelete({{ $feed->id }})" 
                                class="flex items-center"
                            >
                                <flux:icon.loading class="size-4" />
                            </span>
                        </flux:button>


                            </div>
                            @endif
                            </div>
                            
                            

                            
                                <h2 class="text-base font-semibold text-gray-800 dark:text-white">{{ $feed->title }}</h2>
                      
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
                        <div class="flex flex-col gap-2  text-gray-500 dark:text-gray-400 text-sm">
                            <div class="flex items-center gap-6">
                                <!-- Heart -->
                                <div class="flex items-center gap-1 cursor-pointer" wire:click="toggleHeart({{ $feed->id }})">
                                    @php
                                        $userReacted = $feed->reactions->where('user_id', auth()->id())->where('type', 'heart')->count() > 0;
                                        $count = $feed->reactions->where('type', 'heart')->count();
                                    @endphp

                                    @if($userReacted)
                                        <flux:icon.heart variant="solid" color="red"  wire:loading.remove wire:target="toggleHeart({{ $feed->id }})"/>
                                    @else
                                        <flux:icon.heart wire:loading.remove wire:target="toggleHeart({{ $feed->id }})"/>
                                    @endif

                                    <flux:icon.loading wire:loading wire:target="toggleHeart({{ $feed->id }})"/>

                                    <span>{{ $count }}</span>
                                </div>

                                <!-- Comment count -->
                                <div class="flex items-center gap-1 cursor-pointer">
                                    <flux:icon.chat-bubble-oval-left-ellipsis  />
                                    <span>{{ $feed->comments->count() }}</span>
                                </div>
                            </div>

                                <!-- Comment box -->
                                @if($feed->comments->count() >= 10)
                                    <div class="text-xs text-red-500">Comment limit reached.</div>
                                @endif
                                <form wire:submit.prevent="addComment({{ $feed->id }})" 
                                    class="gap-2 mt-1 {{ $feed->comments->count() >= 10 ? 'hidden' : 'flex' }}">
                                    <flux:input.group>
                                        <flux:input wire:model.defer="comments.{{ $feed->id }}" placeholder="Add a comment..." style="outline: none; box-shadow: none;"/>
                                        <flux:button type="submit" >
                                            <flux:icon.paper-airplane variant="solid" class="text-blue-500 dark:text-blue-300"/>
                                        </flux:button>
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
                                                <flux:avatar
                                                    circle

                                                    {{-- avatar="{{ asset('storage/' . $comment->user->profile_image) }}" --}}
                                                    src="{{ Storage::disk('digitalocean')->url($comment->user->profile_image) }}"
                                                    icon:trailing="chevrons-up-down"
                                                    class="size-8 rounded-full overflow-hidden object-cover"
                                                />
                                                @else
                                                    <flux:avatar
                                                        circle
                                                        
                                                        :initials="$comment->user->initials()"
                                                        icon:trailing="chevrons-up-down"
                                                        class="size-8 rounded-full"
                                                    />
                                                @endif
                                                    <div>
                                                        <div class="bg-gray-100 dark:bg-gray-700 rounded-lg p-2">
                                                            <div class="font-semibold">{{ $comment->user->name }}</div>
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
                                                <flux:avatar
                                                    circle

                                                    {{-- avatar="{{ asset('storage/' . $comment->user->profile_image) }}" --}}
                                                    src="{{ Storage::disk('digitalocean')->url($comment->user->profile_image) }}"
                                                    icon:trailing="chevrons-up-down"
                                                    class="size-8 rounded-full"
                                                />
                                                @else
                                                    <flux:avatar
                                                    circle
                                                        
                                                        :initials="$comment->user->initials()"
                                                        icon:trailing="chevrons-up-down"
                                                        class="size-8 rounded-full"
                                                    />
                                                @endif
                                                    <div>
                                                        <div class="bg-gray-100 dark:bg-gray-700 rounded-lg p-2">
                                                            <div class="font-semibold">{{ $comment->user->name }}</div>
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
                                                <flux:avatar
                                                    circle

                                                    {{-- avatar="{{ asset('storage/' . $comment->user->profile_image) }}" --}}
                                                    src="{{ Storage::disk('digitalocean')->url($comment->user->profile_image) }}"
                                                    icon:trailing="chevrons-up-down"
                                                    class="size-8 rounded-full"
                                                />
                                                @else
                                                    <flux:avatar
                                                        circle
                                                        
                                                        :initials="$comment->user->initials()"
                                                        icon:trailing="chevrons-up-down"
                                                        class="size-8 rounded-full"
                                                    />
                                                @endif
                                                    <div>
                                                        <div class="bg-gray-100 dark:bg-gray-700 rounded-lg p-2">
                                                            <div class="font-semibold">{{ $comment->user->name }}</div>
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
                    <div class="text-center text-gray-500 dark:text-gray-400">No posts available.</div>
                @endforelse
            </div>
        </div>

        <!-- ========== RIGHT SIDEBAR ========== -->


      <livewire:right-sidebar />
 
    </div>
    <!-- ====== DELETE POST MODAL ====== -->
    @include('livewire.admin.feed.partials.delete-modal')

    <!-- ====== CREATE POST MODAL ====== -->
    @include('livewire.admin.feed.partials.create-modal')

    <!-- ====== EDIT POST MODAL ====== -->
    @include('livewire.admin.feed.partials.edit-modal')

    @include('livewire.admin.feed.partials.post-info')
</div>
