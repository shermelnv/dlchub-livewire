<div class="flex flex-col  overflow-x-hidden">

    {{-- HEADER --}}
    <section class="min-h-screen flex flex-col items-center justify-center text-center space-y-6 px-4" data-aos="fade-up">
        <h1 class="max-w-2xl text-5xl font-bold">Introducing PLC HUB Students Collaboration</h1>
        <flux:text class="max-w-2xl text-lg text-gray-600">
        A one-stop hub that connects students, organizations, and faculty. Stay updated with announcements, join events, 
        participate in voting rooms, and collaborate through real-time chats — all in one place.
        </flux:text>
    </section>

    {{-- GROUP CHAT --}}
    <section class="min-h-screen grid grid-cols-1 md:grid-cols-2 items-center bg-red-950">
        <div class="w-3/4 h-72 rounded-lg shadow-lg bg-amber-100 m-auto" data-aos="zoom-in"></div>
        <div class="space-y-6 text-white px-10" data-aos="fade-left">
            <h1 class="text-4xl font-bold">Make Your Group Chats More Fun</h1>
            <flux:text class="max-w-lg text-lg text-gray-200">
            Stay connected and collaborate effortlessly with classmates, friends, or organizations. 
            Access group chats anytime from the sidebar, making it easy to jump into discussions, 
            share ideas, and keep your projects organized.
            </flux:text>
            <ul class="space-y-3">
                <li class="flex items-center gap-2" data-aos="fade-up" data-aos-delay="100"><flux:icon.check class="size-4"/> Real-time messaging and file sharing</li>
                <li class="flex items-center gap-2" data-aos="fade-up" data-aos-delay="200"><flux:icon.check class="size-4"/> Create study groups and project teams</li>
                <li class="flex items-center gap-2" data-aos="fade-up" data-aos-delay="300"><flux:icon.check class="size-4"/> Integrated calendar and event planning</li>
            </ul>
        </div>
    </section>

    {{-- VOTE OFFICER --}}
    <section class="min-h-screen grid grid-cols-1 md:grid-cols-2 items-center bg-gray-900">
        <div class="space-y-6 text-white px-10" data-aos="fade-right">
            <h1 class="text-4xl font-bold">Student Voting Made Simple</h1>
            <flux:text class="max-w-lg text-lg text-gray-300">
            Take part in campus elections with just a few clicks. Your voice matters—choose your leaders and shape the future of your student community.
            </flux:text>
            <flux:button href="{{route('voting')}}" icon="check-circle" variant="primary" color="rose" class="mt-6" data-aos="zoom-in-up">Start Voting</flux:button>
        </div>
        <div class="w-3/4 h-72 rounded-lg shadow-lg bg-amber-100 m-auto" data-aos="zoom-in"></div>
    </section>

    {{-- DUMMY HERE --}}

    {{-- STUDENTS REGISTERED / ORGANIZATIONS / VOTING ROOMS / UPCOMING EVENTS --}}
    <section class="flex items-center justify-center bg-gradient-to-r from-yellow-400 via-amber-700 to-red-800 px-6 py-16">
        <div class="bg-white/90 backdrop-blur-sm rounded-xl shadow-lg p-10 w-full max-w-6xl">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 text-center">
                
                <!-- Students Enrolled -->
                <div data-aos="fade-up" data-aos-delay="100">
                    <flux:icon.users class="mx-auto size-12 text-red-800 mb-4"/>
                    <p class="text-4xl font-bold text-red-900">{{$userCount}}</p>
                    <p class="text-red-700 font-medium">Students</p>
                </div>

                <!-- Academic Programs -->
                <div data-aos="fade-up" data-aos-delay="200">
                    <flux:icon.user-group class="mx-auto size-12 text-red-800 mb-4"/>
                    <p class="text-4xl font-bold text-red-900">{{ $orgCount }}</p>
                    <p class="text-red-700 font-medium">Organizations</p>
                </div>

                <!-- Employees -->
                <div data-aos="fade-up" data-aos-delay="300">
                    <flux:icon.check-circle class="mx-auto size-12 text-red-800 mb-4"/>
                    <p class="text-4xl font-bold text-red-900">{{ $ongoingVotingRooms }}</p>
                    <p class="text-red-700 font-medium">Voting Rooms</p>
                </div>

                <!-- Campuses -->
                <div data-aos="fade-up" data-aos-delay="400">
                    <flux:icon.calendar class="mx-auto size-12 text-red-800 mb-4"/>
                    <p class="text-4xl font-bold text-red-900">8</p>
                    <p class="text-red-700 font-medium">Upcoming Events</p>
                </div>
            </div>
        </div>
    </section>



    {{-- NEWS FEED --}}
    <section class="max-h-screen flex flex-col items-center bg-gray-100 dark:bg-gray-900 pb-10">
        <h2 class="w-full py-2 bg-red-900 dark:bg-red-700 text-center text-white font-semibold" data-aos="fade-down">
            News Feed
        </h2>
        <div class="w-full grid grid-cols-1 md:grid-cols-3 gap-6 p-6">
           @forelse($latestFeeds as $i => $feed)
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 space-y-3" 
                 data-aos="fade-up" 
                 data-aos-delay="{{ $i * 200 }}">
                 
                @if($feed->image)
                    <img src="{{ asset('storage/'.$feed->image) }}" 
                         alt="News Image" 
                         class="w-full h-48 object-cover rounded-md bg-gray-200 dark:bg-gray-700"/>
                @else
                    <div class="w-full h-48 rounded-md bg-gray-200 dark:bg-gray-700 flex items-center justify-center text-gray-400">
                        No Image
                    </div>
                @endif

                <p class="text-gray-700 dark:text-gray-300 line-clamp-3">
                    {{ $feed->content }}
                </p>
            </div>
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
<section class="min-h-screen grid grid-cols-1 lg:grid-cols-5 gap-6 p-6 bg-gray-100 dark:bg-gray-900">

    {{-- Advertisement --}}
    <div class="col-span-3 flex flex-col rounded-2xl shadow-lg  dark:border-gray-700" data-aos="fade-right">
        <div class="w-full py-3 bg-red-800 dark:bg-red-900 text-center text-white font-semibold rounded-t-2xl">
            Advertisement
        </div>

        {{-- Content grows and scrolls if needed --}}
        <div class=" p-6 grid grid-cols-1 md:grid-cols-2 gap-6 ">
            @forelse($latestAds as $i => $ad)
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 space-y-4"
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

                    <p class="text-gray-700 dark:text-gray-300 line-clamp-3">
                        {{ $ad->description }}
                    </p>
                </div>
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
    <div class="col-span-2 flex flex-col rounded-2xl shadow-lg  dark:border-gray-700" data-aos="fade-left">
        <div class="w-full py-3 bg-red-800 dark:bg-red-900 text-center text-white font-semibold rounded-t-2xl">
            Organizations
        </div>

        {{-- Content grows --}}
        <div class="flex flex-col gap-6  p-6 text-gray-700 dark:text-gray-300 ">
            @forelse($organizations as $org)
            <a href="{{ route('org.profile', ['org' => $org->id]) }}">
                <div class="flex items-center gap-4 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg shadow-sm hover:shadow-md transition ">
                    @if($org->logo_path)
                        <img src="{{ Storage::url($org->logo_path) }}"
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

        {{-- Button fixed at bottom --}}
        <div class="flex justify-center p-4">
            <flux:modal.trigger name="viewAllOrgs">
                <flux:button 
                {{-- href="{{ route('organizations') }}"  --}}
                icon="arrow-right-circle" variant="primary" color="red" data-aos="fade-up">
                    View More Orgs
                </flux:button>
            </flux:modal.trigger>
        </div>
    </div>
</section>
<flux:modal name="viewAllOrgs" >
    
    <h2 class="text-2xl font-bold mb-4 ">All Organizations</h2>
        
    <div class="max-w-3xl max-h-[80vh] overflow-y-auto ">
        <div class="grid gap-6">
            @forelse($allOrganizations as $org)
                <a href="{{ route('org.profile', ['org' => $org->id]) }}">
                    <div class="flex items-center gap-4 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg shadow-sm hover:shadow-md transition ">
                        @if($org->logo_path)
                            <img src="{{ Storage::url($org->logo_path) }}"
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
</flux:modal>




    <!-- ====== FOOTER ====== -->
<footer class="bg-red-950 text-gray-300 ">
    <div class="max-w-7xl mx-auto px-6 lg:px-8 py-10 grid grid-cols-1 md:grid-cols-4 gap-8">
        
        <!-- Branding -->
        <div>
            <h2 class="text-xl font-bold text-white">DLCHub</h2>
            <p class="mt-2 text-sm text-gray-400">
                Empowering students and organizations through digital collaboration.
            </p>
        </div>

        <!-- Students -->
        <div>
            <h3 class="text-lg font-semibold text-white">Students</h3>
            <ul class="mt-2 space-y-2 text-sm">
                <li><a href="#" class="hover:text-white">Directory</a></li>
                <li><a href="#" class="hover:text-white">Achievements</a></li>
                <li><a href="#" class="hover:text-white">Profiles</a></li>
            </ul>
        </div>

        <!-- Organizations -->
        <div>
            <h3 class="text-lg font-semibold text-white">Organizations</h3>
            <ul class="mt-2 space-y-2 text-sm">
                <li><a href="#" class="hover:text-white">All Orgs</a></li>
                <li><a href="#" class="hover:text-white">Events</a></li>
                <li><a href="#" class="hover:text-white">Join Requests</a></li>
            </ul>
        </div>

        <!-- Voting Rooms & Events -->
        <div>
            <h3 class="text-lg font-semibold text-white">Voting & Events</h3>
            <ul class="mt-2 space-y-2 text-sm">
                <li><a href="#" class="hover:text-white">Voting Rooms</a></li>
                <li><a href="#" class="hover:text-white">Upcoming Elections</a></li>
                <li><a href="#" class="hover:text-white">Calendar</a></li>
            </ul>
        </div>
    </div>

    <!-- Bottom Bar -->
    <div class="border-t border-gray-700 mt-6">
        <div class="max-w-7xl mx-auto px-6 py-4 flex flex-col md:flex-row justify-between items-center">
            <p class="text-sm text-gray-400">&copy; {{ date('Y') }} DLCHub. All rights reserved.</p>
            <div class="flex space-x-4 mt-2 md:mt-0">
                <a href="#" class="hover:text-white">Privacy</a>
                <a href="#" class="hover:text-white">Terms</a>
                <a href="#" class="hover:text-white">Support</a>
            </div>
        </div>
    </div>
</footer>




</div>
