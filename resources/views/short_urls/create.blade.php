@extends('layouts.app')

@section('content')
    <h1>Create Short URL</h1>

    <form method="POST" action="{{ route('short-urls.store') }}">
        @csrf

        <label for="original_url">Original URL</label>
        <input id="original_url" type="url" name="original_url" value="{{ old('original_url') }}" required style="width: 100%;">

        <p><button type="submit">Create</button></p>
    </form>
@endsection
