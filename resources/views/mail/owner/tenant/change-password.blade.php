@extends('mail.layouts.owner')

@section('content')
    <h1>Password Changed</h1>
    <p>The following tenant has changed their password</p>

    <ul>
        <li><strong>Name:</strong> {{ $mailData->name }}</li>
        <li><strong>Email:</strong> {{ $mailData->email }}</li>
    </ul>
@endsection
