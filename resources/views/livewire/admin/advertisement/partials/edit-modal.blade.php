<flux:modal name="edit-advertisement" class="md:w-[40rem]">
    <form wire:submit.prevent="updateAdvertisement">
        <div class="space-y-6">
            <flux:heading size="lg">Edit Advertisement</flux:heading>
            <flux:text class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                Modify the details of your advertisement below.
            </flux:text>

            {{-- Title --}}
            <flux:input label="Title" wire:model.defer="showAd.title" placeholder="e.g. Graphic Design Internship" />

            {{-- Description --}}
            <flux:textarea label="Description" rows="4" wire:model.defer="showAd.description" placeholder="Provide full details..." />

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                {{-- Organization Select --}}
                <flux:select label="Organization" wire:model.defer="showAd.organization">
                    <flux:select.option value="">All</flux:select.option>
                    @foreach ($orgs as $org)
                        <flux:select.option value="{{ $org->name }}">{{ $org->name }}</flux:select.option>
                    @endforeach
                </flux:select>

                {{-- Image Upload --}}
                {{-- Image Upload --}}
<div class="space-y-2 col-span-2">
    <flux:field>
        <div class="flex justify-between">
            <flux:label badge="Optional">Update Images</flux:label>
            @if ($photos)
                <flux:modal.trigger name="preview-feed-photos">
                    <flux:button size="sm" variant="outline">Preview Selected</flux:button>
                </flux:modal.trigger>
            @endif
        </div>

        {{-- Preview Modal --}}
        <flux:modal name="preview-feed-photos" class="md:w-[30rem]">
            <div class="space-y-4">
                <flux:heading>Preview Selected Images</flux:heading>
                <div class="grid grid-cols-2 gap-4">
                    @foreach ($photos as $photo)
                        <img src="{{ $photo->temporaryUrl() }}" class="w-full h-40 object-cover rounded shadow" />
                    @endforeach
                </div>
            </div>
        </flux:modal>

        {{-- File Input --}}
        <input
            type="file"
            wire:model="photos"
            multiple
            accept="image/*"
            class="max-w-[15rem] block border border-gray-300 rounded-md p-2 text-sm"
        />

        @error('photos.*')
            <p class="text-red-500 text-xs">{{ $message }}</p>
        @enderror
    </flux:field>
</div>

            </div>

            {{-- Guidelines --}}
            <div class="mt-6 p-4 bg-gray-50 dark:bg-gray-800 rounded-md border border-gray-200 dark:border-gray-700">
                <flux:heading size="sm" level="3" class="mb-2">Publishing Reminders</flux:heading>
                <ul class="list-disc list-inside text-sm text-gray-600 dark:text-gray-400 space-y-1">
                    <li>Make sure your content is accurate and complete.</li>
                    <li>Do not upload misleading or inappropriate content.</li>
                    <li>Images will replace previous ones if uploaded.</li>
                </ul>
            </div>

            {{-- Save Button --}}
            <div class="flex justify-end pt-4">
                <flux:button type="submit" variant="primary">
                    Save Changes
                </flux:button>
            </div>
        </div>
    </form>
</flux:modal>
