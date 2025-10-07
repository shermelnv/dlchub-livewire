{{-- VIEW USER --}}
<flux:modal name="view-user" class="md:w-[40rem]">
    @if ($showUser)
        <div class="space-y-6">
            <flux:heading size="lg">User Details</flux:heading>
            <flux:text class="mt-2">This is a read-only view of the user profile.</flux:text>

            <div class="grid grid-cols-3 gap-4">
                <div class="flex justify-center">
                    @if (!empty($showUser['profile_image']))
                    <flux:avatar
                        circle
                        class="size-40 object-cover overflow-hidden"
                        {{-- src="{{ asset('storage/' . $showUser['profile_image']) }}" --}}
                        src="{{ Storage::disk('digitalocean')->url($showUser['profile_image']) }}"
                    />
                    @else
                    <flux:avatar
                        circle
                        class="size-40 object-cover text-3xl"
                        name="{{ $showUser['name'] }}"
                    />

                    @endif
                </div>

                <div class="col-span-2 space-y-3">
                    <flux:input label="Name" value="{{ $showUser['name'] ?? '' }}" readonly />
                    <flux:input label="Email" value="{{ $showUser['email'] ?? '' }}" readonly />
                    
                    <flux:input label="Role" value="{{ ucfirst($showUser['role'] ?? 'user') }}" readonly />

                    <div class="flex justify-between">
                        <p class="font-semibold">COR</p>
                        <flux:modal.trigger name="COR_preview">
                            <flux:button size="sm">View COR</flux:button>
                        </flux:modal.trigger>
                    </div>
                </div>
            </div>

            <div class="flex">
                <flux:spacer />
                <flux:modal.close>
                    <flux:button variant="ghost">Close</flux:button>
                </flux:modal.close>
            </div>
        </div>
    @else
        <div class="p-6 text-center">
            <flux:icon.loading class="w-5 h-5 animate-spin text-primary mx-auto" />
            <flux:text class="mt-2">Loading user data...</flux:text>
        </div>
    @endif
</flux:modal>

<flux:modal name="COR_preview">
    <flux:heading>COR PREVIEW</flux:heading>

    @if (!empty($showUser['document']))
        <div class="p-4">
            <img 
            {{-- src="{{ asset('storage/' . $showUser['document']) }}"  --}}
            src="{{ Storage::disk('digitalocean')->url($showUser['document']) }}"
                 alt="User COR or ID" 
                 class="max-w-full h-auto mx-auto rounded-lg border border-gray-300" />
        </div>
    @else
        <div class="p-6 text-center">
            <flux:text>No COR/ID document uploaded.</flux:text>
        </div>
    @endif
</flux:modal>
