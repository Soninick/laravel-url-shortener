<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laravel URL Shortener</title>
    <style>
        :root {
            --bg: #f5f7fb;
            --surface: #ffffff;
            --surface-soft: #f8fafc;
            --text: #172033;
            --muted: #64748b;
            --line: #d8e0ea;
            --primary: #1f6feb;
            --primary-dark: #1858be;
            --success-bg: #e9f8ef;
            --success-text: #176b37;
            --error-bg: #fff1f2;
            --error-text: #b42318;
            --shadow: 0 18px 45px rgba(15, 23, 42, .08);
            --radius: 8px;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            background: var(--bg);
            color: var(--text);
            font-family: Arial, Helvetica, sans-serif;
            line-height: 1.5;
        }

        a {
            color: var(--primary);
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

        .site-header {
            background: var(--surface);
            border-bottom: 1px solid var(--line);
        }

        .nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 20px;
            max-width: 1120px;
            margin: 0 auto;
            padding: 16px 20px;
        }

        .brand {
            color: var(--text);
            font-size: 18px;
            font-weight: 700;
            letter-spacing: .01em;
        }

        .nav-links {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 10px;
        }

        .nav-link {
            color: var(--muted);
            font-size: 14px;
            font-weight: 600;
            padding: 8px 10px;
            border-radius: 6px;
        }

        .nav-link:hover {
            background: var(--surface-soft);
            color: var(--text);
            text-decoration: none;
        }

        .page {
            max-width: 1120px;
            margin: 0 auto;
            padding: 32px 20px 48px;
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 18px;
            margin-bottom: 24px;
        }

        .page-title {
            margin: 0;
            font-size: clamp(26px, 4vw, 36px);
            line-height: 1.15;
        }

        .page-description {
            margin: 8px 0 0;
            color: var(--muted);
        }

        .card {
            background: var(--surface);
            border: 1px solid var(--line);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            padding: 22px;
        }

        .section {
            margin-top: 28px;
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 14px;
            margin-bottom: 12px;
        }

        .section-title {
            margin: 0;
            font-size: 20px;
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 16px;
        }

        .stat-card {
            background: var(--surface);
            border: 1px solid var(--line);
            border-radius: var(--radius);
            padding: 18px;
        }

        .stat-label {
            margin: 0;
            color: var(--muted);
            font-size: 13px;
            font-weight: 700;
            text-transform: uppercase;
        }

        .stat-value {
            margin: 8px 0 0;
            font-size: 24px;
            font-weight: 700;
            overflow-wrap: anywhere;
        }

        .form-card {
            max-width: 680px;
        }

        .field {
            margin-bottom: 18px;
        }

        label {
            display: block;
            margin-bottom: 7px;
            font-size: 14px;
            font-weight: 700;
        }

        input,
        select {
            width: 100%;
            border: 1px solid var(--line);
            border-radius: 7px;
            background: #fff;
            color: var(--text);
            font: inherit;
            padding: 11px 12px;
        }

        input:focus,
        select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(31, 111, 235, .14);
            outline: none;
        }

        .button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 40px;
            border: 1px solid transparent;
            border-radius: 7px;
            cursor: pointer;
            font: inherit;
            font-weight: 700;
            padding: 9px 15px;
            text-decoration: none;
        }

        .button:hover {
            text-decoration: none;
        }

        .button-primary {
            background: var(--primary);
            color: #fff;
        }

        .button-primary:hover {
            background: var(--primary-dark);
            color: #fff;
        }

        .button-secondary {
            background: var(--surface);
            border-color: var(--line);
            color: var(--text);
        }

        .button-secondary:hover {
            background: var(--surface-soft);
        }

        .button-logout {
            background: #fff;
            border-color: var(--line);
            color: var(--muted);
        }

        .button-logout:hover {
            color: var(--text);
            background: var(--surface-soft);
        }

        .actions {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 22px;
        }

        .alert {
            max-width: 1120px;
            margin: 18px auto 0;
            border-radius: var(--radius);
            padding: 13px 16px;
        }

        .alert-success {
            background: var(--success-bg);
            border: 1px solid #bce8ca;
            color: var(--success-text);
        }

        .alert-error {
            background: var(--error-bg);
            border: 1px solid #ffc9cf;
            color: var(--error-text);
        }

        .alert ul {
            margin: 0;
            padding-left: 20px;
        }

        .table-wrap {
            overflow-x: auto;
            border: 1px solid var(--line);
            border-radius: var(--radius);
            background: var(--surface);
        }

        table {
            width: 100%;
            min-width: 760px;
            border-collapse: collapse;
        }

        th,
        td {
            border-bottom: 1px solid var(--line);
            padding: 13px 14px;
            text-align: left;
            vertical-align: top;
        }

        th {
            background: var(--surface-soft);
            color: var(--muted);
            font-size: 13px;
            text-transform: uppercase;
        }

        tr:hover td {
            background: #fbfdff;
        }

        tbody tr:last-child td {
            border-bottom: 0;
        }

        .url-cell {
            max-width: 360px;
            overflow-wrap: anywhere;
        }

        .empty-state {
            color: var(--muted);
            text-align: center;
            padding: 28px 14px;
        }

        .detail-list {
            display: grid;
            gap: 14px;
            margin: 0;
        }

        .detail-row {
            display: grid;
            grid-template-columns: 130px minmax(0, 1fr);
            gap: 16px;
        }

        .detail-label {
            color: var(--muted);
            font-weight: 700;
        }

        .detail-value {
            overflow-wrap: anywhere;
        }

        .login-shell {
            max-width: 460px;
            margin: 36px auto;
        }

        .login-card {
            padding: 28px;
        }

        .muted-panel {
            background: var(--surface-soft);
            border: 1px solid var(--line);
            border-radius: var(--radius);
            color: var(--muted);
            padding: 13px 14px;
        }

        @media (max-width: 820px) {
            .nav,
            .page-header,
            .section-header {
                align-items: stretch;
                flex-direction: column;
            }

            .grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (max-width: 560px) {
            .page {
                padding: 24px 14px 36px;
            }

            .nav {
                padding: 14px;
            }

            .grid {
                grid-template-columns: 1fr;
            }

            .card,
            .login-card {
                padding: 18px;
            }

            .detail-row {
                grid-template-columns: 1fr;
                gap: 4px;
            }
        }
    </style>
</head>
<body>
    <header class="site-header">
        <nav class="nav">
            <a class="brand" href="{{ auth()->check() ? route('dashboard') : route('login') }}">
                Laravel URL Shortener
            </a>

            @auth
                <div class="nav-links">
                    <a class="nav-link" href="{{ route('dashboard') }}">Dashboard</a>

                    @if (! auth()->user()->isMember())
                        <a class="nav-link" href="{{ route('invitations.create') }}">Invite User</a>
                    @endif

                    @if (! auth()->user()->isSuperAdmin())
                        <a class="nav-link" href="{{ route('short-urls.create') }}">Create Short URL</a>
                    @endif
                </div>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="button button-logout" type="submit">Logout</button>
                </form>
            @endauth
        </nav>
    </header>

    @if (session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    @if ($errors->any())
        <div class="alert alert-error">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <main class="page">
        @yield('content')
    </main>
</body>
</html>
