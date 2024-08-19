<x-mail::message>
# Dear {{ $mailData->name }}

We received a request to reset your password. If you initiated this request, please click the button below to reset
your password.

If you did not request a password reset, you can safely ignore this email, and your current password will remain
unchanged.

<x-mail::button :url="$url">
Reset Password
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
