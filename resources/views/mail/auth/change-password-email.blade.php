<x-mail::message>
# Dear {{ $mailData->name }},

We wanted to inform you that your password has been successfully reset. If you made this change, there is no further
action required. Below are the details of the reset for your reference:

Date and Time of Change: **{{ now()->format('l, F jS Y, h:i A') }}**<br>
IP Address: **{{ request()->ip() }}**<br>


If you did not request this change, it is important that you take immediate action to secure your account. Please
follow the steps below:

Secure Your Account: Go to {{ config('app.domain') }} and change your password immediately using the "Forgot Password"
feature.

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
