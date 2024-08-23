@extends('mail.layouts.tenant')

@section('content')
<h1>Dear {{ $mailData['tenantData']['name'] }},</h1>
<p>Your account has been deleted with all associated data</p>

<ul>
    <li><strong>Name:</strong> {{ $mailData['tenantData']['name'] }}</li>
    <li><strong>Email:</strong> {{ $mailData['tenantData']['email'] }}</li>
    <li><strong>Domains:</strong>
        <ul>
            @if (!empty($mailData['domains']))
                @foreach ($mailData['domains'] as $domain)
                    <li>{{ $domain }}</li>
                @endforeach
            @else
                <li>No domains associated with this tenant.</li>
            @endif
        </ul>
    </li>
</ul>
@endsection
