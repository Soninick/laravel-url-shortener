@extends('layouts.app')

@section('content')
    <h1>Invite User</h1>

    <form method="POST" action="{{ route('invitations.store') }}">
        @csrf

        @if (auth()->user()->isSuperAdmin())
            <label for="company_name">Company Name</label>
            <input id="company_name" type="text" name="company_name" value="{{ old('company_name') }}" required>
        @else
            <p>Company: {{ auth()->user()->company->name }}</p>
        @endif

        <label for="name">Name</label>
        <input id="name" type="text" name="name" value="{{ old('name') }}" required>

        <label for="email">Email</label>
        <input id="email" type="email" name="email" value="{{ old('email') }}" required>

        <label for="role">Role</label>
        <select id="role" name="role" required>
            @if (auth()->user()->isSuperAdmin())
                <option value="Admin">Admin</option>
            @else
                <option value="Admin">Admin</option>
                <option value="Member">Member</option>
            @endif
        </select>

        <label for="password">Temporary Password</label>
        <input id="password" type="password" name="password" required>

        <p><button type="submit">Invite User</button></p>
    </form>
@endsection
