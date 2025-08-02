<x-mail::message>
# Hello {{ $user->name }},

@if ($user->status === 'approved')
Your account has been **Approved**. You may now log in and access your account.
@else
Your registration is received and currently **pending** verification. You will receive another email once it is approved.
@endif

<x-mail::button :url="route('login')">
Go to Login
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
