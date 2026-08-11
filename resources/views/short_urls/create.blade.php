@extends('layouts.app')

@section('content')
    <div class="page-header">
        <div>
            <h1 class="page-title">Create Short URL</h1>
            <p class="page-description">Paste the original URL and the app will create a short link for your allowed scope.</p>
        </div>
        <a class="button button-secondary" href="{{ route('dashboard') }}">Back to Dashboard</a>
    </div>

    <div class="card form-card">
        <form method="POST" action="{{ route('short-urls.store') }}">
            @csrf

            <div class="field">
                <label for="original_url">Original URL</label>
                <input id="original_url" type="url" name="original_url" value="{{ old('original_url') }}" required>
            </div>

            <div class="actions">
                <button class="button button-primary" type="submit">Create</button>
                <a class="button button-secondary" href="{{ route('dashboard') }}">Cancel</a>
            </div>
        </form>
    </div>
@endsection
