<flux:modal name="add-advertisement" class="md:w-[40rem]">
    <form wire:submit.prevent="createAdvertisement">
        <div class="space-y-6">
            <flux:heading size="lg">Post an Advertisement</flux:heading>
            <flux:text class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                Fill out the details below to publish your advertisement.
            </flux:text>

            <flux:input label="Title" wire:model.defer="title" placeholder="e.g. Graphic Design Internship" />

            <flux:textarea label="Description" rows="4" wire:model.defer="description" placeholder="Provide full details..." />

            <div class="grid grid-cols-1  gap-4">
                
                        @if(auth()->user()->role === 'superadmin' || auth()->user()->role === 'admin')
                            <flux:select label="Organization" wire:model.defer="organization">
                                    <flux:select.option selected value="">All</flux:select.option>
                                @foreach ($orgs as $org)
                         
                                    <flux:select.option value="{{$org->name}}">{{$org->name}}</flux:select.option>
                                @endforeach
                            </flux:select>
                        @endif

                        @if(auth()->user()->role === 'org')
                    <div class="grid grid-cols-2 gap-4">
                        <flux:select label="Privacy" wire:model.defer="privacy" placeholder="Public / Private" description="Public - All. Private - Org members" >
                            <flux:select.option value="public">Public</flux:select.option>
                            <flux:select.option value="private">Private</flux:select.option>
                        </flux:select>
                        <flux:input type="text" label="Type" description="Example: Event, Announcement, Etc." wire:model.defer="type" placeholder="ex. Event, Announcement, etc." autocomplete="off" />
                    </div>
                    @endif

                                       {{-- Image Upload --}}
            <div class="space-y-2">
                <flux:field>
                    
                    <div class="flex justify-between">
                        <flux:label badge="Optional">Image</flux:label>
                        @if ($photos)
                            <flux:modal.trigger name="preview-temp-photos">
                                <flux:button size="sm" variant="outline">Preview Selected</flux:button>
                            </flux:modal.trigger>
                        @endif

                        <flux:modal name="preview-temp-photos" class="md:w-[30rem]">
                            <div class="space-y-4">
                                <flux:heading>Preview Selected Images</flux:heading>

                                <div class="grid grid-cols-2 gap-4">
                                    @foreach ($photos as $photo)
                                        <img src="{{ $photo->temporaryUrl() }}" class="w-full h-40 object-cover rounded shadow" />
                                    @endforeach
                                </div>
                            </div>
                        </flux:modal>

                    </div>
                  
                <input
                    type="file"
                    wire:model="photos"
                    multiple
                    class="max-w-[15rem] block border border-gray-300 rounded-md p-2 text-sm"
                />
                @error('photos.*')
                    <p class="text-red-500 text-xs">{{ $message }}</p>
                @enderror
                </flux:field>
                

               
                </div>
            
            </div>

 

            {{-- Publishing Guidelines --}}
            <div class="mt-6 p-4 bg-gray-50 dark:bg-gray-800 rounded-md border border-gray-200 dark:border-gray-700">
                <flux:heading size="sm" level="3" class="mb-2">Publishing Guidelines</flux:heading>
                <ul class="list-disc list-inside text-sm text-gray-600 dark:text-gray-400 space-y-1">
                    <li>Ensure your post is respectful and accurate.</li>
                    <li>Do not include misleading or spammy content.</li>
                    <li>Use real organization or person names.</li>
                    <li>Posts are reviewed and may be removed if inappropriate.</li>
                </ul>
            </div>

            {{-- Submit Button --}}
            <div class="flex justify-end pt-4">
                <flux:button type="submit" variant="primary">
                    Publish
                </flux:button>
            </div>
        </div>
    </form>
</flux:modal>