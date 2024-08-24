@extends('mail.layouts.owner')

@section('content')
    <h1>Dear {{ $mailData['name'] }}</h1>

    <p>We received a request to reset your password. If you initiated this request, please click the button below to reset
        your password.</p>

    <p>If you did not request a password reset, you can safely ignore this email, and your current password will remain
        unchanged.</p>

    <a href="{{ $mailData['url'] }}">Reset Password</a>
@endsection
