<x-mail::message>
# ✅ Your Account Has Been Verified!

Hi {{ $user->name }},

We're excited to let you know that your account on **DLCHub** has been successfully **verified**. You can now log in and start using all features.

---

## 🔐 Your Login Credentials

- **Email:** {{ $user->email }}
> You may change your password anytime in your account settings for added security.

<x-mail::button :url="route('login')">
Login to Your Account
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
