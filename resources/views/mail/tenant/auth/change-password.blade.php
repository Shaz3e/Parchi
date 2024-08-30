@extends('mail.layouts.owner')

@section('content')
    <h1>Dear {{ $mailData->name }},</h1>

    <p>We wanted to inform you that your password has been successfully reset. If you made this change, there is no further
        action required. Below are the details of the reset for your reference:</p>

    <p>Date and Time of Change: <strong>{{ now()->format('l, F jS Y, h:i A') }}</strong><br>
        IP Address: <strong>{{ request()->ip() }}</strong><br>
    </p>


    <p>If you did not request this change, it is important that you take immediate action to secure your account. Please
        follow the steps below:
    </p>

    <p>Secure Your Account: Go to {{ config('app.domain') }} and change your password immediately using the "Forgot
        Password"
        feature.</p>
@endsection
