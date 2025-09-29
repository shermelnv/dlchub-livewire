<x-layouts.guest>
    <div class="bg-white dark:bg-gray-800 p-8 rounded-2xl shadow-xl max-w-md w-full text-center space-y-6 border border-gray-200 dark:border-gray-700">
        <flux:heading size="xl">⏳ Account Not Yet Verified</flux:heading>

        <flux:text class="text-gray-600 dark:text-gray-300">
            Hello {{ session('user_name') }}, your account has been registered but is still awaiting admin approval.<br>
            You will be notified once it’s activated.
        </flux:text>

     
            <flux:button color="danger" type="submit" :href="route('logout')">
                Logout
            </flux:button>
            <flux:button color="danger" type="submit" :href="route('home')">
                Home
            </flux:button>
      

    </div>
</x-layouts.guest>
