@extends('mail.layouts.owner')

@section('content')
    <h1>New Tenant Created</h1>

    <table>
        <tr>
            <td>Name</td>
            <td>{{ $mailData->name }}</td>
        </tr>
        <tr>
            <td>Email</td>
            <td>{{ $mailData->email }}</td>
        </tr>
        <tr>
            <td>Domain</td>
            <td>{{ $mailData->domain }}.{{ config('app.domain') }}</td>
        </tr>
    </table>
@endsection
