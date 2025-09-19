<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use Livewire\Volt\Component;
use Masmerise\Toaster\Toaster;


new class extends Component {

    public string $name = '', $email = '', $username = '', $mobile_number = '', $academic_program = '', $role = '';
    public string $current_password = '', $new_password = '', $new_password_confirmation = '';

    /**
     * Mount the component.
     */
    public function mount(): void
    {
        $user = Auth::user();

        $this->name = $user->name;
        $this->email = $user->email;
        $this->username = $user->username ?? 'NO USERNAME';
        $this->role = $user->role ?? 'STUDENT BA?';
    }

    /**
     * Update the profile information for the currently authenticated user.
     */
 public function updateProfileInformation(): void
    {
        $user = Auth::user();

        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique(User::class)->ignore($user->id)],
        ]);

        $user->fill($validated);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        $this->dispatch('profile-updated');
        Toaster::success('Profile updated successfully.');
    }

    /**
     * Send an email verification notification to the current user.
     */
    public function resendVerificationNotification(): void
    {
        $user = Auth::user();

        if ($user->hasVerifiedEmail()) {
            $this->redirectIntended(default: route('dashboard', absolute: false));

            return;
        }

        $user->sendEmailVerificationNotification();

        Session::flash('status', 'verification-link-sent');
    }
}; ?>

<section class="w-full">
    @include('partials.settings-heading')

    <x-settings.layout :heading="__('Profile')" :subheading="__('Update your profile information.')">

            
        <form wire:submit="updateProfileInformation" class="space-y-6">
            <div class="grid grid-cols-1  gap-6">
                <div class="grid grid-cols-2 md:grid-cols-2 gap-2">
                        {{-- Username --}}
                        <flux:input wire:model.defer="username" :label="__('Username')" type="text" readonly />
                        {{$this->username}}
                        {{-- Group --}}
                        <flux:input wire:model.defer="role" :label="__('Role')" type="text" readonly />
                </div>



                {{-- Complete Name --}}
                <flux:input wire:model.defer="name" :label="__('Complete Name')" type="text"  />

                {{-- Email --}}
                <flux:input wire:model.defer="email" :label="__('Email')" type="email" readonly />



                
            </div>

            <div class="flex items-center gap-4">
                <flux:button type="submit" variant="primary">{{ __('Save Changes') }}</flux:button>
                <flux:button variant="ghost">{{ __('Cancel') }}</flux:button>
                <x-action-message class="me-3" on="profile-updated">
                    {{ __('Saved.') }}
                </x-action-message>

            </div>
        </form>
    

        <livewire:settings.delete-user-form />
    </x-settings.layout>
</section>

