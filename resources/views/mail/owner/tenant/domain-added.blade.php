@extends('mail.layouts.owner')

@section('content')
    <h1>New Domain Added</h1>
    <p>The following tenant has a new domain</p>

    <ul>
        <li><strong>Name:</strong> {{ $mailData['tenant']['name'] }}</li>
        <li><strong>Email:</strong> {{ $mailData['tenant']['email'] }}</li>
        <li><strong>Domains:</strong> {{ $mailData['domain']['domain'] }}</li>
    </ul>
@endsection
