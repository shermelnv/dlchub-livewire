<flux:modal name="post-info" class="max-w-4xl w-auto">
    @if ($selectedPost)
        <div class="flex items-center justify-center bg-black rounded-lg max-h-[80vh] mt-8">
            <img 
                {{-- src="{{ asset('storage/' . $selectedPost->photo_url) }}"  --}}
                src="{{ Storage::disk('digitalocean')->url($selectedPost->photo_url) }}"
                alt="{{ $selectedPost->title }}" 
                class="object-contain max-h-[80vh] w-full rounded-lg">
        </div>
    @endif
</flux:modal>
