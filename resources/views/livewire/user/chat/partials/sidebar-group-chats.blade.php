<div class="w-full space-y-4 p-2 h-auto">
                <div class="flex justify-between items-center">
                    <h2 class="font-semibold mb-3 flex gap-2 items-center">
                        Group Chats <flux:badge size="sm" color="{{$groups->count() == 4 ? 'red' : 'lime'}}">{{ $groups->count()}} / 4</flux:badge>
                        {{-- GROUP LIMIT REACHED --}}
                        
                    </h2>
                </div>

                @forelse ($groups as $group)
                    <a href="/user/chat/{{ $group->group_code }}" class="flex cursor-pointer rounded-lg py-2
          {{ request()->is('user/chat/' . $group->group_code) 
              ? 'bg-gray-200 dark:bg-gray-800' 
              : 'hover:bg-gray-100 dark:hover:bg-gray-800' }}">
                    <div class="flex gap-4 px-2 items-center">
                @if ($group->group_image)
                                <flux:avatar
                                    circle
                                    src="{{ asset('storage/' . $group->group_image) }}"
                                    
                                />
                            @else
                                <flux:avatar
                                    circle
                                    name="{{$group->name}}"
                                    
                                />
                            @endif
                <div>
                    <div>{{ $group->name }}</div>
                    <div class="text-xs text-zinc-500 dark:text-zinc-400 truncate">
                        {{ $group->description ?: 'No Description' }}
                    </div>
                </div>
            </div>
                    </a>
                @empty
                    <div class="text-sm text-zinc-500 italic px-2">
                You're not part of any groups yet.
            </div>
                @endforelse



            </div>



