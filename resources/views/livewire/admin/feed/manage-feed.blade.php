<div>
    <!-- Header Section -->
    <div class="relative mb-6 w-full">
        <flux:heading size="xl" level="1">
            {{ __('School Announcement') }}
        </flux:heading>

        <flux:subheading size="lg" class="mb-6">
            {{ __('Stay Updated with the latest news and important information from our university') }}
        </flux:subheading>

        <flux:separator variant="subtle" />

    </div>

    <!-- Main Grid Layout -->
    <div class="grid grid-cols-1 md:grid-cols-4 lg:grid-cols-4 gap-6 h-full">
        <!-- Left/Main Column -->
        <div class="w-full col-span-3 flex flex-col gap-6">
            
            <!-- What's on your mind -->
            <section class="flex bg-gray-900 rounded-lg gap-4 p-4">
                <flux:avatar circle src="https://unavatar.io/x/calebporzio" />

                <flux:modal.trigger name="post-feed">
                    <flux:button class="w-full">What's on your mind?</flux:button>
                </flux:modal.trigger>
              
                    <flux:modal name="post-feed">
                        <form wire:submit.prevent="createPost">
                        <div class="space-y-6">
                            {{-- Modal Header --}}
                            <div>
                                <flux:heading size="lg">Create Feed Post</flux:heading>
                                <flux:text class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                                    Share an announcement, event, or important update.
                                </flux:text>
                            </div>

                            {{-- Content --}}
                            <div class="flex flex-col gap-4">
                                <flux:input label="Post Title" wire:model.defer="title" placeholder="Post Title"/>

                                <flux:textarea label="Post Content" wire:model.defer="content" placeholder="What's on your mind? (Max 2000 Characters)"/>

                                <div class="grid grid-cols-2 gap-4">
                                    <flux:select label="Category" wire:model.defer="category">
                                        <flux:select.option>Select Category</flux:select.option>
                                        <flux:select.option value="Academic">Academic</flux:select.option>
                                        <flux:select.option value="Events">Events</flux:select.option>
                                        <flux:select.option value="Student Life">Student Life</flux:select.option>
                                    </flux:select>
                                

                                    <flux:input label="Department" wire:model.defer="department" placeholder="ex. IT"/>
                            
                                </div>

                                {{-- Submit Button --}}
                                <div class="flex justify-end">
                                    <flux:button type="submit" variant="primary">
                                        Post
                                    </flux:button>
                                </div>
                            </div>
                        </form>
                </flux:modal>

            </section>

            <!-- Filters -->
            <section class="bg-white dark:bg-gray-900 rounded-xl p-4 shadow space-y-6">
                
                <!-- Department & Date Filters -->
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 flex-wrap">
                    
                    <!-- Department Filter -->
                    <div class="flex flex-col sm:flex-row sm:items-center gap-2 sm:gap-4">
                        <flux:label class="text-gray-700 dark:text-gray-200">Department:</flux:label>

                        <flux:field class="w-full sm:w-[12rem]">
                            <flux:select wire:model="department" placeholder="All Departments">
                                <flux:select.option value="">All Departments</flux:select.option>
                                <flux:select.option value="IT">IT</flux:select.option>
                                <flux:select.option value="Business">Business</flux:select.option>
                                <flux:select.option value="Engineering">Engineering</flux:select.option>
                                <flux:select.option value="Education">Education</flux:select.option>
                            </flux:select>

                        </flux:field>
                    </div>

                    <!-- Date Range Filter -->
                    <div class="flex flex-col sm:flex-row sm:items-center gap-2 sm:gap-4">
                        <flux:label class="text-gray-700 dark:text-gray-200">Date Range:</flux:label>

                        <flux:field>
                            <flux:input wire:model="dateFrom"  type="date" class="dark:bg-gray-800 dark:text-white" />
                        </flux:field>
                        <flux:label class="text-gray-500 dark:text-gray-300">to</flux:label>

                        <flux:field>
                            <flux:input wire:model="dateTo" type="date" class="dark:bg-gray-800 dark:text-white" />
                        </flux:field>
                    </div>
                </div>

                <div class="flex justify-between items-center">
                    <!-- Categories Filter -->
                    <div class="flex flex-col sm:flex-row sm:items-center gap-2 sm:gap-4">
                        <flux:label class="text-gray-700 dark:text-gray-200">Categories:</flux:label>

                        @php
                            $allCategories = ['Academic', 'Events', 'Administrative', 'Student Life'];
                        @endphp

                        @foreach ($allCategories as $cat)
                            <button
                                wire:click="toggleCategory('{{ $cat }}')"
                                class="px-3 py-1 rounded-full text-sm transition
                                    {{ $category === $cat 
                                        ? 'bg-red-900 text-white' 
                                        : 'bg-gray-200 text-gray-800 dark:bg-gray-700 dark:text-gray-100' }}">
                                {{ $cat }}
                            </button>
                        @endforeach


                    </div>

                    <flux:button wire:click="resetFilters" variant="ghost">Clear Filters</flux:button>
                    <flux:button wire:click="filter" >Apply Filter</flux:button>


                </div>
                

                
            </section>

            <div class="space-y-6">

                {{-- Feed List --}}
                @forelse ($feeds as $feed)
                    <div class="bg-white dark:bg-gray-800 shadow rounded-2xl p-6 border border-gray-200 dark:border-gray-700">
                        <div class="flex justify-between items-start">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $feed->title }}</h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    {{ $feed->category }} • {{ $feed->department }} • {{ $feed->published_at->format('F j, Y') }}
                                </p>
                            </div>
                            <div class="text-sm text-gray-400 dark:text-gray-500">
                                by {{ $feed->user->name ?? 'Unknown' }}
                            </div>
                        </div>

                        <p class="mt-4 text-gray-700 dark:text-gray-300 text-sm leading-relaxed">
                            {{ $feed->content }}
                        </p>
                    </div>
                @empty
                    <div class="text-center text-gray-500 dark:text-gray-400">
                        No posts available.
                    </div>
                @endforelse
            </div>

        </div>
    </div>
</div>
