<x-layouts.app.sidebar :title="$title ?? null">
    <flux:main class="!p-0 !bg-[#FFFFEB] dark:!bg-zinc-800">
        {{-- @if($title !== 'Chat')
        <flux:navbar class="bg-red-950 px-5 justify-between">
            <flux:heading size="xl" >{{$title ?? null}}</flux:heading>
            @if($title == 'Feed' || $title == 'Advertisement')
            <div class="max-w-md w-full ">
                <flux:input icon="magnifying-glass" placeholder="Search DLC HUB" clearable/>
            </div>
            @endif
        </flux:navbar>
        @endif --}}
        
        {{ $slot }}
        @if(request()->routeIs('inbox', 'feed', 'advertisement', 'landing-page' , '/home', 'home'))
            <!-- ====== FOOTER ====== -->
            <footer class="w-full bg-red-900 dark:bg-red-950 text-white">
                <div class="max-w-7xl mx-auto px-4 py-6 grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                    
                    <!-- Brand -->
                    <div class="flex flex-col items-center md:items-start">
                        <span class="font-semibold text-lg">PLCHUB</span>
                        <span class="opacity-75">Connecting Students Together</span>
                    </div>

                    <!-- Links -->
                    <div class="flex flex-col items-center md:items-center">
                        <span class="font-semibold mb-2">Platforms</span>
                        <div class="flex gap-2 text-center md:text-left">
                            <p  class="hover:font-bold">News Feed</p>
                            <p  class="hover:font-bold">Advertisement</p>
                            <p  class="hover:font-bold">Voting</p>
                            <p  class="hover:font-bold">Chat</p>
                        </div>
                    </div>

                    <!-- Copyright -->
                    <div class="flex flex-col items-center md:items-end opacity-75">
                        <p>© {{ now()->year }} PLCHUB. All rights reserved.</p>
                        <p class="text-xs">Built by students for Pampanga State University -- Lubao Campus</p>
                    </div>
                </div>
            </footer>
        @endif
    </flux:main>
    <x-toaster-hub />
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
    // Initialize AOS normally
    document.addEventListener('DOMContentLoaded', () => {
        AOS.init({
            duration: 800,
            once: true,
        });
    });
    
</script>
</x-layouts.app.sidebar>
