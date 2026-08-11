@extends('layouts.app')

@section('content')
    <div class="page-header">
        <div>
            <h1 class="page-title">Short URL</h1>
            <p class="page-description">Details for the selected shortened link.</p>
        </div>
        <a class="button button-secondary" href="{{ route('dashboard') }}">Back to Dashboard</a>
    </div>

    <div class="card">
        <dl class="detail-list">
            <div class="detail-row">
                <dt class="detail-label">Short URL</dt>
                <dd class="detail-value">
                    <a href="{{ route('short-urls.redirect', $shortUrl->code) }}" target="_blank">
                        {{ route('short-urls.redirect', $shortUrl->code) }}
                    </a>
                </dd>
            </div>

            <div class="detail-row">
                <dt class="detail-label">Original URL</dt>
                <dd class="detail-value"><a href="{{ $shortUrl->original_url }}" target="_blank">{{ $shortUrl->original_url }}</a></dd>
            </div>

            <div class="detail-row">
                <dt class="detail-label">Company</dt>
                <dd class="detail-value">{{ $shortUrl->company->name }}</dd>
            </div>

            <div class="detail-row">
                <dt class="detail-label">Created By</dt>
                <dd class="detail-value">{{ $shortUrl->user->name }}</dd>
            </div>
        </dl>
    </div>
@endsection
