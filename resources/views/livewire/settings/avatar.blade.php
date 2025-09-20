<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;
use Livewire\Volt\Component;

use Livewire\WithFileUploads;

new class extends Component {
    use WithFileUploads;

    public $profileImage, $avatar;

    
    

    public function updateUserAvatar()
    {
        $user = Auth::user();

        $this->validate([
            'avatar' => ['required', 'image', 'max:1024'],
        ]);

        // Delete old avatar if it exists
        if ($user->profile_image && Storage::disk('digitalocean')->exists($user->profile_image)) {
            Storage::disk('digitalocean')->delete($user->profile_image);
        }

        // Store new avatar
        $path = $this->avatar->storePublicly('profile-images', 'digitalocean');
        $user->profile_image = $path;
        $user->save();

        $this->reset('avatar');
        $this->dispatch('avatar-updated');
        Toaster::success('Avatar updated.');
    }

}; ?>  

<section class="w-full">
    @include('partials.settings-heading')

    <x-settings.layout :heading="__('Update Avatar')" :subheading="__('Ensure your account is using a long, random password to stay secure')">
        
        <form wire:submit="updateUserAvatar" class="space-y-4">
            
            {{-- 2x2 Preview --}}
            <div class="size-40 rounded overflow-hidden border border-gray-300 dark:border-gray-700">
                @if ($avatar)
                    <img src="{{ $avatar->temporaryUrl() }}" class="object-cover w-full h-full" />
                @elseif (auth()->user()->profile_image)
                    <img 
                    {{-- src="{{ asset('storage/' . auth()->user()->profile_image) }}" --}}
                    src="{{ Storage::disk('digitalocean')->url(auth()->user()->profile_image) }}"
                     class="object-cover w-full h-full" />
                @else
                    <div class="flex items-center justify-center w-full h-full text-gray-400 text-sm">
                        No avatar
                    </div>
                @endif

            </div>
            {{-- Select Image --}}
            <flux:input wire:model="avatar" type="file" :label="__('Select Image')" accept="image/*" 
            />


            <div class="flex items-center gap-4">
                <flux:button 
                    type="submit" 
                    variant="primary"
                    wire:loading.attr="disabled" 
                    wire:target="avatar"
                    >
                    {{ __('Save Changes') }}
                </flux:button>
                <flux:button variant="ghost">{{ __('Cancel') }}</flux:button>
                <x-action-message class="me-3" on="avatar-updated">
                    {{ __('Saved.') }}
                </x-action-message>

            </div>
        </form>
            
    </x-settings.layout>
</section>
