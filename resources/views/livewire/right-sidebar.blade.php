<div >
        {{-- DESKTOP --}}
    <div class="hidden lg:flex flex-col h-[100vh] sticky top-0 shadow overflow-y-auto py-5 gap-6 scrollbar-hover bg-zinc-900">

            <div class="space-y-4  w-full rounded-lg p-2">
                <flux:heading size="lg">Trending</flux:heading>
                <div class="grid h-auto gap-4">
                    <div class="grid items-center">No trending at the moment</div>
                </div>
                
            </div>


            @if(auth()->user()->isUser())
                <livewire:sidebar-group-chats/>
                {{-- @include('livewire.user.chat.partials.create-group') --}}
            @endif
           
            <livewire:active-voting />

           

                        <!-- Orgs -->
            <div class="w-full p-2 rounded-lg   ">
                <div class="flex justify-between">
                    <h2 class="font-semibold mb-3 flex gap-2">
                         Organizations
                    </h2>
                       
                </div>
                
                <div class="h-auto">
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
            
    </div>


        {{-- MOBILE --}}
        <flux:modal name="mobile-right-sidebar" class="bg-zinc-900" variant="flyout">
            
            <div class="space-y-4  w-full rounded-lg p-2">
                <flux:heading size="lg">Trending</flux:heading>
                <div class="grid h-auto gap-4">
                    <div class="grid items-center">No trending at the moment</div>
                </div>
                
            </div>


            @if(auth()->user()->role === 'user')
                <livewire:sidebar-group-chats/>
                {{-- @include('livewire.user.chat.partials.create-group') --}}
                
            @endif
            
            <livewire:active-voting />

           

                        <!-- Orgs -->
            <div class="w-full p-2 rounded-lg   ">
                <div class="flex justify-between">
                    <h2 class="font-semibold mb-3 flex gap-2">
                         Organizations
                    </h2>
                       
                </div>
                
                <div class="h-auto">
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
            
        

        </flux:modal>

</div>