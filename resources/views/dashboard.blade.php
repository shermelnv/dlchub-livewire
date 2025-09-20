<x-layouts.app :title="__('Dashboard')">
               {{-- Header --}}
    

<div class="flex h-full w-full flex-1 flex-col gap-6 rounded-xl p-4">

        {{-- Summary Cards --}}
        <livewire:dashboard-stats/>

        {{-- 📊 Live Statistics + ⚡ Quick Actions --}}
    <div class="grid h-full">
        {{-- Live Statistics (2/3 width) --}}
        <livewire:dashboard-recent-activity />



      


    </div>
</div>


    </div>
</x-layouts.app>
