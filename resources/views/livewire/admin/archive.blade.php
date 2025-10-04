<div class="space-y-4 p-10">
    <h2 class="text-lg font-semibold">Archived Students</h2>

    
 <table class="min-w-full table-auto divide-y divide-gray-200 dark:divide-gray-700 text-sm">
            <thead class="bg-gray-100 dark:bg-zinc-800 text-left text-gray-600 dark:text-gray-300 uppercase tracking-wide">
                <tr>
        
                    <th class="px-4 py-3">#</th>
                    <th class="px-4 py-3">Username</th>
                    <th class="px-4 py-3">Name</th>
                    <th class="px-4 py-3">Email</th>
     
                </tr>

            <tbody class="bg-white dark:bg-zinc-900 divide-y divide-gray-200 dark:divide-zinc-700">
                @forelse ($archives as $archive)
                    <tr class="hover:bg-gray-50 dark:hover:bg-zinc-800" wire:key="user-{{ $archive->id }}">
                        <td class="px-4 py-3 font-medium text-black dark:text-white">{{ $loop->iteration }}</td>
                        <td class="px-4 py-3 font-medium text-black dark:text-white">{{ $archive->username }}</td>
                        <td class="px-4 py-3 font-medium text-maroon-900 dark:text-rose-300">{{ $archive->name }}</td>
                        <td class="px-4 py-3 text-gray-700 dark:text-gray-400">{{ $archive->email }}</td>

                    </tr>

                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-6 text-center text-gray-500">No archives yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

</div>
