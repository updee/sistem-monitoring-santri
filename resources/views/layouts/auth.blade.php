<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Login') — IBS Ash-Shiddiiqi</title>

    {{-- Bootstrap 5 --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    {{-- Google Fonts --}}
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --hijau:      #1a5c2e;
            --hijau-mid:  #246b38;
            --hijau-pale: #e8f5ec;
            --emas:       #c9a227;
            --emas-light: #f5e9c0;
            --emas-dark:  #9a7a1a;
            --txt:        #1a1a1a;
            --txt2:       #4a4a4a;
            --txt3:       #7a7a7a;
            --border:     #e0e8e0;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Inter', system-ui, sans-serif;
            background: #edf2ed;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .auth-wrapper {
            width: 100%;
            max-width: 900px;
        }

        .auth-card {
            background: #fff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 32px rgba(26,92,46,0.13);
            display: flex;
            min-height: 520px;
        }

        /* ── LEFT PANEL ─────────────────────────────────── */
        .auth-left {
            width: 42%;
            background: var(--hijau);
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 40px 36px;
            flex-shrink: 0;
        }

        .auth-logo-box {
            width: 56px; height: 56px;
            background: var(--emas);
            border-radius: 13px;
            display: flex; align-items: center; justify-content: center;
            font-size: 22px; font-weight: 800;
            color: var(--hijau);
            margin-bottom: 22px;
        }

        .auth-school-name {
            font-size: 22px; font-weight: 800;
            color: #fff; line-height: 1.25;
            margin-bottom: 8px;
        }

        .auth-school-sub {
            font-size: 12px;
            color: rgba(255,255,255,0.6);
            line-height: 1.6;
            margin-bottom: 28px;
        }

        .auth-motto {
            color: var(--emas);
            font-size: 12px; font-style: italic;
            line-height: 1.7;
            border-left: 3px solid var(--emas);
            padding-left: 14px;
        }

        .auth-accent-bars {
            display: flex; gap: 8px;
            margin-top: 28px;
        }
        .auth-bar {
            height: 4px; border-radius: 2px;
            background: rgba(255,255,255,0.2);
        }
        .auth-bar.active { background: var(--emas); }

        /* ── RIGHT PANEL ────────────────────────────────── */
        .auth-right {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 40px 40px;
        }

        .auth-form-title {
            font-size: 22px; font-weight: 800;
            color: var(--hijau); margin-bottom: 6px;
        }

        .auth-form-sub {
            font-size: 13px; color: var(--txt3);
            margin-bottom: 24px;
        }

        /* Role Tabs */
        .role-tabs {
            display: flex; gap: 4px;
            background: #f4f6f4;
            border-radius: 10px;
            padding: 4px;
            margin-bottom: 22px;
        }
        .role-tab {
            flex: 1; padding: 8px;
            text-align: center;
            font-size: 12px; font-weight: 600;
            border-radius: 7px;
            cursor: pointer; color: var(--txt3);
            border: none; background: none;
            transition: all 0.15s;
        }
        .role-tab.active {
            background: #fff;
            color: var(--hijau);
            font-weight: 700;
            box-shadow: 0 1px 4px rgba(0,0,0,0.08);
        }

        /* Form inputs */
        .form-label-custom {
            font-size: 12px; font-weight: 700;
            color: var(--txt2); margin-bottom: 6px;
            display: block;
        }

        .form-input-custom {
            width: 100%; height: 42px;
            border: 1.5px solid var(--border);
            border-radius: 9px;
            padding: 0 14px;
            font-size: 13px; color: var(--txt);
            background: #fafcfa;
            transition: border-color 0.15s, box-shadow 0.15s;
            outline: none;
            font-family: inherit;
        }
        .form-input-custom:focus {
            border-color: var(--hijau);
            box-shadow: 0 0 0 3px rgba(26,92,46,0.1);
            background: #fff;
        }
        .form-input-custom.is-invalid { border-color: #dc3545; }

        .form-group-auth { margin-bottom: 16px; }

        .btn-auth {
            width: 100%; height: 44px;
            background: var(--hijau); color: #fff;
            border: none; border-radius: 9px;
            font-size: 14px; font-weight: 700;
            cursor: pointer; font-family: inherit;
            transition: background 0.15s;
            margin-top: 4px;
        }
        .btn-auth:hover { background: var(--hijau-mid); }

        .auth-divider {
            display: flex; align-items: center;
            gap: 12px; color: var(--txt3);
            font-size: 12px; margin: 14px 0;
        }
        .auth-divider::before, .auth-divider::after {
            content: ''; flex: 1;
            height: 1px; background: var(--border);
        }

        .auth-footer-link { color: var(--hijau); font-weight: 700; }
        .auth-footer-link:hover { text-decoration: underline; }

        .alert-error {
            background: #fce8e8; color: #c62828;
            border: 1px solid #f0c0c0;
            border-radius: 8px;
            padding: 10px 14px;
            font-size: 12px;
            margin-bottom: 16px;
        }

        /* Responsive */
        @media (max-width: 680px) {
            .auth-card { flex-direction: column; min-height: auto; }
            .auth-left  { width: 100%; padding: 28px 24px; }
            .auth-right { padding: 28px 24px; }
        }
    </style>
</head>
<body>
<div class="auth-wrapper">
    @yield('content')
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
