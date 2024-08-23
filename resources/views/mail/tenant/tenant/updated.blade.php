@extends('mail.layouts.tenant')

@section('content')
<h1>Dear {{ $mailData['name'] }},</h1>
<p>Your account information has been updated successfully.</p>

<ul>
    <li><strong>Email:</strong> {{ $mailData['email'] }}</li>
</ul>
@endsection
