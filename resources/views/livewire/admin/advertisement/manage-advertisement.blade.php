<div 
    x-init="Echo.channel('manage-ads')
                .listen('.ads.post', (e) => {
                    console.log('new ads post', e.ads);
                    Livewire.dispatch('newAdPosted');
                });
            Echo.channel('manage-feeds')
                .listen('.feed.post', (e) => {
                    console.log('new feed post', e.feed);
                    Livewire.dispatch('newFeedPosted');
                });
                
            "
class="">

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 h-full">
        <div class="w-full space-y-4 col-span-2 py-5 lg:px-20" >
            @if(auth()->user()->role !== 'user')
            <section class="flex bg-zinc-100 dark:bg-zinc-800 text-black dark:text-white rounded-lg gap-4 p-4">
            @if(auth()->user()->profile_image)
                    <flux:avatar 
                        circle 
                        {{-- src="{{ asset('storage/' . auth()->user()->profile_image) }}"  --}}
                        src="{{ Storage::disk('digitalocean')->url(auth()->user()->profile_image) }}"
                        class="overflow-hidden"
                        />
                @else
                    <flux:avatar
                        circle
                        :initials="auth()->user()->initials()"   
                        />
                @endif


                <flux:modal.trigger name="add-advertisement">
                    <flux:button class="w-full">What's on your mind?</flux:button>
                </flux:modal.trigger>                
            </section>
            @endif
            <div class="flex justify-between">
                <flux:dropdown class="flex items-center">
                    <flux:button icon:trailing="chevron-down" size="sm">
                        {{ $organizationFilter 
                            ? $orgs->firstWhere('id', $organizationFilter)?->name 
                            : 'All Organizations' 
                        }}
                    </flux:button>

                    <span class="text-xs flex gap-2 p-2">
                        <flux:icon.bars-3-bottom-left class="size-4" />
                        {{ count($this->filteredAdvertisements) }} Active Advertisement
                    </span>

                    <flux:menu>
                        <flux:menu.item wire:click="$set('organizationFilter', null)">
                            All Organizations
                        </flux:menu.item>
                        @foreach($orgs as $org)
                            <flux:menu.item wire:click="$set('organizationFilter', '{{ $org->id }}')">
                                {{ $org->name }}
                            </flux:menu.item>
                        @endforeach
                    </flux:menu>
                </flux:dropdown>

                @if ($organizationFilter)
                    <flux:button color="gray" size="sm" wire:click="resetFilters">
                        Reset Filters
                    </flux:button>
                @endif

            </div>

            {{-- ADVERTISEMENTS --}}
            @include('livewire.admin.advertisement.partials.advertisement-feed') 
            @include('livewire.admin.advertisement.partials.photos-modal') 
            


           

        </div>

        {{-- RIGHT SIDEBAR --}}
        <livewire:right-sidebar />
    </div>
{{-- 
    <flux:modal name="mobile-right-sidebar" class="md:hidden" variant="flyout">
        <div class="flex flex-col col-span-2 gap-6 shadow  my-5 ">
            
            <livewire:active-voting />


            @if(auth()->user()->role === 'user')
            <div class="w-full p-2 h-auto">
                <div class="flex justify-between items-center">
                    <h2 class="font-semibold mb-3 flex gap-2 items-center">
                        Group Chats <flux:badge size="sm" color="green">3 / 4</flux:badge>
                        
                        
                    </h2>
                    <flux:modal.trigger name="create-group-chat">
                        <flux:button variant="ghost" size="sm" icon="plus" />
                    </flux:modal.trigger>
                </div>
            </div>
            @endif

                        
            <div class="w-full p-2 rounded-lg h-auto">
                <div class="flex justify-between">
                    <h2 class="font-semibold mb-3 flex gap-2">
                         Organizations
                    </h2>
                       
                </div>
                
                <div>
                @forelse ($orgs as $org)
                    <a href="{{ route('org.profile', ['org' => $org->id]) }}" >
                        <div class="flex gap-4 items-center text-sm p-1 hover:bg-gray-100 dark:hover:bg-gray-700 rounded">
                           
                             @if ($org->profile_image)
                                <flux:avatar
                                    circle
                                    src="{{ asset('storage/' . $org->profile_image) }}"
                                    
                                />
                            @else
                                <flux:avatar
                                    circle
                                    :initials="$org->initials()"
                                    
                                />
                            @endif
                            <span class="truncate">{{ $org->name }}</span>
                        </div>
                    </a>
                @empty
                    <p class="text-sm text-gray-400">No data available.</p>
                @endforelse
                </div>
            </div>

            
            <div class=" w-full p-4 rounded-lg  shadow">
                <h2 class="font-semibold mb-3">💬 Help & Support</h2>
                <ul class="text-sm text-gray-700 dark:text-gray-300 space-y-1">
                    <li><a href="#" class="hover:underline">📘 How to post an advertisement</a></li>
                    <li><a href="#" class="hover:underline">📩 Contact administrator</a></li>
                    <li><a href="#" class="hover:underline">⚙️ Manage your organization</a></li>
                </ul>
            </div>
        </div>
    </flux:modal> --}}

    {{-- ADVERTISEMENT MODAL --}}
    @include('livewire.admin.advertisement.partials.create-modal') 
    @include('livewire.admin.advertisement.partials.delete-modal') 
    @include('livewire.admin.advertisement.partials.edit-modal') 
</div>
