<div class="min-h-screen px-10 py-5">
    {{-- Org Header --}}
    <div class="grid grid-cols-1 md:grid-cols-5 gap-4 items-center mb-6 ">
        
        <!-- Left: Org Info -->
        <section class="flex gap-6 col-span-1 md:col-span-4">
            <!-- Avatar -->
            <div class="flex items-center justify-center">
                <flux:avatar class="size-20" circle src="{{$org->profile ?? 'https://i.pravatar.cc/100?u=' . $this->org->id}}" />
            </div>

            <!-- Text Info -->
            <div class="flex flex-col justify-center space-y-1">
                <strong class="text-2xl text-gray-900 dark:text-white">{{ $this->org->name }}</strong>
                <span class="text-gray-600 dark:text-gray-300 text-sm">
                    Lorem ipsum dolor sit amet consectetur adipisicing elit. Quis, recusandae.
                </span>
                <div class="flex items-center gap-2 text-gray-500 dark:text-gray-200 text-sm mt-1">
                    <flux:icon.users class="size-4" />
                    <span>2,145 Followers</span>
                </div>
            </div>
        </section>

        <!-- Right: Follow Button -->
        <section class="flex justify-end items-center">
            <flux:button>
                <flux:icon.user-plus class="size-4" variant="solid" />
                Follow Organization
            </flux:button>
        </section>
        
    </div>

    
    <div class="grid grid-cols-1 md:grid-cols-5 gap-10">

        {{-- RIGHT ABOUT --}}
        <section class="col-span-2 border p-4 rounded-xl bg-white dark:bg-gray-800 h-fit sticky self-start top-6 shadow">
            <h2 class="text-lg font-semibold flex items-center gap-2 mb-4 text-gray-900 dark:text-white">
                <flux:icon.information-circle class="size-6 text-red-900 dark:text-red-800" />
                About
            </h2>

            <p class="text-gray-600 dark:text-gray-400 mb-4 leading-relaxed">
                Lorem ipsum dolor sit amet consectetur adipisicing elit. Pariatur natus aut cumque...
            </p>

            <div class="space-y-4">
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
                    <div>
                        <div class="text-sm text-gray-600 dark:text-gray-300">Email</div>
                        <a href="mailto:egames@university.edu" class="text-black dark:text-white underline truncate">
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
                        <a href="https://www.egames-org.com" target="_blank" class="text-black dark:text-white underline">
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
        </section>
      
        {{-- LEFT CONTENT --}}
        <section class="col-span-3">
                {{-- tabs --}}
            <div x-data="{tab: 'feed'}">
                <div class="grid grid-cols-3 shadow-md mb-8 ">
                    <button 
                        @click="tab = 'feed'"
                        :class="tab === 'feed' ? 'bg-red-950 border-b text-white' : ' text-gray-900  dark:text-gray-200'"
                        class="py-4 transition-all rounded-tl-xl ">
                        Feed
                    </button>
                    <button 
                        @click="tab = 'ads'"
                        :class="tab === 'ads' ? 'bg-red-950 border-b text-white' : ' text-gray-900  dark:text-gray-200 '"
                        class="py-4 transition-all">
                        Advertisement
                    </button>
                    <button 
                        @click="tab = 'members'"
                        :class="tab === 'members' ? 'bg-red-950 border-b text-white' : ' text-gray-900  dark:text-gray-200'"
                        class="py-4 transition-all rounded-tr-xl">
                        Members
                    </button>
            
                </div>
                {{-- FEED --}}
                <div x-show="tab === 'feed'" class="grid grid-cols-1 gap-4" >
                    
                    <div class="border rounded-xl h-auto p-4 grid grid-cols-1 gap-4">
                        <div class="flex justify-between">
                            <div class="flex items-center gap-4">
                                <h2 class="font-bold text-2xl">TITLE</h2>
                                <span class="text-md"> ● 2 Hours Ago</span>
                            </div>
                            <flux:icon.ellipsis-horizontal/>
                        </div>
                       
                        <p class="text-gray-700 dark:text-gray-300">ABOUT: Lorem ipsum dolor sit amet consectetur adipisicing elit. Laborum earum nam corporis sequi nulla? Repudiandae odio ad mollitia nisi praesentium ea harum quisquam officia! Quisquam, recusandae. Fugiat ab sint mollitia?</p>

                        <div class="h-100 w-full rounded-xl border border-neutral-200 dark:border-neutral-700">
                            
                        </div>

                        {{-- REACTION / COMMENT --}}
                        <div class="flex justify-between">

                            <div class="flex gap-8">
                                {{-- HEART REACT --}}
                                <div class="flex gap-2">
                                    <flux:icon.heart/>
                                    123
                                </div>

                                {{-- COMMENT --}}
                                <div class="flex gap-2">
                                    <flux:icon.chat-bubble-oval-left-ellipsis/>
                                    312
                                </div>
                            </div>

                            admin
                        </div>
                    </div>
                    
                </div>
                
                {{-- ADVERTISEMENT --}}
                <div x-show="tab === 'ads'"  >


                    <div class="border rounded-xl h-auto p-4 grid grid-cols-1 gap-4">
                        <div class="flex justify-between">
                            <div class="flex items-center gap-4">
                                <h2 class="font-bold text-2xl">TITLE</h2>
                                <span class="text-md"> ● 2 Hours Ago</span>
                            </div>
                            <flux:icon.ellipsis-horizontal/>
                        </div>
                       
                        <p class="text-gray-700 dark:text-gray-300">ABOUT: Lorem ipsum dolor sit amet consectetur adipisicing elit. Laborum earum nam corporis sequi nulla? Repudiandae odio ad mollitia nisi praesentium ea harum quisquam officia! Quisquam, recusandae. Fugiat ab sint mollitia?</p>

                        <div class="h-100 w-full rounded-xl border border-neutral-200 dark:border-neutral-700">
                            
                        </div>

                        {{-- REACTION / COMMENT --}}
                        <div class="flex gap-8">

                            {{-- HEART REACT --}}
                            <div class="flex gap-2">
                                <flux:icon.heart/>
                                123
                            </div>

                            {{-- COMMENT --}}
                            <div class="flex gap-2">
                                <flux:icon.chat-bubble-oval-left-ellipsis/>
                                312
                            </div>

                        </div>
                    </div>

                </div>


                {{-- MEMBERS --}}
                <div x-show="tab === 'members'"  >

                    <h2 class="text-xl font-semibold mb-4">Members</h2>
                    <p class="text-gray-600 dark:text-gray-300">This is where the organization's members will be listed.</p>

                </div>
            </div>
          
        </section>

        

    </div>
</div>
