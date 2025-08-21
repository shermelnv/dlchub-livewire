<x-mail::message>
# Hello {{ $user->name }},

Your PLCHub account has been created successfully.

---

**Email:** {{ $user->email }}  
**Temporary Password:** {{ $password }}

You can log in and change your password anytime.

<x-mail::button :url="route('login')">
Go to Login
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
