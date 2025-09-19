<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen">
        <flux:sidebar sticky stashable class="border-e border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
            <flux:sidebar.toggle class="lg:hidden" icon="x-mark" />



            <a href="{{ route('landing-page') }}" class="me-5 flex items-center space-x-2 rtl:space-x-reverse" >
                <x-app-logo />
            </a>
          
            <flux:navlist variant="outline">

                <flux:navlist.item icon="home" :href="route('landing-page')" :current="request()->routeIs('landing-page')" >
                            {{ __('Home') }}
                        </flux:navlist.item>
                @if (in_array(auth()->user()->role, ['admin', 'superadmin']))
                    <flux:navlist.item icon="rectangle-group" :href="route('dashboard')" :current="request()->routeIs('dashboard')" wire:navigate>
                            {{ __('Dashboard') }}
                        </flux:navlist.item>
                @endif
                {{-- ADMIN / SUPERADMIN --}}
                @if (in_array(auth()->user()->role, ['admin', 'superadmin']))
                    <flux:navlist.group :heading="__('Manage')" class="grid">
                        
                        <flux:navlist.item icon="users" :href="route('admin.user.manage-users')" :current="request()->routeIs('admin.user.manage-users')" wire:navigate>
                            {{ __('Manage Users') }}
                        </flux:navlist.item>

                        <flux:navlist.item icon="folder-git-2" :href="route('admin.org.manage-org')" :current="request()->routeIs('admin.org.manage-org')" wire:navigate>
                            {{ __('Manage Org') }}
                        </flux:navlist.item>

                       
                    </flux:navlist.group>
                @endif



                @if (in_array(auth()->user()->role, ['org', 'admin', 'superadmin']))
                    <flux:navlist.group  class="grid">
                        {{-- <flux:navlist.item icon="identification" :href="route('org.profile')" :current="request()->routeIs('org.profile')" wire:navigate>
                            {{ __('Org Profile') }}
                        </flux:navlist.item> --}}

                        <flux:navlist.item icon="chat-bubble-left-right" :href="route('admin.chat.manage-chat')" :current="request()->routeIs('admin.chat.manage-chat')" wire:navigate>
                            {{ __('Manage Chat') }}
                        </flux:navlist.item>

                        <flux:navlist.item icon="rss" :href="route('feed')" :current="request()->routeIs('feed')" wire:navigate>
                            {{ __('Manage Feed') }}
                        </flux:navlist.item>

                        <flux:navlist.item icon="inbox-arrow-down" :href="route('voting')" :current="request()->routeIs('voting')" wire:navigate>
                            {{ __('Manage Voting') }}
                        </flux:navlist.item>

                        <flux:navlist.item icon="megaphone" :href="route('advertisement')" :current="request()->routeIs('advertisement')" wire:navigate>
                            {{ __('Manage Advertisement') }}
                        </flux:navlist.item>

                     
                    </flux:navlist.group>
                @endif

                {{-- @if (auth()->user()->isOrg())
                   <flux:navlist.item icon="users" :href="route('org.follow-request')" :current="request()->routeIs('org.follow-request')" wire:navigate>
                            {{ __('Follow Request') }}
                        </flux:navlist.item>
                @endif --}}

                @if (auth()->user()->isOrg())

                   <flux:navlist.item icon="users" 
                        :href=" route('org.profile', ['orgId' => auth()->id()]) " 
                        :current="request()->routeIs('org.profile') && request()->route('orgId') == auth()->id()"
                        wire:navigate
                        >
                        {{ __('Profile') }}
                    </flux:navlist.item>

                    <flux:navlist.item icon="users" :href="route('org.follow-request')" :current="request()->routeIs('org.follow-request')" wire:navigate>
                        {{ __('Follow Request') }}
                    </flux:navlist.item>
                @endif
               
                @if (auth()->user()->isUser())
                    {{-- USER --}}
                    <flux:navlist.group  class="grid">
                        <flux:navlist.item icon="megaphone" :href="route('feed')" :current="request()->routeIs('feed')" wire:navigate>{{ __('News Feed') }}</flux:navlist.item>
                        <flux:navlist.item icon="megaphone" :href="route('advertisement')" :current="request()->routeIs('advertisement')" wire:navigate>{{ __('Advertisement') }}</flux:navlist.item>
                        <flux:navlist.item icon="chat-bubble-left-right" :href="route('user.chat')" :current="request()->routeIs('user.chat')" wire:navigate>{{ __('Chat') }}</flux:navlist.item>
                        <flux:navlist.item icon="check-circle" :href="route('voting')" :current="request()->routeIs('voting')" wire:navigate>{{ __('Voting') }}</flux:navlist.item>

                    </flux:navlist.group>

                    {{-- @livewire('sidebar-group-chats') --}}
                @endif


                <flux:navlist.item 
                
                
                icon="users" :href="route('inbox')" :current="request()->routeIs('inbox')" wire:navigate>
                            <livewire:sidebar-inbox/>
                        </flux:navlist.item>

     
                    
                    

                        {{-- MEMBERS --}}
                        {{-- <flux:navlist.item icon="users" :href="route('members')" :current="request()->routeIs('members')" wire:navigate>
                            {{ __('Members') }}
                        </flux:navlist.item> --}}
                    {{-- </flux:navlist.group> --}}
            </flux:navlist>

            <flux:spacer />

            <flux:navlist variant="outline">
                {{-- <flux:navlist.item icon="folder-git-2" href="https://github.com/laravel/livewire-starter-kit" target="_blank">
                {{ __('Repository') }}
                </flux:navlist.item>

                <flux:navlist.item icon="book-open-text" href="https://laravel.com/docs/starter-kits#livewire" target="_blank">
                {{ __('Documentation') }}
                </flux:navlist.item> --}}
            </flux:navlist>

            <!-- Desktop User Menu -->
            <flux:dropdown class="hidden lg:block" position="bottom" align="start">
                @if (auth()->user()->profile_image)
                <flux:profile
                circle
                    :name="auth()->user()->name"
                    {{-- avatar="{{ asset('storage/' . auth()->user()->profile_image) }}" --}}
                    avatar="{{ Storage::disk('digitalocean')->url(auth()->user()->profile_image) }}"
                    icon:trailing="chevrons-up-down"
                    class="w-8 h-8 rounded-full overflow-hidden object-cover"
                />
                @else
                    <flux:profile
                    circle
                        :name="auth()->user()->name"
                        :initials="auth()->user()->initials()"
                        icon:trailing="chevrons-up-down"
                        class="w-8 h-8 rounded-full"
                    />
                @endif

                


                <flux:menu class="w-[220px]">
                    <flux:menu.radio.group>
                        <div class="p-0 text-sm font-normal">
                            <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                                <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                    <span
                                        class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white"
                                    >
                                        @if(auth()->user()->profile_image)
                                        {{-- <img src="{{asset('storage/' . auth()->user()->profile_image)}}" alt=""> --}}
                                        <img src="{{ Storage::disk('digitalocean')->url(auth()->user()->profile_image) }}" alt="">
                                        
                                        @else
                                        {{ auth()->user()->initials() }}
                                        @endif
                                    </span>
                                </span>

                                <div class="grid flex-1 text-start text-sm leading-tight">
                                    <span class="truncate font-semibold">{{ auth()->user()->name }}</span>
                                    <span class="truncate text-xs">{{ auth()->user()->email }}</span>
                                </div>
                            </div>
                        </div>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <flux:menu.radio.group>
                        <flux:menu.item :href="route('settings.profile')" icon="cog" wire:navigate>{{ __('Settings') }}</flux:menu.item>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full">
                            {{ __('Log Out') }}
                        </flux:menu.item>
                    </form>
                </flux:menu>
            </flux:dropdown>
        </flux:sidebar>

        <!-- Mobile User Menu -->
        <flux:header class="lg:hidden sticky top-0 
           bg-white/60 dark:bg-black/60 
           backdrop-blur-md 
           z-50 shadow-sm">
            <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />

            <flux:spacer />

            <flux:dropdown position="top" align="end">
                @if(auth()->user()->profile_image)
                <flux:profile
                    {{-- avatar="{{asset('storage/' . auth()->user()->profile_image)}}" --}}
                    avatar="{{ Storage::disk('digitalocean')->url(auth()->user()->profile_image) }}"
                    icon-trailing="chevron-down"
                />
                @else
                <flux:profile
                    :initials="auth()->user()->initials()"
                    icon-trailing="chevron-down"
                />
                @endif
                

                <flux:menu>
                    <flux:menu.radio.group>
                        <div class="p-0 text-sm font-normal">
                            <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                                <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                    <span
                                        class="flex h-full w-full items-center justify-center rounded bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white"
                                    >
                                        @if(auth()->user()->profile_image)
                                        {{-- <img src="{{asset('storage/' . auth()->user()->profile_image)}}" alt=""> --}}
                                        <img src="{{ Storage::disk('digitalocean')->url(auth()->user()->profile_image) }}" alt="">
                                        @else
                                        {{ auth()->user()->initials() }}
                                        @endif
                                    </span>
                                </span>

                                <div class="grid flex-1 text-start text-sm leading-tight">
                                    <span class="truncate font-semibold">{{ auth()->user()->name }}</span>
                                    <span class="truncate text-xs">{{ auth()->user()->email }}</span>
                                </div>
                            </div>
                        </div>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <flux:menu.radio.group>
                        <flux:menu.item :href="route('settings.profile')" icon="cog" wire:navigate>{{ __('Settings') }}</flux:menu.item>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full">
                            {{ __('Log Out') }}
                        </flux:menu.item>
                    </form>
                </flux:menu>
            </flux:dropdown>
            
        
            @if (request()->routeIs('user.chat.*', 'inbox', 'feed', 'advertisement', 'voting', 'admin.chat.manage-chat'))
                <flux:modal.trigger name="mobile-right-sidebar">
                    <flux:button icon="bars-3-bottom-left" variant="ghost"/>
                </flux:modal.trigger>
            @endif

   
        </flux:header>

        {{ $slot }}

        @fluxScripts
    </body>
</html>
