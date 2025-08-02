{{-- REJECT LIST --}}
<flux:modal name="reject-list" class="md:w-[40rem]">
    <div class="space-y-6">
        <flux:heading size="lg">Rejected Memberships</flux:heading>
        <flux:text>Below is a list of users whose membership requests were rejected.</flux:text>

        {{-- Sample Rejected Users Table --}}
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                <thead class="bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-300">
                    <tr>
                        <th class="px-4 py-2 text-left">Name</th>
                        <th class="px-4 py-2 text-left">Email</th>
                        <th class="px-4 py-2 text-left">Reason</th>
                        <th class="px-4 py-2 text-left">Rejected At</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-900 text-gray-700 dark:text-gray-300 divide-y divide-gray-100 dark:divide-gray-800">
                    @foreach ([
                        ['name' => 'Juan Dela Cruz', 'email' => 'juan@example.com', 'reason' => 'Invalid ID', 'date' => '2025-07-01'],
                        ['name' => 'Maria Clara', 'email' => 'maria@example.com', 'reason' => 'Duplicate account', 'date' => '2025-07-05'],
                        ['name' => 'Jose Rizal', 'email' => 'rizal@example.com', 'reason' => 'Unverified organization', 'date' => '2025-07-10'],
                    ] as $rejected)
                        <tr>
                            <td class="px-4 py-2">{{ $rejected['name'] }}</td>
                            <td class="px-4 py-2">{{ $rejected['email'] }}</td>
                            <td class="px-4 py-2">{{ $rejected['reason'] }}</td>
                            <td class="px-4 py-2">{{ \Carbon\Carbon::parse($rejected['date'])->format('M d, Y') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="flex justify-end">
            <flux:modal.close>
                <flux:button variant="ghost">Close</flux:button>
            </flux:modal.close>
        </div>
    </div>
</flux:modal>
