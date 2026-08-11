<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laravel URL Shortener</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 980px; margin: 30px auto; padding: 0 15px; line-height: 1.4; }
        input, select, button { padding: 8px; margin: 4px 0; }
        label { display: block; margin-top: 10px; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; vertical-align: top; }
        nav { display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px; }
        .error { color: #b00020; }
        .status { color: #116611; }
    </style>
</head>
<body>
    @auth
        <nav>
            <div>
                <a href="{{ route('dashboard') }}">Dashboard</a>
                @if (! auth()->user()->isMember())
                    | <a href="{{ route('invitations.create') }}">Invite User</a>
                @endif
                @if (! auth()->user()->isSuperAdmin())
                    | <a href="{{ route('short-urls.create') }}">Create Short URL</a>
                @endif
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit">Logout</button>
            </form>
        </nav>
    @endauth

    @if (session('status'))
        <p class="status">{{ session('status') }}</p>
    @endif

    @if ($errors->any())
        <div class="error">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @yield('content')
</body>
</html>
