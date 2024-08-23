@extends('mail.layouts.tenant')

@section('content')
<h1>Dear {{ $mailData['tenant']['name'] }},</h1>
<p>New Domain has been added successfully.</p>

<ul>
    <li><strong>Domain:</strong> {{ $mailData['domain']['domain'] }}</li>
</ul>
@endsection
