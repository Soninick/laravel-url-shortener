@extends('layouts.app')

@section('content')
    <h1>Short URL</h1>

    <p><strong>Short URL:</strong> <a href="{{ route('short-urls.redirect', $shortUrl->code) }}">{{ route('short-urls.redirect', $shortUrl->code) }}</a></p>
    <p><strong>Original URL:</strong> <a href="{{ $shortUrl->original_url }}">{{ $shortUrl->original_url }}</a></p>
    <p><strong>Company:</strong> {{ $shortUrl->company->name }}</p>
    <p><strong>Created By:</strong> {{ $shortUrl->user->name }}</p>
@endsection
