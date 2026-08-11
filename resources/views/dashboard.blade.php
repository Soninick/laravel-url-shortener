@extends('layouts.app')

@section('content')
    <h1>{{ auth()->user()->role }} Dashboard</h1>

    <p>
        Logged in as {{ auth()->user()->name }}
        @if (auth()->user()->company)
            from {{ auth()->user()->company->name }}
        @endif
    </p>

    @if (auth()->user()->isSuperAdmin())
        <p>SuperAdmin can view all companies and short URLs, but cannot create short URLs.</p>
    @elseif (auth()->user()->isAdmin())
        <p>Admin can invite users and view company short URLs.</p>
    @else
        <p>Member can create and view only their own short URLs.</p>
    @endif

    <h2>Short URLs</h2>

    <table>
        <thead>
            <tr>
                <th>Short URL</th>
                <th>Original URL</th>
                <th>Company</th>
                <th>Created By</th>
                <th>Created At</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($urls as $url)
                <tr>
                    <td>
                        <a href="{{ route('short-urls.redirect', $url->code) }}" target="_blank">
                            {{ route('short-urls.redirect', $url->code) }}
                        </a>
                    </td>
                    <td><a href="{{ $url->original_url }}" target="_blank">{{ $url->original_url }}</a></td>
                    <td>{{ $url->company->name }}</td>
                    <td>{{ $url->user->name }}</td>
                    <td>{{ $url->created_at->format('Y-m-d H:i') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5">No short URLs found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    @if (! auth()->user()->isMember())
        <h2>Invitations</h2>

        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Company</th>
                    <th>Invited By</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($invitations as $invitation)
                    <tr>
                        <td>{{ $invitation->name }}</td>
                        <td>{{ $invitation->email }}</td>
                        <td>{{ $invitation->role }}</td>
                        <td>{{ $invitation->company->name }}</td>
                        <td>{{ $invitation->inviter->name }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5">No invitations found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    @endif
@endsection
