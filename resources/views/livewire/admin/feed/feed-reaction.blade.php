<div class="flex items-center gap-1 cursor-pointer" wire:click="toggleHeart">
    

    @if($userReacted)
        <flux:icon.heart variant="solid" color="red"/>
    @else
        <flux:icon.heart  />
    @endif

    <span>{{ $count }}</span>
</div>
