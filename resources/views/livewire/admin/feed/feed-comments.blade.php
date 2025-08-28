<div class="space-y-2">
    <!-- Input for new comment -->
    <form wire:submit.prevent="addComment" class="flex gap-2">
        <input type="text" wire:model.defer="comment"
               placeholder="Add a comment..."
               class="flex-1 rounded border p-2 text-sm" />
        <button type="submit" class="px-3 py-1 bg-blue-600 text-white rounded text-sm">Comment</button>
    </form>

    <!-- Display comments -->
    <div class="space-y-1 mt-2 text-sm text-gray-700 dark:text-gray-300">
        @foreach($comments as $c)
            <div class="flex items-start gap-2">
                <div class="font-semibold">{{ $c->user->name }}:</div>
                <div>{{ $c->comment }}</div>
            </div>
        @endforeach
    </div>
</div>
