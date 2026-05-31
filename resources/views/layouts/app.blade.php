<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') — IBS Ash-Shiddiiqi</title>

    {{-- Bootstrap 5 CDN --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    {{-- Google Fonts: Inter --}}
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        /* ══════════════════════════════════════════════════════
           CSS VARIABLES — Brand Colors IBS Ash-Shiddiiqi
           ══════════════════════════════════════════════════════ */
        :root {
            --hijau:         #1a5c2e;
            --hijau-mid:     #246b38;
            --hijau-pale:    #e8f5ec;
            --hijau-border:  #b8d8c0;
            --emas:          #c9a227;
            --emas-light:    #f5e9c0;
            --emas-dark:     #9a7a1a;
            --txt:           #1a1a1a;
            --txt2:          #4a4a4a;
            --txt3:          #7a7a7a;
            --bg-page:       #f4f6f4;
            --card:          #ffffff;
            --border-light:  #e0e8e0;
            --sidebar-w:     230px;
            --topbar-h:      54px;
            --radius:        10px;
        }

        /* ══════════════════════════════════════════════════════
           BASE
           ══════════════════════════════════════════════════════ */
        * { box-sizing: border-box; }
        body {
            font-family: 'Inter', system-ui, sans-serif;
            background: var(--bg-page);
            color: var(--txt);
            font-size: 14px;
            margin: 0; padding: 0;
        }

        /* ══════════════════════════════════════════════════════
           SIDEBAR
           ══════════════════════════════════════════════════════ */
        .sidebar {
            position: fixed;
            top: 0; left: 0;
            width: var(--sidebar-w);
            height: 100vh;
            background: var(--hijau);
            display: flex;
            flex-direction: column;
            z-index: 1000;
            overflow-y: auto;
            transition: transform .25s ease;
        }

        /* Logo */
        .sb-logo {
            display: flex; align-items: center; gap: 10px;
            padding: 18px 16px 14px;
            border-bottom: 1px solid rgba(255,255,255,.1);
            text-decoration: none;
        }
        .sb-logo-icon {
            width: 36px; height: 36px;
            background: var(--emas);
            border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            font-size: 14px; font-weight: 800;
            color: var(--hijau); flex-shrink: 0;
        }
        .sb-logo-name { font-size: 11px; font-weight: 700; color: #fff; line-height: 1.3; }
        .sb-logo-sub  { font-size: 9px;  color: rgba(255,255,255,.5); }

        /* User strip */
        .sb-user {
            display: flex; align-items: center; gap: 10px;
            padding: 11px 16px;
            border-bottom: 1px solid rgba(255,255,255,.08);
        }
        .sb-avatar {
            width: 32px; height: 32px; border-radius: 50%;
            background: var(--emas);
            display: flex; align-items: center; justify-content: center;
            font-size: 11px; font-weight: 800; color: var(--hijau);
            flex-shrink: 0;
        }
        .sb-uname { font-size: 11px; font-weight: 600; color: #fff; line-height: 1.3; }
        .sb-urole  { font-size: 10px; color: rgba(255,255,255,.5); }

        /* Nav */
        .sb-nav { flex: 1; padding: 8px 0; }
        .sb-section {
            padding: 10px 16px 3px;
            font-size: 9px; font-weight: 700;
            color: rgba(255,255,255,.35);
            letter-spacing: .1em; text-transform: uppercase;
        }
        .sb-item {
            display: flex; align-items: center; gap: 9px;
            padding: 9px 16px;
            font-size: 12px; color: rgba(255,255,255,.65);
            text-decoration: none;
            border-left: 3px solid transparent;
            transition: background .15s, color .15s;
            white-space: nowrap;
        }
        .sb-item:hover { background: rgba(255,255,255,.07); color: #fff; text-decoration: none; }
        .sb-item.active {
            background: rgba(255,255,255,.12);
            color: #fff; border-left-color: var(--emas);
            font-weight: 600;
        }
        /* Keep sidebar icons consistent & not oversized */
        .sidebar .sidebar-item svg,
        .sidebar .sb-item svg {
            width: 16px;
            height: 16px;
            flex-shrink: 0;
        }
        .sidebar .sidebar-item svg { opacity: .85; }
        .sidebar .sidebar-item.active svg { opacity: 1; }
        /* ⚠️ FIX: SVG icon di sidebar harus kecil dan fixed */
        .sb-item svg {
            width: 15px !important; height: 15px !important;
            flex-shrink: 0;
        }
        .sb-badge {
            margin-left: auto;
            background: var(--emas); color: var(--hijau);
            font-size: 9px; font-weight: 800;
            padding: 2px 6px; border-radius: 10px;
            min-width: 18px; text-align: center;
        }

        /* Footer logout */
        .sb-footer {
            padding: 12px 16px;
            border-top: 1px solid rgba(255,255,255,.08);
        }
        .sb-logout {
            display: flex; align-items: center; gap: 8px;
            background: none; border: none;
            color: rgba(255,255,255,.55);
            font-size: 12px; cursor: pointer;
            padding: 6px 0; width: 100%;
            font-family: inherit; transition: color .15s;
        }
        .sb-logout:hover { color: #fff; }
        .sb-logout svg { width: 14px !important; height: 14px !important; }

        /* ══════════════════════════════════════════════════════
           MAIN WRAPPER
           ══════════════════════════════════════════════════════ */
        .main-wrapper {
            margin-left: var(--sidebar-w);
            min-height: 100vh;
            display: flex; flex-direction: column;
        }

        /* Topbar */
        .topbar {
            height: var(--topbar-h);
            background: #fff;
            border-bottom: 1px solid var(--border-light);
            display: flex; align-items: center;
            justify-content: space-between;
            padding: 0 22px;
            position: sticky; top: 0; z-index: 100;
            box-shadow: 0 1px 3px rgba(0,0,0,.05);
        }
        .tb-title { font-size: 15px; font-weight: 700; color: var(--hijau); }
        .tb-bc    { font-size: 11px; color: var(--txt3); margin-top: 1px; }
        .tb-bc span { color: var(--hijau); font-weight: 500; }
        .tb-right { display: flex; align-items: center; gap: 10px; }
        .tb-notif {
            position: relative;
            width: 34px; height: 34px;
            border: none; border-radius: 8px;
            background: var(--hijau-pale);
            display: flex; align-items: center; justify-content: center;
            cursor: pointer; text-decoration: none;
        }
        .tb-notif svg { width: 17px !important; height: 17px !important; color: var(--hijau); }
        .tb-notif-dot {
            position: absolute; top: 6px; right: 6px;
            width: 7px; height: 7px;
            background: #e53935; border-radius: 50%;
            border: 1.5px solid #fff;
        }
        .tb-date {
            background: var(--hijau-pale); color: var(--hijau);
            font-size: 11px; font-weight: 500;
            padding: 6px 12px; border-radius: 8px;
        }
        .tb-ham {
            display: none;
            width: 34px; height: 34px;
            border: none; border-radius: 8px;
            background: var(--hijau-pale);
            align-items: center; justify-content: center;
            cursor: pointer;
        }
        .tb-ham svg { width: 18px !important; height: 18px !important; color: var(--hijau); }

        /* Flash alerts */
        .flash-wrap { padding: 14px 22px 0; }
        .flash-success {
            background: var(--hijau-pale); color: var(--hijau);
            border: 1px solid var(--hijau-border);
            border-radius: 8px; padding: 10px 14px;
            font-size: 13px; display: flex; gap: 8px;
            align-items: flex-start;
        }
        .flash-success svg { width: 16px !important; height: 16px !important; flex-shrink: 0; margin-top: 1px; }
        .flash-warning {
            background: var(--emas-light); color: var(--emas-dark);
            border: 1px solid #e0c870;
            border-radius: 8px; padding: 10px 14px;
            font-size: 13px;
        }
        .flash-error {
            background: #fce8e8; color: #c62828;
            border: 1px solid #f0c0c0;
            border-radius: 8px; padding: 10px 14px;
            font-size: 13px;
        }

        /* Page content */
        .page-content { flex: 1; padding: 22px; }

        /* Page header */
        .page-hdr {
            display: flex; align-items: flex-start;
            justify-content: space-between;
            margin-bottom: 20px; flex-wrap: wrap; gap: 10px;
        }
        .page-hdr-title { font-size: 18px; font-weight: 800; color: var(--txt); }
        .page-hdr-sub   { font-size: 12px; color: var(--txt3); margin-top: 2px; }

        /* ══════════════════════════════════════════════════════
           CARDS
           ══════════════════════════════════════════════════════ */
        .card-c {
            background: var(--card);
            border: 1px solid var(--border-light);
            border-radius: var(--radius);
            box-shadow: 0 1px 3px rgba(0,0,0,.05);
        }
        .card-c-hdr {
            display: flex; align-items: center; justify-content: space-between;
            padding: 13px 18px;
            border-bottom: 1px solid var(--border-light);
        }
        .card-c-title { font-size: 13px; font-weight: 700; color: var(--txt); }
        .card-c-link  { font-size: 12px; color: var(--hijau); font-weight: 600; text-decoration: none; }
        .card-c-link:hover { text-decoration: underline; }
        .card-c-body  { padding: 16px 18px; }

        /* ══════════════════════════════════════════════════════
           STAT CARDS — ⚠️ FIX ICON SIZE
           ══════════════════════════════════════════════════════ */
        .stat-c {
            background: var(--card);
            border: 1px solid var(--border-light);
            border-radius: var(--radius);
            padding: 16px 18px;
            box-shadow: 0 1px 3px rgba(0,0,0,.05);
            transition: box-shadow .15s, transform .15s;
        }
        .stat-c:hover { box-shadow: 0 3px 10px rgba(0,0,0,.08); transform: translateY(-1px); }

        /* ⚠️ KUNCI: Icon wrapper WAJIB fixed size, SVG WAJIB fixed size */
        .stat-icon {
            width: 40px; height: 40px;
            border-radius: 9px;
            display: flex; align-items: center; justify-content: center;
            margin-bottom: 12px;
            flex-shrink: 0;
        }
        .stat-icon svg {
            width: 20px !important;
            height: 20px !important;
            display: block;
        }
        .stat-icon.green { background: var(--hijau-pale); }
        .stat-icon.gold  { background: var(--emas-light); }
        .stat-icon.blue  { background: #e8f0fe; }
        .stat-icon.red   { background: #fce8e8; }

        .stat-val  { font-size: 26px; font-weight: 800; color: var(--txt); line-height: 1; }
        .stat-lbl  { font-size: 12px; color: var(--txt3); margin-top: 4px; }
        .stat-chg  { font-size: 11px; margin-top: 5px; }
        .chg-up    { color: #1a7a3a; }
        .chg-warn  { color: var(--emas-dark); }
        .chg-down  { color: #c62828; }

        /* Welcome bar */
        .welcome-bar {
            background: var(--hijau);
            border-radius: var(--radius);
            padding: 18px 22px;
            margin-bottom: 20px;
            display: flex; align-items: center;
            justify-content: space-between;
        }
        .wb-hi   { color: rgba(255,255,255,.7); font-size: 12px; }
        .wb-name { color: #fff; font-size: 19px; font-weight: 800; margin-top: 2px; }
        .wb-info { color: rgba(255,255,255,.6); font-size: 12px; margin-top: 3px; }
        .wb-motto{ color: var(--emas); font-size: 11px; font-style: italic; text-align: right; max-width: 220px; line-height: 1.6; }

        /* ══════════════════════════════════════════════════════
           TABLE
           ══════════════════════════════════════════════════════ */
        .tbl { width: 100%; border-collapse: collapse; font-size: 13px; }
        .tbl thead th {
            background: #f4f7f4; color: var(--txt3);
            font-size: 10px; font-weight: 700;
            text-transform: uppercase; letter-spacing: .06em;
            padding: 9px 14px;
            border-bottom: 1px solid var(--border-light);
        }
        .tbl tbody td {
            padding: 10px 14px;
            border-bottom: 1px solid #f4f6f4;
            vertical-align: middle;
        }
        .tbl tbody tr:last-child td { border-bottom: none; }
        .tbl tbody tr:hover { background: #fafcfa; }

        /* Table avatar */
        .td-av {
            width: 30px; height: 30px; border-radius: 7px;
            background: var(--hijau-pale);
            display: inline-flex; align-items: center; justify-content: center;
            font-size: 10px; font-weight: 700; color: var(--hijau);
        }
        .td-name { font-size: 13px; font-weight: 600; color: var(--txt); }
        .td-sub  { font-size: 11px; color: var(--txt3); margin-top: 1px; }

        /* ══════════════════════════════════════════════════════
           BADGES
           ══════════════════════════════════════════════════════ */
        .bdg {
            display: inline-flex; align-items: center;
            font-size: 11px; font-weight: 700;
            padding: 3px 9px; border-radius: 20px;
        }
        .bdg-green  { background: var(--hijau-pale); color: var(--hijau); }
        .bdg-gold   { background: var(--emas-light);  color: var(--emas-dark); }
        .bdg-blue   { background: #e8f0fe; color: #1a3c8e; }
        .bdg-red    { background: #fce8e8; color: #c62828; }
        .bdg-purple { background: #f0e8fe; color: #6b1e9e; }
        .bdg-gray   { background: #f0f0f0; color: #5a5a6a; }

        .grade-a { background: var(--hijau-pale); color: var(--hijau); }
        .grade-b { background: #e8f0fe; color: #1a3c8e; }
        .grade-c { background: var(--emas-light); color: var(--emas-dark); }
        .grade-d { background: #fce8e8; color: #c62828; }

        .s-hadir { background: var(--hijau-pale); color: var(--hijau); }
        .s-izin  { background: var(--emas-light); color: var(--emas-dark); }
        .s-sakit { background: #e8f0fe; color: #1a3c8e; }
        .s-alpha { background: #fce8e8; color: #c62828; }

        /* ══════════════════════════════════════════════════════
           BUTTONS
           ══════════════════════════════════════════════════════ */
        .btn-hj {
            background: var(--hijau); color: #fff;
            border: none; border-radius: 8px;
            padding: 8px 16px; font-size: 13px; font-weight: 700;
            cursor: pointer; display: inline-flex;
            align-items: center; gap: 6px;
            text-decoration: none; font-family: inherit;
            transition: background .15s;
        }
        .btn-hj:hover { background: var(--hijau-mid); color: #fff; text-decoration: none; }
        .btn-hj svg { width: 15px !important; height: 15px !important; }

        .btn-ol-hj {
            background: transparent; color: var(--hijau);
            border: 1.5px solid var(--hijau);
            border-radius: 8px; padding: 7px 14px;
            font-size: 13px; font-weight: 600;
            cursor: pointer; display: inline-flex;
            align-items: center; gap: 6px;
            text-decoration: none; font-family: inherit;
            transition: all .15s;
        }
        .btn-ol-hj:hover { background: var(--hijau); color: #fff; text-decoration: none; }
        .btn-ol-hj svg { width: 15px !important; height: 15px !important; }

        .btn-edit  { background: #e8f0fe; color: #1a3c8e; border: 1px solid #c8d8fe; border-radius: 6px; padding: 4px 9px; font-size: 11px; font-weight: 700; cursor: pointer; border: none; font-family: inherit; }
        .btn-del   { background: #fce8e8; color: #c62828; border: 1px solid #f0c0c0; border-radius: 6px; padding: 4px 9px; font-size: 11px; font-weight: 700; cursor: pointer; border: none; font-family: inherit; }
        .btn-view  { background: var(--hijau-pale); color: var(--hijau); border-radius: 6px; padding: 4px 9px; font-size: 11px; font-weight: 700; text-decoration: none; display: inline-block; border: none; }
        .btn-edit:hover  { background: #1a3c8e; color: #fff; }
        .btn-del:hover   { background: #c62828; color: #fff; }
        .btn-view:hover  { background: var(--hijau); color: #fff; text-decoration: none; }

        /* ══════════════════════════════════════════════════════
           FORMS
           ══════════════════════════════════════════════════════ */
        .flbl { font-size: 12px; font-weight: 700; color: var(--txt2); margin-bottom: 5px; display: block; }
        .finp {
            width: 100%; height: 38px;
            border: 1.5px solid var(--border-light); border-radius: 8px;
            padding: 0 12px; font-size: 13px; color: var(--txt);
            background: #fafcfa; outline: none; font-family: inherit;
            transition: border-color .15s, box-shadow .15s;
        }
        .finp:focus { border-color: var(--hijau); box-shadow: 0 0 0 3px rgba(26,92,46,.1); }
        .finp.is-invalid { border-color: #dc3545; }
        textarea.finp { height: auto; padding: 8px 12px; resize: vertical; }
        select.finp   { cursor: pointer; }
        .fgrp { margin-bottom: 15px; }
        .ferr { font-size: 11px; color: #dc3545; margin-top: 4px; }

        /* ══════════════════════════════════════════════════════
           PROGRESS BAR
           ══════════════════════════════════════════════════════ */
        .prog-bar { height: 6px; background: #e8ece8; border-radius: 3px; overflow: hidden; }
        .prog-fill { height: 100%; background: var(--hijau); border-radius: 3px; transition: width .4s; }

        /* ══════════════════════════════════════════════════════
           PAGINATION
           ══════════════════════════════════════════════════════ */
        .pag-wrap {
            display: flex; align-items: center;
            justify-content: space-between;
            padding: 12px 18px;
            border-top: 1px solid var(--border-light);
        }
        .pag-info { font-size: 12px; color: var(--txt3); }
        .pag-btn {
            width: 30px; height: 30px;
            border: 1px solid var(--border-light); border-radius: 6px;
            display: flex; align-items: center; justify-content: center;
            font-size: 12px; color: var(--txt2);
            text-decoration: none; cursor: pointer;
            transition: all .15s;
        }
        .pag-btn:hover { background: var(--hijau-pale); color: var(--hijau); text-decoration: none; }
        .pag-btn.active { background: var(--hijau); color: #fff; border-color: var(--hijau); }

        /* ══════════════════════════════════════════════════════
           DONUT CHART HELPER
           ══════════════════════════════════════════════════════ */
        .donut-wrap  { display: flex; align-items: center; gap: 16px; }
        .donut-lgd   { display: flex; flex-direction: column; gap: 6px; }
        .donut-lgd-i { display: flex; align-items: center; gap: 7px; font-size: 12px; color: var(--txt2); }
        .donut-dot   { width: 9px; height: 9px; border-radius: 3px; flex-shrink: 0; }

        /* ══════════════════════════════════════════════════════
           RESPONSIVE — MOBILE
           ══════════════════════════════════════════════════════ */
        @media (max-width: 991px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.open { transform: translateX(0); box-shadow: 4px 0 20px rgba(0,0,0,.2); }
            .main-wrapper { margin-left: 0; }
            .sb-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,.4); z-index: 999; }
            .sb-overlay.show { display: block; }
            .tb-ham { display: flex !important; }
            .page-content { padding: 14px; }
            .stats-row { grid-template-columns: repeat(2, 1fr) !important; }
            .wb-motto { display: none; }
            .tb-date { display: none; }
        }
        @media (max-width: 575px) {
            .stats-row { grid-template-columns: repeat(2, 1fr) !important; }
            .page-hdr { gap: 8px; }
        }

        /* ══════════════════════════════════════════════════════
           FOOTER
           ══════════════════════════════════════════════════════ */
        .site-footer {
            text-align: center; padding: 14px;
            font-size: 11px; color: var(--txt3);
            background: #fff;
            border-top: 1px solid var(--border-light);
        }
        .site-footer strong { color: var(--hijau); }

        /* =====================================================
           Compatibility classes used across existing views
           (to keep UI consistent with prototype quickly)
           ===================================================== */
        .sidebar-section { padding: 10px 16px 3px; font-size: 9px; font-weight: 700; color: rgba(255,255,255,.35); letter-spacing: .1em; text-transform: uppercase; }
        .sidebar-item { display: flex; align-items: center; gap: 9px; padding: 9px 16px; font-size: 12px; color: rgba(255,255,255,.65); text-decoration: none; border-left: 3px solid transparent; transition: background .15s, color .15s; white-space: nowrap; }
        .sidebar-item:hover { background: rgba(255,255,255,.07); color: #fff; text-decoration: none; }
        .sidebar-item.active { background: rgba(255,255,255,.12); color: #fff; border-left-color: var(--emas); font-weight: 600; }
        .sidebar-item svg { width: 15px !important; height: 15px !important; flex-shrink: 0; }
        .sidebar-badge { margin-left: auto; background: var(--emas); color: var(--hijau); font-size: 9px; font-weight: 800; padding: 2px 6px; border-radius: 10px; min-width: 18px; text-align: center; }

        .welcome-bar .greeting { color: rgba(255,255,255,.75); font-size: 12px; }
        .welcome-bar .user-name { color: #fff; font-size: 20px; font-weight: 800; line-height: 1.15; }
        .welcome-bar .user-info { color: rgba(255,255,255,.65); font-size: 12px; margin-top: 2px; }
        .welcome-bar .motto { color: var(--emas-light); font-size: 11px; text-align: right; line-height: 1.4; }

        .page-header { display: flex; align-items: flex-start; justify-content: space-between; gap: 10px; flex-wrap: wrap; margin-bottom: 12px; }
        .page-header-title { font-size: 15px; color: var(--txt); font-weight: 800; }
        .page-header-sub { font-size: 11px; color: var(--txt3); margin-top: 2px; }

        .card-custom { background: var(--card); border: 1px solid var(--border-light); border-radius: var(--radius); box-shadow: 0 1px 3px rgba(0,0,0,.05); }
        .card-header-custom { display: flex; align-items: center; justify-content: space-between; gap: 10px; padding: 10px 14px; border-bottom: 1px solid var(--border-light); }
        .card-body-custom { padding: 12px 14px; }
        .card-title-custom { font-size: 12px; font-weight: 800; color: var(--txt); }
        .card-link { font-size: 11px; color: var(--hijau); font-weight: 700; text-decoration: none; }
        .card-link:hover { text-decoration: underline; }

        .stat-card { background: var(--card); border: 1px solid var(--border-light); border-radius: var(--radius); padding: 12px; box-shadow: 0 1px 3px rgba(0,0,0,.05); }
        .stat-card-icon { width: 34px; height: 34px; border-radius: 8px; display: flex; align-items: center; justify-content: center; margin-bottom: 8px; }
        .stat-card-icon svg { width: 18px !important; height: 18px !important; }
        .stat-card-icon.green { background: var(--hijau-pale); }
        .stat-card-icon.gold { background: var(--emas-light); }
        .stat-card-icon.blue { background: #e8f0fe; }
        .stat-card-icon.red { background: #fce8e8; }
        .stat-card-value { font-size: 26px; line-height: 1; font-weight: 800; color: var(--txt); }
        .stat-card-label { font-size: 11px; color: var(--txt3); margin-top: 4px; }
        .stat-card-change { font-size: 10px; margin-top: 4px; }
        .change-up { color: #1a7a3a; }
        .change-warn { color: var(--emas-dark); }
        .change-down { color: #c62828; }

        .table-custom { width: 100%; border-collapse: collapse; font-size: 12px; }
        .table-custom thead th { background: #f4f7f4; color: var(--txt3); font-size: 9px; font-weight: 800; text-transform: uppercase; letter-spacing: .05em; padding: 8px 10px; border-bottom: 1px solid var(--border-light); }
        .table-custom tbody td { padding: 8px 10px; border-bottom: 1px solid #f4f6f4; vertical-align: middle; }
        .table-custom tbody tr:last-child td { border-bottom: none; }
        .table-custom tbody tr:hover { background: #fafcfa; }

        .td-avatar { width: 26px; height: 26px; border-radius: 6px; background: var(--hijau-pale); color: var(--hijau); display: inline-flex; align-items: center; justify-content: center; font-size: 9px; font-weight: 800; }
        .td-name-main { font-size: 12px; font-weight: 700; color: var(--txt); line-height: 1.2; }
        .td-name-sub { font-size: 10px; color: var(--txt3); line-height: 1.2; margin-top: 1px; }

        .badge-custom { display: inline-flex; align-items: center; justify-content: center; font-size: 10px; font-weight: 700; padding: 3px 8px; border-radius: 999px; }
        .badge-green { background: var(--hijau-pale); color: var(--hijau); }
        .badge-gold { background: var(--emas-light); color: var(--emas-dark); }
        .badge-blue { background: #e8f0fe; color: #1a3c8e; }
        .badge-red { background: #fce8e8; color: #c62828; }
        .badge-purple { background: #f0e8fe; color: #6b1e9e; }
        .badge-teal { background: #e0f7f4; color: #00695c; }
        .badge-gray { background: #f0f0f0; color: #5a5a6a; }

        .btn-hijau { background: var(--hijau); color: #fff; border: none; border-radius: 7px; padding: 8px 12px; font-size: 11px; font-weight: 700; display: inline-flex; align-items: center; gap: 6px; text-decoration: none; }
        .btn-hijau:hover { color: #fff; text-decoration: none; background: var(--hijau-mid); }
        .btn-outline-hijau { background: #fff; color: var(--hijau); border: 1px solid var(--hijau); border-radius: 7px; padding: 7px 11px; font-size: 11px; font-weight: 700; text-decoration: none; display: inline-flex; align-items: center; gap: 6px; }
        .btn-outline-hijau:hover { background: var(--hijau); color: #fff; text-decoration: none; }
        .btn-view-custom { background: var(--hijau-pale); color: var(--hijau); border-radius: 6px; padding: 3px 8px; font-size: 10px; font-weight: 700; text-decoration: none; }
        .btn-edit-custom { background: #e8f0fe; color: #1a3c8e; border-radius: 6px; padding: 3px 8px; font-size: 10px; font-weight: 700; text-decoration: none; border: none; }
        .btn-danger-custom { background: #fce8e8; color: #c62828; border-radius: 6px; padding: 3px 8px; font-size: 10px; font-weight: 700; border: none; }

        .form-group-custom { margin-bottom: 10px; }
        .form-label-custom { font-size: 11px; color: var(--txt2); font-weight: 700; margin-bottom: 4px; display: block; }
        .form-control-custom { width: 100%; height: 32px; border: 1px solid var(--border-light); border-radius: 6px; background: #fafcfa; color: var(--txt); font-size: 12px; padding: 0 10px; outline: none; }
        textarea.form-control-custom { height: auto; padding: 8px 10px; }
        .form-control-custom:focus { border-color: var(--hijau); box-shadow: 0 0 0 2px rgba(26,92,46,.08); }
        .form-error { color: #dc3545; font-size: 10px; margin-top: 3px; }

        .progress-hijau { height: 6px; border-radius: 999px; background: #e8ece8; overflow: hidden; }
        .progress-fill { height: 100%; background: var(--hijau); border-radius: 999px; transition: width .25s; }

        .pagination-custom { display: flex; align-items: center; justify-content: space-between; gap: 10px; border-top: 1px solid var(--border-light); padding: 10px 14px; }
        .pagination-info { font-size: 11px; color: var(--txt3); }
        .page-link { min-width: 24px; height: 24px; border: 1px solid var(--border-light); border-radius: 6px; display: inline-flex; align-items: center; justify-content: center; font-size: 10px; font-weight: 700; color: var(--txt2); text-decoration: none; background: #fff; }
        .page-link.active, .page-link:hover { background: var(--hijau); border-color: var(--hijau); color: #fff; }

        .donut-wrapper { display: flex; align-items: center; gap: 14px; }
        .donut-legend { display: flex; flex-direction: column; gap: 4px; }
        .donut-legend-item { display: flex; align-items: center; gap: 6px; color: var(--txt2); font-size: 11px; }
        .donut-legend-dot { width: 8px; height: 8px; border-radius: 2px; flex-shrink: 0; }

        .alert-emas { border: 1px solid #e0c870; background: var(--emas-light); color: var(--emas-dark); border-radius: 8px; padding: 9px 11px; font-size: 12px; display: flex; gap: 8px; }
        .alert-danger { border: 1px solid #f0c0c0; background: #fce8e8; color: #7a1a1a; }
    </style>

    @stack('styles')
</head>
<body>

{{-- Sidebar overlay (mobile) --}}
<div class="sb-overlay" id="sbOverlay"></div>

{{-- ══════════════════ SIDEBAR ══════════════════ --}}
<aside class="sidebar" id="sidebar">

    <a href="{{ url('/') }}" class="sb-logo">
        <div class="sb-logo-icon">AS</div>
        <div>
            <div class="sb-logo-name">IBS Ash-Shiddiiqi</div>
            <div class="sb-logo-sub">Monitoring Santri</div>
        </div>
    </a>

    <div class="sb-user">
        @auth
            <div class="sb-avatar">{{ strtoupper(substr(auth()->user()->name ?? 'NA', 0, 2)) }}</div>
            <div>
                <div class="sb-uname">{{ Str::limit(auth()->user()->name ?? 'NA', 20) }}</div>
                <div class="sb-urole">{{ auth()->user()->role_label ?? '-' }}</div>
            </div>
        @else
            <div class="sb-avatar">NA</div>
            <div>
                <div class="sb-uname">Guest</div>
                <div class="sb-urole">-</div>
            </div>
        @endauth
    </div>

    <nav class="sb-nav">
        @auth
            @if(auth()->user()->isAdmin())
                @include('layouts.partials.nav-admin')
            @elseif(auth()->user()->isUstadz())
                @include('layouts.partials.nav-ustadz')
            @else
                @include('layouts.partials.nav-wali')
            @endif
        @endauth
    </nav>

    <div class="sb-footer">
        @auth
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="sb-logout">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4"/>
                        <polyline points="16 17 21 12 16 7"/>
                        <line x1="21" y1="12" x2="9" y2="12"/>
                    </svg>
                    Keluar dari Sistem
                </button>
            </form>
        @endauth
    </div>
</aside>

{{-- ══════════════════ MAIN ══════════════════ --}}
<div class="main-wrapper">

    <header class="topbar">
        <div class="d-flex align-items-center gap-3">
            <button class="tb-ham" id="tbHam">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="3" y1="6" x2="21" y2="6"/>
                    <line x1="3" y1="12" x2="21" y2="12"/>
                    <line x1="3" y1="18" x2="21" y2="18"/>
                </svg>
            </button>
            <div>
                <div class="tb-title">@yield('page-title','Dashboard')</div>
                @php
                    $bcRaw = trim($__env->yieldContent('breadcrumb'));
                    $bcText = $bcRaw !== '' ? strip_tags(html_entity_decode($bcRaw, ENT_QUOTES | ENT_HTML5, 'UTF-8')) : '';
                @endphp
                <div class="tb-bc">Beranda @if($bcText !== ''){{ $bcText }}@endif</div>
            </div>
        </div>
        <div class="tb-right">
            @auth
                @if(auth()->user()->isAdmin())
                    @php $izinPending = \App\Models\Izin::menunggu()->count(); @endphp
                    @if($izinPending > 0)
                        <a href="{{ route('admin.izin.index') }}" class="tb-notif" title="{{ $izinPending }} izin menunggu">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M18 8A6 6 0 006 8c0 7-3 9-3 9h18s-3-2-3-9"/>
                                <path d="M13.73 21a2 2 0 01-3.46 0"/>
                            </svg>
                            <span class="tb-notif-dot"></span>
                        </a>
                    @endif
                @endif
            @endauth
            <div class="tb-date d-none d-md-block">
                {{ \Carbon\Carbon::now()->locale('id')->isoFormat('dddd, D MMMM Y') }}
            </div>
        </div>
    </header>

    {{-- Flash Messages --}}
    @if(session('success') || session('warning') || session('error'))
        <div class="flash-wrap">
            @if(session('success'))
                <div class="flash-success mb-2" id="flashMsg">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M22 11.08V12a10 10 0 11-5.93-9.14"/>
                        <polyline points="22 4 12 14.01 9 11.01"/>
                    </svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif
            @if(session('warning'))
                <div class="flash-warning mb-2" id="flashMsg">{{ session('warning') }}</div>
            @endif
            @if(session('error'))
                <div class="flash-error mb-2" id="flashMsg">{{ session('error') }}</div>
            @endif
        </div>
    @endif

    <main class="page-content">
        @yield('content')
    </main>

    <footer class="site-footer">
        &copy; {{ date('Y') }} IBS Ash-Shiddiiqi Jambi &nbsp;·&nbsp;
        Sistem Monitoring Santri &nbsp;·&nbsp;
        Oleh <strong>Dwi Abdi Putra</strong> — Universitas Jambi
    </footer>
</div>

{{-- Bootstrap JS --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
{{-- Chart.js --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

<script>
// Sidebar toggle
const sidebar = document.getElementById('sidebar');
const overlay = document.getElementById('sbOverlay');
document.getElementById('tbHam')?.addEventListener('click', () => {
    sidebar.classList.toggle('open');
    overlay.classList.toggle('show');
});
overlay?.addEventListener('click', () => {
    sidebar.classList.remove('open');
    overlay.classList.remove('show');
});
window.addEventListener('resize', () => {
    if (window.innerWidth >= 992) {
        sidebar.classList.remove('open');
        overlay.classList.remove('show');
    }
});

// Auto-hide flash
setTimeout(() => {
    document.querySelectorAll('#flashMsg').forEach(el => {
        el.style.transition = 'opacity .5s';
        el.style.opacity = '0';
        setTimeout(() => el.remove(), 500);
    });
}, 4000);

// CSRF token global
window.csrfToken = '{{ csrf_token() }}';
</script>

@stack('scripts')
</body>
</html>
