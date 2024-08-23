@extends('mail.layouts.tenant')

@section('content')
<h1>Welcome to {{ config('app.name') }}, {{ $mailData['name'] }}!</h1>
<p>Your account has been created successfully. Below are your login details:</p>

<ul>
    <li><strong>Email:</strong> {{ $mailData['email'] }}</li>
    <li><strong>Password:</strong> {{ $mailData['password'] }}</li>
</ul>

<p>Please change your password after your first login.</p>
@endsection
