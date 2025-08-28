<x-layouts.app.sidebar :title="$title ?? null">
    <flux:main style="padding: 0">
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
