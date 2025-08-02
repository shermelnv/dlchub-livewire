<x-layouts.guest>
    <div class="bg-white dark:bg-gray-800 p-8 rounded-2xl shadow-xl max-w-md w-full text-center space-y-6 border border-gray-200 dark:border-gray-700">
        <flux:heading size="xl">⏳ Account Not Yet Verified</flux:heading>

        <flux:text class="text-gray-600 dark:text-gray-300">
            Hello {{ session('user_name') }}, your account has been registered but is still awaiting admin approval.<br>
            You will be notified once it’s activated.
        </flux:text>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <flux:button color="danger" type="submit">
                Logout
            </flux:button>
        </form>
    </div>
</x-layouts.guest>
