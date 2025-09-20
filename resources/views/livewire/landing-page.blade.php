<div class="flex flex-col  overflow-x-hidden min-h-screen">

    {{-- HEADER --}}
    <section class="min-h-screen flex flex-col items-center justify-center text-center space-y-6 px-4" data-aos="fade-up">
        <h1 class="max-w-2xl text-3xl md:text-5xl font-bold">Introducing PLC HUB Students Collaboration</h1>
        <flux:text class="max-w-2xl text-base md:text-lg text-gray-600">
       Connect. Collaborate. Stay updated. PLC HUB brings students, organizations, and faculty together in a single hub — from events and announcements to voting and real-time chats.
        </flux:text>
    </section>

    {{-- GROUP CHAT --}}
    <section class="min-h-screen flex flex-col lg:grid lg:grid-cols-2 items-center bg-red-950">
        {{-- PHOTO --}}
        <img 
            src="{{ asset('images/chatting.png') }}" 
            alt="Group Chat" 
            class="w-full max-h-96 lg:max-h-[500px] rounded-lg object-contain mx-auto my-6 lg:my-0"
            data-aos="zoom-in"
        />

        <div class="space-y-6 text-white px-6 md:px-10 text-center lg:text-left" data-aos="fade-left">
            <h1 class="text-2xl md:text-4xl font-bold">Make Your Group Chats More Fun</h1>
            <flux:text class="max-w-lg text-base md:text-lg text-gray-200 text-justify mx-auto lg:mx-0">
                Team up in real time! Chat with classmates, share ideas, and keep everything on track—right from your dashboard.
            </flux:text>
            <div class="grid grid-cols-2 lg:flex flex-col gap-4">
                <ul class="space-y-3">
                    <li class="flex items-center gap-2 text-xs lg:text-lg justify-center text-start lg:justify-start" data-aos="fade-up" data-aos-delay="100">
                        <flux:icon.check class="size-4"/> Real-time messaging
                    </li>
                    <li class="flex items-center gap-2 text-xs lg:text-lg justify-center text-start lg:justify-start" data-aos="fade-up" data-aos-delay="200">
                        <flux:icon.check class="size-4"/> Create study groups
                    </li>
                    <li class="flex items-center gap-2 text-xs lg:text-lg justify-center text-start lg:justify-start" data-aos="fade-up" data-aos-delay="300">
                        <flux:icon.check class="size-4"/> Integrated calendar and event planning
                    </li>
                </ul>
                <div class="flex justify-between items-center lg:block">
                    <flux:button href="{{route('user.chat')}}" icon="chat-bubble-oval-left" variant="primary" color="rose" data-aos="fade-up" data-aos-delay="400">Start Chatting</flux:button>
                </div>
            </div>
            
        </div>
    </section>

    {{-- VOTE OFFICER --}}
    <section class="min-h-screen flex flex-col md:flex-row items-center bg-gray-900">
        <div class="space-y-6 text-white px-6 md:px-10 text-center md:text-left order-2 md:order-1" data-aos="fade-right">
            <h1 class="text-2xl md:text-4xl font-bold">Student Voting Made Simple</h1>
            <flux:text class="max-w-lg text-base md:text-lg text-gray-300 text-justify mx-auto md:mx-0">
                Make your voice count! Vote for student leaders, track ongoing elections, and participate in campus decision-making with ease.
            </flux:text>
            <flux:button href="{{route('voting')}}" icon="check-circle" variant="primary" color="rose" class="mt-6" data-aos="zoom-in-up">Start Voting</flux:button>
        </div>
        <img 
            src="{{ asset('images/voting.png') }}" 
            alt="Voting" 
            class="w-full max-h-96 md:max-h-[500px] rounded-lg object-contain mx-auto my-6 md:my-0 order-1 md:order-2"
            data-aos="zoom-in"
        />
    </section>

    {{-- DUMMY HERE --}}




    {{-- NEWS FEED --}}
    <section class="max-h-screen flex flex-col items-center bg-gray-100 dark:bg-gray-900 pb-10">
         <h2 class="w-full py-2 bg-red-900 dark:bg-red-700 text-center text-white font-semibold" data-aos="fade-down">
        Latest News & Updates
        </h2>

        <div class="w-full grid grid-cols-2 md:grid-cols-3 gap-6 p-6">
           @forelse($latestFeeds as $i => $feed)
           <a href="{{route('feed')}}" wire:navigate >
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 space-y-3" 
                 data-aos="fade-up" 
                 data-aos-delay="{{ $i * 200 }}">
                 
                @if($feed->photo_url)
                    <img 
                    {{-- src="{{ asset('storage/'. $feed->photo_url) }}"  --}}
                    src="{{ Storage::disk('digitalocean')->url($feed->photo_url) }}"
                         alt="News Image" 
                         class="w-full h-48 object-cover rounded-md bg-gray-200 dark:bg-gray-700"/>
                @else
                    <div class="w-full h-48 rounded-md bg-gray-200 dark:bg-gray-700 flex items-center justify-center text-gray-400">
                        No Image
                    </div>
                @endif

                <p class="text-gray-700 dark:text-gray-300 words-break line-clamp-2 h-[3rem]">
                    {{ $feed->content }}
                </p>
            </div>
        </a>
        @empty
            <p class="col-span-3 text-center text-gray-500 dark:text-gray-400">
                No news available yet.
            </p>
        @endforelse
        </div>

        @if($latestFeeds->count() > 0)
        <flux:button href="{{route('feed')}}" icon="arrow-right-circle" variant="primary" color="red" class="mt-6" data-aos="fade-up">View More News</flux:button>
        @endif
    </section>


        {{-- ADVERTISEMENT & ORGS --}}
        <section class="grid grid-cols-1 lg:grid-cols-5 gap-6 p-6 bg-gray-100 dark:bg-gray-900 items-stretch overflow-hidden">

            {{-- Advertisement --}}
            <div class="col-span-3 flex flex-col rounded-2xl shadow-lg  dark:border-gray-700 h-auto" data-aos="fade-right">
                <div class="w-full py-3 bg-red-800 dark:bg-red-900 text-center text-white font-semibold rounded-t-2xl">
                    Advertisement
                </div>

                {{-- Content grows and scrolls if needed --}}
                <div class=" p-6 grid grid-cols-2 gap-6 ">
                    
                    @forelse($latestAds as $i => $ad)
                    <a href="{{route('advertisement')}}" wire:navigate >
                        <div class="bg-gray-100 dark:bg-gray-800 rounded-lg shadow p-4 space-y-4"
                            data-aos="fade-up"
                            data-aos-delay="{{ $i * 200 }}">

                            @php
                                $firstPhoto = $ad->photos->first();
                            @endphp

                            @if ($firstPhoto)
                                <img src="{{ Storage::url($firstPhoto->photo_path) }}"
                                    class="w-full h-48 object-cover rounded-md bg-gray-200 dark:bg-gray-700"
                                    alt="Ad Image">
                            @else
                                <div class="w-full h-48 flex items-center justify-center bg-gray-200 dark:bg-gray-700 rounded-md text-gray-500">
                                    No Image Available
                                </div>
                            @endif

                                <p class="text-gray-700 dark:text-gray-300 words-break line-clamp-2 h-[3rem]">
                                    {{ $ad->description }}
                                </p>
                  
                        </div>
                    </a>
                    @empty
                        <p class="col-span-3 text-center text-gray-500 dark:text-gray-400">
                            No advertisements yet.
                        </p>
                    @endforelse
                </div>

                {{-- Button fixed at bottom --}}

                @if($latestAds->count() > 0)
                <div class="flex justify-center p-4">
                    <flux:button href="{{ route('advertisement') }}" icon="arrow-right-circle" variant="primary" color="red" data-aos="fade-up">
                        View More Ads
                    </flux:button>
                </div>
                @endif
            </div>

            {{-- Organizations --}}
            <div class="col-span-2 flex flex-col rounded-2xl shadow-lg h-auto dark:border-gray-700" data-aos="fade-left">
                <div class="w-full py-3 bg-red-800 dark:bg-red-900 text-center text-white font-semibold rounded-t-2xl">
                    Organizations
                </div>

                {{-- Content grows --}}
                <div class="flex-1 flex flex-col gap-6 p-6 text-gray-700 dark:text-gray-300 scrollbar-hover max-h-[90vh] overflow-y-auto">
                    {{-- List of Orgs --}}
                    {{-- <div class="grid gap-6"> --}}
                    @forelse($organizations as $org)
                    <a href="{{ route('org.profile', ['orgId' => $org->id]) }}" wire:navigate >
                        <div class="flex items-center gap-4 p-2 bg-gray-100 dark:bg-gray-800 rounded-lg shadow-sm hover:shadow-md transition ">
                            @if($org->profile_image)
                                <img src="{{ Storage::disk('digitalocean')->url($org->profile_image) }}"
                                    class="w-12 h-12 rounded-full object-cover bg-gray-200 dark:bg-gray-600"
                                    alt="{{ $org->name }} Logo">
                            @else
                                <div class="w-12 h-12 flex items-center justify-center rounded-full bg-red-800 text-white font-semibold">
                                    {{ strtoupper(substr($org->name, 0, 2)) }}
                                </div>
                            @endif
                            <div class="flex-1">
                                <h3 class="text-lg font-semibold">{{ $org->name }}</h3>
                            </div>
                        </div>
                        </a>
                    @empty
                        <p class="text-center text-gray-500 dark:text-gray-400">
                            No organizations available.
                        </p>
                    @endforelse
                </div>

                
            </div>
        </section>











</div>
