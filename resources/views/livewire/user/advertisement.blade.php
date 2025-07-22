<div>
          <div class="relative mb-6 w-full ">

        
                    <flux:heading size="xl" level="1">{{ __('Advertisement') }}</flux:heading>
                    <flux:subheading size="lg" class="mb-6">{{ __('Manage your profile and account settings') }}</flux:subheading>
               

            

                  <flux:separator variant="subtle" class="col-span-2"/>
       
            </div>

                <div class="grid grid-cols-1 md:grid-cols-4 lg:grid-cols-4 gap-6 h-full">

                  <div class="w-full col-span-3 ">
                      <div class="flex justify-between items-center">          
                        <h2 class="font-bold text-xl">Latest Advertisement</h2>

                      <flux:dropdown class="flex items-center">
    <flux:button icon:trailing="chevron-down" size="sm">
        {{ $categoryFilter ? ucfirst($categoryFilter) : 'All Categories' }}
    </flux:button>

    <span class="text-xs flex gap-2 p-2">
        <flux:icon.bars-3-bottom-left class="size-4" />
        {{ count($this->filteredAdvertisements) }} Active Advertisement
    </span>

      <flux:menu>
          <flux:menu.item wire:click="$set('categoryFilter', null)">
              All Categories
          </flux:menu.item>
          <flux:menu.item wire:click="$set('categoryFilter', 'internship')">
              Internship
          </flux:menu.item>
          <flux:menu.item wire:click="$set('categoryFilter', 'event')">
              Event
          </flux:menu.item>
          <flux:menu.item wire:click="$set('categoryFilter', 'job')">
              Job
          </flux:menu.item>
          <flux:menu.item wire:click="$set('categoryFilter', 'scholarship')">
              Scholarship
          </flux:menu.item>
      </flux:menu>
</flux:dropdown>

                    </div>
          
                    {{-- ADVERTISEMENTS --}}

                    <div class="mt-10 space-y-6">

    @forelse ($this->filteredAdvertisements as $ad)
        <div class="p-4 border rounded-md bg-white dark:bg-gray-800 shadow-sm">
            <div class="font-semibold text-lg text-gray-900 dark:text-white">
                {{ $ad->title }}
            </div>
            <div class="text-sm text-gray-600 dark:text-gray-300">
                {{ $ad->category }} • {{ $ad->organization }} • {{ $ad->location }}
            </div>
            <div class="mt-1 text-gray-700 dark:text-gray-300 text-sm">
                {{ $ad->description }}
            </div>
            <div class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                Event: {{ $ad->event_date ?? 'N/A' }} at {{ $ad->time ?? 'N/A' }} • Deadline: {{ $ad->deadline ?? 'N/A' }}
            </div>
            <div class="mt-1 text-xs text-gray-400 dark:text-gray-500 italic">
                Tags: {{ $ad->tags }}
            </div>
        </div>
    @empty
        <div class="text-gray-500 text-sm dark:text-gray-400">
            No advertisements posted yet.
        </div>
    @endforelse
</div>


                  </div>


                <div class="flex flex-col gap-6">
                  <div class="border w-full h-10 p-4 rounded-lg">
                    <h2>Quick Stats</h2>
                  </div>
                  <div class="border w-full h-10 p-4 rounded-lg">
                    <h2>Trending Categories</h2>
                  </div>
                  <div class="border w-full h-10 p-4 rounded-lg">
                    <h2>Upcoming Deadlines</h2>
                  </div>
                  <div class="border w-full h-10 p-4 rounded-lg">
                    <h2>Help and Support</h2>
                  </div>
                </div>




                {{-- add a modal for add advertisement it should contain title category description oraganization/person location event date time application deadline tags  --}}
<flux:modal name="add-advertisement" class="md:w-[40rem]">
    <form wire:submit.prevent="createAdvertisement">
        <div class="space-y-6">
            <flux:heading size="lg">Post an Opportunity</flux:heading>
            <flux:text class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                Fill out the details below to publish your advertisement.
            </flux:text>

            <flux:input label="Title" wire:model.defer="title" placeholder="e.g. Graphic Design Internship" />

            <flux:select label="Category" wire:model.defer="category">
                <option value="">Select a category</option>
                <option value="internship">Internship</option>
                <option value="event">Event</option>
                <option value="job">Job</option>
                <option value="scholarship">Scholarship</option>
            </flux:select>

            <flux:textarea label="Description" rows="4" wire:model.defer="description" placeholder="Provide full details..." />

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <flux:input label="Organization or Person" wire:model.defer="organization" />
                <flux:input label="Location" wire:model.defer="location" />
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-2">
                <flux:input label="Event Date" type="date" wire:model.defer="event_date" />
                <flux:input label="Time" type="time" wire:model.defer="time" />
                <flux:input label="Application Deadline" type="date" wire:model.defer="deadline" />
            </div>

            <flux:input label="Tags (comma separated)" wire:model.defer="tags" />

            {{-- Image Upload --}}
            <div class="space-y-2">
                <flux:input
                    type="file"
                    label="Image (optional)"
                    wire:model="photos"
                    multiple
                />
                @error('photos.*')
                    <p class="text-red-500 text-xs">{{ $message }}</p>
                @enderror

                @if ($photos)
                    <div class="grid grid-cols-3 gap-2 mt-2">
                        @foreach ($photos as $photo)
                            <div class="relative">
                                <img src="{{ $photo->temporaryUrl() }}" class="w-full h-32 object-cover rounded-md shadow" />
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Validation Errors --}}
            @if ($errors->any())
                <div class="text-red-500 text-sm">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Publishing Guidelines --}}
            <div class="mt-6 p-4 bg-gray-50 dark:bg-gray-800 rounded-md border border-gray-200 dark:border-gray-700">
                <flux:heading size="sm" level="3" class="mb-2">Publishing Guidelines</flux:heading>
                <ul class="list-disc list-inside text-sm text-gray-600 dark:text-gray-400 space-y-1">
                    <li>Ensure your post is respectful and accurate.</li>
                    <li>Do not include misleading or spammy content.</li>
                    <li>Include relevant deadlines and event details.</li>
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




    </div>
</div>
