
<flux:navlist.group expandable heading="Group Chats" class="grid" >
    
    <div class="grid gap-2">
        <flux:modal.trigger name="create-group" >
            <flux:navlist.item icon="plus" badge="{{ $groups->count() }}/4" badge-color="{{$groups->count() == 4 ? 'red' : 'lime'}}">
                Create / Join 
            </flux:navlist.item>
        </flux:modal.trigger>

        @forelse ($groups as $group)

        <flux:navlist.item
            href="/user/chat/{{ $group->group_code }}"
            class="flex cursor-pointer"
            :current=" request()->is('user/chat/' . $group->group_code)"
        >
            <div class="flex gap-4 items-center">
                <flux:avatar circle src="https://unavatar.io/x/calebporzio" size="sm" />
                <div>
                    <div>{{ $group->name }}</div>
                    <div class="text-xs text-zinc-500 dark:text-zinc-400 truncate">
                        {{ $group->description ?: 'No Description' }}
                    </div>
                </div>
            </div>
        </flux:navlist.item>


        @empty
            <div class="text-sm text-zinc-500 italic px-4">
                You're not part of any groups yet.
            </div>
        @endforelse
    </div>
    @include('livewire.user.chat.partials.create-group')
</flux:navlist.group>
