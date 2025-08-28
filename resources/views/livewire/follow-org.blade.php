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
                    <span>{{ $org->followers()->count() }} Followers</span>
                </div>
            </div>
        </section>

        <!-- Right: Follow + Modal Button -->
        <section class="flex justify-start md:justify-end gap-4 items-center w-full md:w-auto mt-2 md:mt-0">

            @if(auth()->user()->role === 'user')
                <flux:button class="w-full sm:w-auto" variant="primary" color="{{$isFollowing ? 'red' : ''}}" wire:click="toggleFollow" 
                    icon="{{ $isFollowing ? 'check-circle' : 'user-plus' }}" >
                    {{ $isFollowing ? 'Following' : 'Follow' }}
                </flux:button>
            @endif

            <!-- Only visible below md -->
            <flux:modal.trigger name="about" class="md:hidden">
                <flux:button icon-leading="information-circle" icon-trailing="chevron-down">About</flux:button>
            </flux:modal.trigger>
        </section>
    </div>