<div>
          <div class="relative mb-6 w-full grid grid-cols-2 ">

           <div>
             <flux:heading size="xl" level="1">{{ __('Advertisement') }}</flux:heading>
            <flux:subheading size="lg" class="mb-6">{{ __('Manage your profile and account settings') }}</flux:subheading>
           </div>

           <div class="flex justify-end items-center">
                <flux:modal.trigger name="add-advertisement">
                  <flux:button icon:leading="plus" size="sm" class="mb-2">
                    {{ __('Create Advertisement') }}
                  </flux:button>
                  </flux:modal.trigger>
           </div>


           
            <flux:separator variant="subtle" class="col-span-2"/>
       
          </div>

                <div class="grid grid-cols-1 md:grid-cols-4 lg:grid-cols-4 gap-6 h-full">

                  <div class="w-full col-span-3 ">
                    <div class="flex justify-between items-center">          
                      <h2 class="font-bold text-xl">Latest Opportunities</h2>

                    <flux:dropdown class="flex items-center">
                        <flux:button icon:trailing="chevron-down" size="sm">All Categories</flux:button>
                        <span class="text-xs flex gap-2 p-2"><flux:icon.bars-3-bottom-left class="size-4 "/>6 Active Opportunity</span>
                      
                      <flux:menu>
                          <flux:menu.checkbox wire:model="read" checked>Read</flux:menu.checkbox>
                          <flux:menu.checkbox wire:model="write" checked>Write</flux:menu.checkbox>
                          <flux:menu.checkbox wire:model="delete">Delete</flux:menu.checkbox>
                      </flux:menu>

                    </flux:dropdown></div>
          
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
                      <div>
                          <flux:heading size="lg">Post an Opportunity</flux:heading>
                          <flux:text class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                              Fill out the details below to publish your advertisement.
                          </flux:text>
                      </div>

                      {{-- Title --}}
                      <flux:input label="Title" placeholder="e.g. Graphic Design Internship" wire:model.defer="title" />

                      {{-- Category --}}
                      <flux:select label="Category" wire:model.defer="category">
                          <flux:select.option value="">Select a category</flux:select.option>
                          <flux:select.option value="internship">Internship</flux:select.option>
                          <flux:select.option value="event">Event</flux:select.option>
                          <flux:select.option value="job">Job</flux:select.option>
                          <flux:select.option value="scholarship">Scholarship</flux:select.option>
                      </flux:select>

                      {{-- Description --}}
                      <flux:textarea label="Description" rows="4" placeholder="Provide full details..." wire:model.defer="description" />

                      {{-- Organization and Location (Same Row) --}}
                      <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                          <flux:input label="Organization or Person" placeholder="e.g. ABC Foundation" wire:model.defer="organization" />
                          <flux:input label="Location" placeholder="e.g. Manila, Philippines or Online" wire:model.defer="location" />
                      </div>

                      {{-- Event Date, Time, Application Deadline (Same Row) --}}
                      <div class="grid grid-cols-1 sm:grid-cols-3 gap-2">
                          <flux:input label="Event Date" type="date" wire:model.defer="event_date" />
                          <flux:input label="Time" type="time" wire:model.defer="time" />
                          <flux:input label="Application Deadline" type="date" wire:model.defer="deadline" />
                      </div>

                      {{-- Tags --}}
                      <flux:input label="Tags (comma separated)" placeholder="e.g. design, marketing, student" wire:model.defer="tags" />

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
