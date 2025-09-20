<div class="space-y-6">
    @forelse ($this->filteredAdvertisements as $ad)
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
                        <img 
                        {{-- src="{{ Storage::url($photos[0]->photo_path) }}" --}}
                        src="{{ Storage::disk('digitalocean')->url($photos[0]->photo_path) }}"
                             class="w-full h-64 object-cover rounded cursor-pointer"
                             alt="Ad Image"
                             wire:click="viewPhotos({{ $ad->id }})"
                             >
                    @elseif ($photoCount === 2)
                        {{-- Style 2: 2 horizontal side-by-side --}}
                        <div class="grid grid-cols-2 gap-1 cursor-pointer" wire:click="viewPhotos({{ $ad->id }})">
                            @foreach ($photos as $photo)
                                <img src="{{ Storage::url($photo->photo_path) }}"
                                     class="w-full h-64 object-cover rounded "
                                     alt="Ad Image">
                            @endforeach
                        </div>
                    @elseif ($photoCount === 3)
                    {{-- Style 6: 1 tall image left, 2 stacked images right --}}
                    <div class="grid grid-cols-2 gap-1 h-64 cursor-pointer" wire:click="viewPhotos({{ $ad->id }})">
                        {{-- Left: tall image --}}
                        <div class="h-64">
                            <img src="{{ Storage::url($photos[0]->photo_path) }}"
                                class="w-full h-full object-cover rounded"
                                alt="Ad Image">
                        </div>

                        {{-- Right: 2 stacked images --}}
                        <div class="grid grid-rows-2 gap-1 h-64 cursor-pointer" wire:click="viewPhotos({{ $ad->id }})">
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
                        <div class="grid grid-cols-2 gap-1 cursor-pointer" wire:click="viewPhotos({{ $ad->id }})">
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
                <!-- Header -->
                            <div class="flex justify-between">
                            <div class="flex gap-4 items-center">

                               @if ($ad->user->profile_image)
                                    <flux:avatar
                                        {{-- avatar="{{ asset('storage/' . $ad->user->profile_image) }}" --}}
                                        src="{{ Storage::disk('digitalocean')->url($ad->user->profile_image) }}"
                                        icon:trailing="chevrons-up-down"
                                        class="w-8 h-8 rounded-full overflow-hidden object-cover"
                                    />
                                    @else
                                        <flux:avatar
                                        circle
                                            :initials="$ad->user->initials()"
                                            icon:trailing="chevrons-up-down"
                                            class="w-8 h-8 rounded-full"
                                        />
                                    @endif

                                <div>
                                <a href="{{ route('org.profile', ['orgId' => $ad->user->id]) }}" wire:navigate>
                                    <h2 class="text-lg font-semibold text-gray-800 dark:text-white">{{ $ad->user->name }}</h2>
                                </a>
                                <p class="text-xs text-gray-500 dark:text-gray-400 flex items-center">
                                    Posted {{ \Carbon\Carbon::parse($ad->published_at)->format('Y-m-d') }} ・ 
                                    @if($ad->privacy === 'public') 
                                            <flux:icon.globe-asia-australia class="size-4" /> 
                                            @else 
                                                <flux:icon.lock-closed class="size-4" />
                                            @endif 
                                </p>
                                </div>
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

            </div>
        </div>
    @empty
        <div class="text-center text-gray-500 dark:text-gray-400">
            No advertisements posted yet.
        </div>
    @endforelse

</div>