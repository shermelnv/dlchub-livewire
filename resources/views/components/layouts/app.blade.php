<x-layouts.app.sidebar :title="$title ?? null">
    <flux:main style="padding: 0">
        {{ $slot }}

        
    </flux:main>
    <x-toaster-hub />
</x-layouts.app.sidebar>
