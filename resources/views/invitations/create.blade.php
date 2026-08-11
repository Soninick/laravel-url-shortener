@extends('layouts.app')

@section('content')
    <div class="page-header">
        <div>
            <h1 class="page-title">Invite User</h1>
            <p class="page-description">Create a user invitation for the allowed role in your current scope.</p>
        </div>
        <a class="button button-secondary" href="{{ route('dashboard') }}">Back to Dashboard</a>
    </div>

    <div class="card form-card">
        <form method="POST" action="{{ route('invitations.store') }}">
            @csrf

            @if (auth()->user()->isSuperAdmin())
                <div class="field">
                    <label for="company_name">Company Name</label>
                    <input id="company_name" type="text" name="company_name" value="{{ old('company_name') }}" required>
                </div>
            @else
                <p class="muted-panel">Company: {{ auth()->user()->company->name }}</p>
            @endif

            <div class="field">
                <label for="name">Name</label>
                <input id="name" type="text" name="name" value="{{ old('name') }}" required>
            </div>

            <div class="field">
                <label for="email">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required>
            </div>

            <div class="field">
                <label for="role">Role</label>
                <select id="role" name="role" required>
                    @if (auth()->user()->isSuperAdmin())
                        <option value="Admin">Admin</option>
                    @else
                        <option value="Admin">Admin</option>
                        <option value="Member">Member</option>
                    @endif
                </select>
            </div>

            <div class="field">
                <label for="password">Temporary Password</label>
                <input id="password" type="password" name="password" required>
            </div>

            <div class="actions">
                <button class="button button-primary" type="submit">Invite User</button>
                <a class="button button-secondary" href="{{ route('dashboard') }}">Cancel</a>
            </div>
        </form>
    </div>
@endsection
