{{-- VIEW USER --}}
<flux:modal name="view-user" class="md:w-[40rem]">
    @if ($showUser)
        <div class="space-y-6">
            <flux:heading size="lg">User Details</flux:heading>
            <flux:text class="mt-2">This is a read-only view of the user profile.</flux:text>

            <div class="grid grid-cols-3 gap-4">
                <div class="flex justify-center">
                    <flux:avatar
                        circle
                        class="size-40"
                        src="{{ $showUser['profile'] ?? 'https://i.pravatar.cc/200?u=' . $showUser['id'] }}"
                    />
                </div>

                <div class="col-span-2 space-y-3">
                    <flux:input label="Name" value="{{ $showUser['name'] ?? '' }}" readonly />
                    <flux:input label="Email" value="{{ $showUser['email'] ?? '' }}" readonly />
                    <flux:input label="Organization" value="{{ $showUser['organization'] ?? '' }}" readonly />
                    <flux:input label="Role" value="{{ ucfirst($showUser['role'] ?? 'user') }}" readonly />
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