@extends('layouts.app')

@section('content')
    <div class="login-shell">
        <div class="card login-card">
            <div class="page-header">
                <div>
                    <h1 class="page-title">Login</h1>
                    <p class="page-description">Sign in to manage your short URLs and invitations.</p>
                </div>
            </div>

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="field">
                    <label for="email">Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus>
                </div>

                <div class="field">
                    <label for="password">Password</label>
                    <input id="password" type="password" name="password" required>
                </div>

                <div class="actions">
                    <button class="button button-primary" type="submit">Login</button>
                </div>
            </form>
        </div>
    </div>
@endsection
