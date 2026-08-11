@extends('layouts.app')

@section('content')
    <div class="page-header">
        <div>
            <h1 class="page-title">{{ auth()->user()->role }} Dashboard</h1>
            <p class="page-description">
                Logged in as {{ auth()->user()->name }}
                @if (auth()->user()->company)
                    from {{ auth()->user()->company->name }}
                @endif
            </p>
        </div>

        @if (! auth()->user()->isSuperAdmin())
            <a class="button button-primary" href="{{ route('short-urls.create') }}">Create Short URL</a>
        @endif
    </div>

    <div class="grid">
        <div class="stat-card">
            <p class="stat-label">User Role</p>
            <p class="stat-value">{{ auth()->user()->role }}</p>
        </div>

        <div class="stat-card">
            <p class="stat-label">Company</p>
            <p class="stat-value">{{ auth()->user()->company->name ?? 'All Companies' }}</p>
        </div>

        <div class="stat-card">
            <p class="stat-label">Short URLs</p>
            <p class="stat-value">{{ $urls->count() }}</p>
        </div>

        <div class="stat-card">
            <p class="stat-label">Invitations</p>
            <p class="stat-value">{{ auth()->user()->isMember() ? 'N/A' : $invitations->count() }}</p>
        </div>
    </div>

    <div class="section">
        <div class="card">
            @if (auth()->user()->isSuperAdmin())
                <p class="page-description">SuperAdmin can view all companies and short URLs, but cannot create short URLs.</p>
            @elseif (auth()->user()->isAdmin())
                <p class="page-description">Admin can invite users and view company short URLs.</p>
            @else
                <p class="page-description">Member can create and view only their own short URLs.</p>
            @endif
        </div>
    </div>

    <section class="section">
        <div class="section-header">
            <h2 class="section-title">Short URLs</h2>
        </div>

        <div class="table-wrap">
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
                            <td class="url-cell">
                                <a href="{{ route('short-urls.redirect', $url->code) }}" target="_blank">
                                    {{ route('short-urls.redirect', $url->code) }}
                                </a>
                            </td>
                            <td class="url-cell"><a href="{{ $url->original_url }}" target="_blank">{{ $url->original_url }}</a></td>
                            <td>{{ $url->company->name }}</td>
                            <td>{{ $url->user->name }}</td>
                            <td>{{ $url->created_at->format('Y-m-d H:i') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td class="empty-state" colspan="5">No short URLs found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    @if (! auth()->user()->isMember())
        <section class="section">
            <div class="section-header">
                <h2 class="section-title">Invitations</h2>
                <a class="button button-secondary" href="{{ route('invitations.create') }}">Invite User</a>
            </div>

            <div class="table-wrap">
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
                                <td class="empty-state" colspan="5">No invitations found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    @endif
@endsection
