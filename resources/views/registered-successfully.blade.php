<x-layouts.guest>
    <div class="bg-white dark:bg-gray-800 max-w-md w-full mx-auto p-8 rounded-2xl shadow-2xl border border-gray-200 dark:border-gray-700 text-center space-y-6">
        
        <div class="flex justify-center items-center space-x-2">
            <span class="text-3xl">🎉</span>
            <flux:heading size="xl" class="text-gray-900 dark:text-white">Registration Complete</flux:heading>
        </div>

        <div class="bg-gray-100 dark:bg-gray-700 rounded-xl py-4 px-6 shadow-inner">
            <p class="text-sm text-gray-600 dark:text-gray-300">
                Welcome, <span class="font-semibold text-primary-600 dark:text-primary-400">{{ session('user_name') ?? 'New User' }}</span>!<br>
                Your account has been created successfully.
            </p>
            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                Please wait for admin verification before logging in.
            </p>
        </div>

        <flux:button href="{{ route('login') }}" color="primary" class="w-full mt-4 size-lg">
            Back to Login
        </flux:button>
    </div>
</x-layouts.guest>
