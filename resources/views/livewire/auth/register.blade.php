<?php

    use App\Models\User;
    use Livewire\Volt\Component;
    use Livewire\WithFileUploads;
    use Livewire\Attributes\Layout;
    use Illuminate\Auth\Events\Registered;
    use App\Events\UserRegistered;
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Support\Facades\Hash;
    use Illuminate\Validation\Rules;
    use Illuminate\Support\Facades\Mail;
    use App\Mail\EmailVerification;

    new #[Layout('components.layouts.auth')] class extends Component {
        use WithFileUploads;

        public string $name = '';
        public string $email = '';
        public string $password = '';
        public string $password_confirmation = '';
        public $photo; // uploaded file
        public int $step = 1; // wizard step

        /**
         * Step 1: Validate personal details
         */
        public function checkDetails(): void
        {
            $validated = $this->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email'],
                'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
            ]);

            // PSU-specific email validation
            $email = $validated['email'];
            $localPart = strstr($email, '@', true);
            $domainPart = substr(strstr($email, '@'), 1);

            if ($domainPart !== 'pampangastateu.edu.ph' || !preg_match('/^\d{10}$/', $localPart)) {
                $this->addError('email', 'Email must be a Pampanga State University account.');
                return;
            }

            // Proceed to photo upload step
            $this->step = 2;
        }

        /**
         * Step 2: Register the user after photo upload
         */
        public function register(): void
        {
            $this->validate([
                'photo' => ['required', 'image', 'max:5120'], // max 2MB
            ]);

            // Store photo in public disk
            $photoPath = $this->photo->store('user_COR_ID', 'public');

            // Create user
            $user = User::create([
                'name' => $this->name,
                'email' => $this->email,
                'username' => strstr($this->email, '@', true),
                'password' => Hash::make($this->password),
                'status' => 'pending',
                'document' => $photoPath,
            ]);

            session(['user_name' => $user->name]);

            // Fire events
            event(new Registered($user));
            broadcast(new UserRegistered());
            Auth::login($user);
            Mail::to($user->email)->send(new EmailVerification($user));

            // Redirect
            $this->redirectIntended(route('redirectToPage', absolute: false), navigate: true);
        }
    };
?>


<div class="flex flex-col gap-6">

    

    {{-- Header --}}
    <x-auth-header :title="__('Create an account')" 
                   :description="__('Enter your details below to create your account')" />

    <x-auth-session-status class="text-center" :status="session('status')" />

        {{-- Breadcrumbs --}}
    <flux:breadcrumbs class="m-auto">

        {{-- Step 1 --}}
        <flux:breadcrumbs.item :class="$step === 1 ? 'font-bold' : 'font-normal' ">
            <div class="flex items-center gap-1">
                @if($step === 2)
                    <flux:icon.check-circle class="size-5 text-green-300" variant="solid"/>
                @endif
                Details
            </div>

        </flux:breadcrumbs.item>

        {{-- Step 2 --}}
        <flux:breadcrumbs.item :class="$step === 2 ? 'font-bold ' : 'font-normal' ">
            Upload Document
        </flux:breadcrumbs.item>
    </flux:breadcrumbs>

    
    {{-- Step 1: Details Form --}}
    @if($step === 1)
        <form wire:submit.prevent="checkDetails" class="flex flex-col gap-6">
            <flux:input wire:model="name" :label="__('Name')" type="text" required autofocus autocomplete="name" placeholder="Full name" />
            <flux:input wire:model="email" :label="__('Email address')" type="email" required autocomplete="email" placeholder="2023001234@pampangastateu.edu.ph" />
            <flux:input wire:model="password" :label="__('Password')" type="password" required autocomplete="new-password" placeholder="Password" viewable />
            <flux:input wire:model="password_confirmation" :label="__('Confirm password')" type="password" required autocomplete="new-password" placeholder="Confirm password" viewable />
            

            <flux:button type="submit" variant="primary" class="w-full">
                {{ __('Next Step') }}
            </flux:button>
        </form>
    @endif

    {{-- Step 2: Photo Upload --}}
    @if($step === 2)
    <form wire:submit.prevent="register" class="space-y-6 max-w-xs">
        <flux:input 
            type="file" 
            wire:model="photo" 
            :label="__('Upload your COR or ID')" 
            required 
            size="sm"
            accept="image/*" 
        />

        {{-- Loader while uploading --}}
        {{-- <div wire:loading wire:target="photo" class="flex justify-center text-sm text-gray-600 dark:text-gray-300" >
            <div class="text-center">
                Loading...
            </div>
                
        </div> --}}

        <div 
    wire:loading 
    wire:target="photo" 
    class="w-full flex justify-center items-center py-4"
>
    <div class="flex flex-col items-center space-y-2 text-gray-600 dark:text-gray-300">
        {{-- Spinner --}}
        <div class="w-6 h-6 border-2 border-primary-500 border-t-transparent rounded-full animate-spin"></div>
        
        {{-- Text --}}
        <span class="text-sm">Loading...</span>
    </div>
</div>
  @error('photo')
        <p class="text-sm text-red-600 dark:text-red-400 mt-1">
            {{ $message }}
        </p>
    @enderror


        @if($photo)
            {{-- <flux:modal.trigger class="flex justify-center" name="review_image">
                <flux:button align="center">review image</flux:button>
            </flux:modal.trigger> --}}

            <flux:modal name="review_image" class="max-h-[calc(100vh-10rem)] w-full m-2">
                <div class="space-y-2 pb-2">
                    <flux:heading size="lg">Review Image</flux:heading>

                    <img 
                        src="{{ $photo->temporaryUrl() }}" 
                        class="max-h-[60vh] w-auto mx-auto object-contain rounded-xl shadow" 
                    />

                </div>
            </flux:modal>
                <flux:modal.trigger class="flex justify-center" name="review_image">
                    
                         <img 
                            src="{{ $photo->temporaryUrl() }}" 
                            class="w-40 h-40 object-cover mx-auto rounded-xl shadow" 
                        />
            </flux:modal.trigger>
        @endif

        <flux:button type="submit" variant="primary" class="w-full" wire:loading.attr="disabled"
        wire:target="register">
            {{ __('Submit Registration') }}
        </flux:button>
    </form>
@endif


    {{-- Login link --}}
    <div class="text-center text-sm text-zinc-600 dark:text-zinc-400">
        <span>{{ __('Already have an account?') }}</span>
        <flux:link :href="route('login')" wire:navigate>{{ __('Log in') }}</flux:link>
    </div>
</div>


