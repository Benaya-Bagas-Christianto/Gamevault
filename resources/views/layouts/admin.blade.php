<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('page_title', 'Admin') — GameVault Admin</title>

    {{-- Favicon --}}
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}?v={{ time() }}">

    {{-- Font Awesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />

    {{-- Google Fonts --}}
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    {{-- App CSS --}}
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    @stack('styles')

    <style>
        /* =============================================
           ADMIN LAYOUT — NVIDIA-Inspired Design System
           ============================================= */
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --nvidia-green:   #76b900;
            --nvidia-green-d: #5a8f00;
            --nvidia-green-l: #a0d400;
            --bg-sidebar:     #000000;
            --bg-sidebar-sec: #090909;
            --bg-content:     #000000;
            --bg-topbar:      #000000;
            --text-primary:   #f3f4f6;
            --text-light:     #9ca3af;
            --text-sidebar:   #c8c8c8;
            --border-color:   #1f1f1f;
            --card-bg:        #000000;
            --shadow-sm:      0 1px 3px rgba(0,0,0,.3);
            --shadow-md:      0 4px 16px rgba(0,0,0,.4);
            --shadow-lg:      0 8px 32px rgba(0,0,0,.5);
            --radius-sm:      2px;
            --radius-md:      2px;
            --radius-lg:      2px;
            --sidebar-w:      260px;
            --topbar-h:       64px;
            --transition:     all .2s cubic-bezier(.4,0,.2,1);
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--bg-content);
            color: var(--text-primary);
            min-height: 100vh;
        }

        /* ---- LAYOUT SHELL ---- */
        .admin-layout {
            display: flex;
            min-height: 100vh;
        }

        /* ---- SIDEBAR ---- */
        .admin-sidebar {
            width: var(--sidebar-w);
            background: var(--bg-sidebar);
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0; left: 0; bottom: 0;
            z-index: 200;
            transition: var(--transition);
            overflow-y: auto;
            scrollbar-width: thin;
            scrollbar-color: #333 transparent;
        }
        .admin-sidebar::-webkit-scrollbar { width: 4px; }
        .admin-sidebar::-webkit-scrollbar-track { background: transparent; }
        .admin-sidebar::-webkit-scrollbar-thumb { background: #333; border-radius: 0; }

        .sidebar-brand {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 20px 22px;
            border-bottom: 1px solid #1a1a1a;
            text-decoration: none;
        }
        .sidebar-brand-icon {
            width: 36px; height: 36px;
            background: transparent;
            border-radius: 0;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
        }
        .sidebar-brand-icon i { color: #000; font-size: 16px; }
        .sidebar-brand-text {
            display: flex; flex-direction: column;
        }
        .sidebar-brand-title {
            font-size: 1.2rem; font-weight: 800;
            color: #ffffff;
            letter-spacing: .5px;
            line-height: 1.2;
        }
        .sidebar-brand-sub {
            font-size: 10px; font-weight: 600;
            color: var(--nvidia-green);
            letter-spacing: 2px;
            text-transform: uppercase;
        }

        .sidebar-section {
            padding: 20px 0 8px;
        }
        .sidebar-section-label {
            font-size: 10px; font-weight: 700;
            letter-spacing: 2px; text-transform: uppercase;
            color: #444;
            padding: 0 22px 8px;
        }

        .sidebar-nav {
            list-style: none;
            padding: 0 12px;
        }
        .sidebar-nav li { margin-bottom: 2px; }

        .sidebar-nav a {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 12px;
            border-radius: 0;
            color: var(--text-sidebar);
            text-decoration: none;
            font-size: 13.5px;
            font-weight: 500;
            transition: var(--transition);
            position: relative;
            overflow: hidden;
        }
        .sidebar-nav a .nav-icon {
            width: 20px; text-align: center;
            font-size: 14px;
            flex-shrink: 0;
            opacity: .7;
            transition: var(--transition);
        }
        .sidebar-nav a:hover {
            background: rgba(118,185,0,.12);
            color: #fff;
        }
        .sidebar-nav a:hover .nav-icon { opacity: 1; color: var(--nvidia-green); }
        .sidebar-nav a.active {
            background: rgba(118,185,0,.18);
            color: var(--nvidia-green);
            font-weight: 600;
        }
        .sidebar-nav a.active::before {
            content: '';
            position: absolute; left: 0; top: 4px; bottom: 4px;
            width: 3px;
            background: var(--nvidia-green);
            border-radius: 0;
        }
        .sidebar-nav a.active .nav-icon { opacity: 1; }

        .sidebar-nav a .nav-badge {
            margin-left: auto;
            background: #e53e3e;
            color: #fff;
            font-size: 10px; font-weight: 700;
            padding: 2px 7px;
            border-radius: 0;
            min-width: 20px; text-align: center;
        }

        .sidebar-bottom {
            margin-top: auto;
            padding: 16px 12px;
            border-top: 1px solid #1a1a1a;
        }
        .sidebar-bottom a {
            display: flex; align-items: center; gap: 10px;
            padding: 10px 12px;
            border-radius: 0;
            color: #666;
            text-decoration: none;
            font-size: 13px;
            font-weight: 500;
            transition: var(--transition);
        }
        .sidebar-bottom a:hover { background: rgba(118,185,0,.12); color: var(--nvidia-green); }

        /* ---- MAIN AREA ---- */
        .admin-content {
            margin-left: var(--sidebar-w);
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        /* ---- TOP BAR ---- */
        .admin-topbar {
            height: var(--topbar-h);
            background: var(--bg-topbar);
            border-bottom: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 28px;
            position: sticky; top: 0;
            z-index: 100;
            box-shadow: var(--shadow-sm);
        }
        .topbar-title {
            font-size: 20px; font-weight: 700;
            color: var(--text-primary);
            display: flex; align-items: center; gap: 10px;
        }
        .topbar-title .breadcrumb-sep { color: #ccc; font-weight: 300; }
        .topbar-title .page-name { color: var(--text-primary); }

        .topbar-right {
            display: flex; align-items: center; gap: 16px;
        }
        .topbar-icon-btn {
            width: 38px; height: 38px;
            border-radius: 0;
            border: 1.5px solid var(--border-color);
            background: transparent;
            display: flex; align-items: center; justify-content: center;
            cursor: pointer;
            color: var(--text-light);
            transition: var(--transition);
            position: relative;
            text-decoration: none;
        }
        .topbar-icon-btn:hover { border-color: var(--nvidia-green); color: var(--nvidia-green); background: rgba(118,185,0,.08); }

        .topbar-admin {
            display: flex; align-items: center; gap: 10px;
            padding: 6px 14px 6px 6px;
            border: 1.5px solid var(--border-color);
            border-radius: 0;
            background: #252525;
            cursor: pointer;
            transition: var(--transition);
        }
        .topbar-admin:hover { border-color: var(--nvidia-green); background: rgba(118,185,0,.08); }
        .topbar-avatar {
            width: 32px; height: 32px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--nvidia-green), var(--nvidia-green-d));
            display: flex; align-items: center; justify-content: center;
            color: #000; font-weight: 800; font-size: 13px;
            flex-shrink: 0; overflow: hidden;
        }
        .topbar-admin-name { font-size: 13px; font-weight: 600; color: var(--text-primary); }
        .topbar-admin-role { font-size: 10px; color: var(--nvidia-green); font-weight: 600; letter-spacing: .5px; text-transform: uppercase; }

        /* ---- MAIN CONTENT ---- */
        .admin-main {
            flex: 1;
            padding: 28px;
        }

        /* ---- STAT CARDS ---- */
        .stat-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 20px;
            margin-bottom: 28px;
        }
        .stat-card {
            background: var(--card-bg);
            border-radius: 0;
            padding: 22px 24px;
            box-shadow: var(--shadow-sm);
            border: 1px solid var(--border-color);
            display: flex; align-items: center; gap: 16px;
            position: relative; overflow: hidden;
            transition: var(--transition);
        }
        .stat-card:hover { box-shadow: var(--shadow-md); transform: translateY(-2px); }
        .stat-card::after {
            content: '';
            position: absolute; bottom: 0; left: 0; right: 0;
            height: 3px;
            background: var(--card-accent, var(--nvidia-green));
        }
        .stat-icon {
            width: 52px; height: 52px; border-radius: 0;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0; font-size: 20px;
            background: var(--icon-bg, rgba(118,185,0,.12));
            color: var(--icon-color, var(--nvidia-green));
        }
        .stat-info { flex: 1; }
        .stat-label { font-size: 12px; font-weight: 600; color: var(--text-light); text-transform: uppercase; letter-spacing: .5px; }
        .stat-value { font-size: 26px; font-weight: 800; color: var(--text-primary); line-height: 1.2; margin-top: 2px; }
        .stat-sub { font-size: 11px; color: var(--text-light); margin-top: 2px; }

        /* ---- CARDS & SECTIONS ---- */
        .admin-card {
            background: var(--card-bg);
            border-radius: 0;
            border: 1px solid var(--border-color);
            box-shadow: var(--shadow-sm);
            overflow: hidden;
            margin-bottom: 24px;
        }
        .admin-card-header {
            display: flex; align-items: center; justify-content: space-between;
            padding: 18px 22px;
            border-bottom: 1px solid var(--border-color);
        }
        .admin-card-title {
            font-size: 15px; font-weight: 700; color: var(--text-primary);
            display: flex; align-items: center; gap: 8px;
        }
        .admin-card-title i { color: var(--nvidia-green); }
        .admin-card-body { padding: 22px; }

        /* ---- TABLE ---- */
        .table-wrap { overflow-x: auto; }
        .table-wrap table {
            width: 100%; border-collapse: collapse;
            font-size: 13.5px;
        }
        .table-wrap thead th {
            background: #252525;
            padding: 11px 14px;
            text-align: left;
            font-size: 11px; font-weight: 700;
            color: var(--text-light);
            text-transform: uppercase; letter-spacing: .8px;
            border-bottom: 2px solid var(--border-color);
            white-space: nowrap;
        }
        .table-wrap tbody td {
            padding: 13px 14px;
            border-bottom: 1px solid var(--border-color);
            color: var(--text-primary);
            vertical-align: middle;
        }
        .table-wrap tbody tr:last-child td { border-bottom: none; }
        .table-wrap tbody tr:hover td { background: rgba(118,185,0,.08); }
        .table-wrap tbody tr.accordion-row td { padding: 0; background: #1f1f1f; }

        /* ---- STATUS BADGES ---- */
        .status-badge {
            display: inline-flex; align-items: center; gap: 5px;
            padding: 4px 10px; border-radius: 0;
            font-size: 11px; font-weight: 700;
            letter-spacing: .3px; white-space: nowrap;
        }
        .status-badge::before { content: ''; width: 6px; height: 6px; border-radius: 0; background: currentColor; opacity: .7; }
        .status-paid   { background: rgba(16,185,129,.12); color: #059669; }
        .status-pending{ background: rgba(245,158,11,.12);  color: #d97706; }
        .status-failed { background: rgba(239,68,68,.12);   color: #dc2626; }
        .status-approved { background: rgba(16,185,129,.12); color: #059669; }
        .status-rejected { background: rgba(239,68,68,.12);  color: #dc2626; }
        .status-active   { background: rgba(118,185,0,.12);  color: var(--nvidia-green-d); }
        .status-inactive { background: rgba(107,114,128,.1); color: #6b7280; }

        /* ---- BUTTONS ---- */
        .btn-primary {
            background: var(--nvidia-green);
            color: #000 !important;
            border: none; border-radius: 0;
            padding: 9px 18px; font-size: 13px; font-weight: 700;
            cursor: pointer; display: inline-flex; align-items: center; gap: 7px;
            text-decoration: none; transition: var(--transition);
            white-space: nowrap;
        }
        .btn-primary:hover { background: var(--nvidia-green-d); transform: translateY(-1px); box-shadow: 0 4px 12px rgba(118,185,0,.35); }

        .btn-outline {
            background: transparent;
            color: var(--text-primary);
            border: 1.5px solid var(--border-color);
            border-radius: 0;
            padding: 8px 16px; font-size: 13px; font-weight: 600;
            cursor: pointer; display: inline-flex; align-items: center; gap: 7px;
            text-decoration: none; transition: var(--transition);
        }
        .btn-outline:hover { border-color: var(--nvidia-green); color: var(--nvidia-green); background: rgba(118,185,0,.06); }

        .btn-danger {
            background: transparent;
            color: #dc2626;
            border: 1.5px solid #fecaca;
            border-radius: 0;
            padding: 7px 14px; font-size: 12.5px; font-weight: 600;
            cursor: pointer; display: inline-flex; align-items: center; gap: 6px;
            text-decoration: none; transition: var(--transition);
        }
        .btn-danger:hover { background: #dc2626; color: #fff; border-color: #dc2626; }

        .btn-sm { padding: 5px 11px !important; font-size: 12px !important; }
        .btn-icon { padding: 7px 10px !important; }

        .action-group { display: flex; align-items: center; gap: 6px; }

        /* ---- FORMS ---- */
        .form-group { margin-bottom: 20px; }
        .form-label {
            display: block; font-size: 13px; font-weight: 600;
            color: var(--text-primary); margin-bottom: 7px;
        }
        .form-label span.req { color: #dc2626; margin-left: 3px; }
        .form-control {
            width: 100%; padding: 10px 14px;
            border: 1.5px solid var(--border-color);
            border-radius: 0;
            font-size: 13.5px; font-family: inherit;
            color: var(--text-primary);
            background: #252525;
            transition: var(--transition);
            outline: none;
        }
        .form-control:focus { border-color: var(--nvidia-green); box-shadow: 0 0 0 3px rgba(118,185,0,.12); }
        .form-control::placeholder { color: #6b7280; }
        textarea.form-control { resize: vertical; min-height: 110px; }

        .form-row { display: grid; gap: 20px; }
        .form-row.cols-2 { grid-template-columns: 1fr 1fr; }
        .form-row.cols-3 { grid-template-columns: 1fr 1fr 1fr; }

        .form-error { font-size: 12px; color: #dc2626; margin-top: 5px; }

        .form-check { display: flex; align-items: center; gap: 8px; }
        .form-check input[type="checkbox"] {
            width: 17px; height: 17px; accent-color: var(--nvidia-green); cursor: pointer;
        }
        .form-check label { font-size: 13.5px; font-weight: 500; cursor: pointer; }

        /* Filter bar */
        .filter-bar {
            display: flex; align-items: center; gap: 12px; flex-wrap: wrap;
            padding: 16px 20px;
            background: #1a1a1a;
            border-bottom: 1px solid var(--border-color);
        }
        .filter-bar .form-control { width: auto; flex: 1; min-width: 180px; }
        .filter-bar select.form-control { width: auto; min-width: 140px; flex: 0 0 auto; }

        /* ---- ALERT ---- */
        .alert {
            padding: 13px 18px; border-radius: 0;
            font-size: 13.5px; font-weight: 500; margin-bottom: 20px;
            display: flex; align-items: center; gap: 10px; border: 1px solid transparent;
        }
        .alert-warning { background: rgba(245,158,11,.1); border-color: rgba(245,158,11,.3); color: #92400e; }
        .alert-success { background: rgba(16,185,129,.1); border-color: rgba(16,185,129,.3); color: #065f46; }
        .alert-danger  { background: rgba(239,68,68,.1);  border-color: rgba(239,68,68,.3);  color: #7f1d1d; }
        .alert a { color: inherit; font-weight: 700; }

        /* ---- ACCORDION ---- */
        .accordion-toggle { cursor: pointer; user-select: none; }
        .accordion-content { display: none; padding: 14px 22px; background: #1a1a1a; border-top: 1px solid var(--border-color); }
        .accordion-content.open { display: block; }

        /* ---- COVER PREVIEW ---- */
        .cover-preview-wrap {
            width: 120px; height: 160px;
            border: 2px dashed var(--border-color);
            border-radius: 0;
            overflow: hidden; display: flex; align-items: center; justify-content: center;
            background: #111; cursor: pointer; position: relative;
        }
        .cover-preview-wrap img { width: 100%; height: 100%; object-fit: cover; }
        .cover-preview-placeholder { color: #666; text-align: center; font-size: 12px; padding: 10px; }

        /* ---- GENRE CHECKBOXES ---- */
        .genre-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
            gap: 8px;
        }
        .genre-item {
            display: flex; align-items: center; gap: 8px;
            padding: 8px 12px;
            border: 1.5px solid var(--border-color);
            border-radius: 0;
            cursor: pointer; transition: var(--transition);
        }
        .genre-item:hover { border-color: var(--nvidia-green); background: rgba(118,185,0,.06); }
        .genre-item input[type="checkbox"]:checked + label { color: var(--nvidia-green-d); font-weight: 600; }
        .genre-item input[type="checkbox"] { accent-color: var(--nvidia-green); }
        .genre-item label { font-size: 13px; cursor: pointer; }

        /* ---- SECTION TITLE ---- */
        .section-title {
            font-size: 16px; font-weight: 700; color: var(--text-primary);
            margin-bottom: 16px; display: flex; align-items: center; gap: 8px;
        }
        .section-title i { color: var(--nvidia-green); }
        .section-divider {
            border: none; border-top: 2px solid var(--border-color); margin: 30px 0;
        }

        /* ---- PAGE HEADER ---- */
        .page-header {
            display: flex; align-items: center; justify-content: space-between;
            margin-bottom: 24px; flex-wrap: wrap; gap: 12px;
        }
        .page-header-left h1 { font-size: 22px; font-weight: 800; }
        .page-header-left p { font-size: 13px; color: var(--text-light); margin-top: 3px; }

        /* ---- USER CARD ---- */
        .user-info-card {
            background: linear-gradient(135deg, #0a0a0a 0%, #1a1a1a 100%);
            border-radius: 0;
            padding: 28px;
            display: flex; align-items: center; gap: 22px;
            margin-bottom: 24px; color: #fff;
        }
        .user-avatar-lg {
            width: 72px; height: 72px; border-radius: 0;
            background: linear-gradient(135deg, var(--nvidia-green), var(--nvidia-green-d));
            display: flex; align-items: center; justify-content: center;
            font-size: 28px; font-weight: 900; color: #000;
            flex-shrink: 0;
        }
        .user-info-card h2 { font-size: 20px; font-weight: 800; }
        .user-info-card p { font-size: 13px; color: #aaa; margin-top: 3px; }
        .user-info-meta { display: flex; gap: 20px; margin-top: 10px; flex-wrap: wrap; }
        .user-meta-item { font-size: 12px; color: #888; }
        .user-meta-item strong { color: #fff; display: block; font-size: 14px; }

        /* ---- ACCORDION SECTIONS ---- */
        .acc-section { border: 1px solid var(--border-color); border-radius: 0; margin-bottom: 12px; overflow: hidden; }
        .acc-header {
            padding: 16px 20px; background: #1c1c1c; cursor: pointer;
            display: flex; align-items: center; justify-content: space-between;
            font-weight: 600; font-size: 14px;
            transition: var(--transition);
        }
        .acc-header:hover { background: rgba(118,185,0,.08); }
        .acc-header .acc-toggle-icon { transition: transform .3s; color: var(--text-light); }
        .acc-header.open .acc-toggle-icon { transform: rotate(180deg); }
        .acc-body { 
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.5s ease-in-out, opacity 0.4s ease-in-out; 
            background: #090909; 
            opacity: 0;
            border-top: 0px solid transparent;
        }
        .acc-body.open { 
            max-height: 1200px;
            opacity: 1; 
            border-top: 1px solid var(--border-color); 
        }

        /* ---- PAGINATION ---- */
        .pagination { display: flex; gap: 4px; padding: 20px 22px; justify-content: flex-end; list-style: none; margin: 0; }
        .pagination .page-item .page-link {
            display: flex; align-items: center; justify-content: center;
            width: 34px; height: 34px; border-radius: 0;
            border: 1.5px solid var(--border-color);
            font-size: 13px; font-weight: 600; color: var(--text-primary);
            text-decoration: none; transition: var(--transition);
        }
        .pagination .page-item.active .page-link { background: var(--nvidia-green); border-color: var(--nvidia-green); color: #000; }
        .pagination .page-item .page-link:hover:not(.active) { border-color: var(--nvidia-green); color: var(--nvidia-green); }
        .pagination .page-item.disabled .page-link { opacity: .4; cursor: not-allowed; }

        /* ---- TOAST ---- */
        .toast-container {
            position: fixed; bottom: 24px; right: 24px;
            z-index: 9999; display: flex; flex-direction: column; gap: 10px;
        }
        .toast {
            min-width: 280px; max-width: 380px;
            background: #252525; border-radius: 0;
            box-shadow: 0 8px 30px rgba(0,0,0,.5);
            border-left: 4px solid var(--nvidia-green);
            padding: 14px 18px; display: flex; align-items: flex-start; gap: 12px;
            animation: slideUp .3s ease;
        }
        @media (max-width: 900px) {
            .toast-container {
                bottom: 16px; right: 16px; left: 16px;
            }
            .toast {
                min-width: 0; max-width: 100%; width: 100%;
            }
        }
        .toast.toast-error { border-left-color: #dc2626; }
        .toast.toast-warning { border-left-color: #d97706; }
        .toast-icon { font-size: 18px; margin-top: 1px; flex-shrink: 0; }
        .toast-success .toast-icon { color: var(--nvidia-green); }
        .toast-error .toast-icon { color: #dc2626; }
        .toast-warning .toast-icon { color: #d97706; }
        .toast-body { flex: 1; }
        .toast-title { font-size: 13px; font-weight: 700; color: var(--text-primary); }
        .toast-msg { font-size: 12.5px; color: var(--text-light); margin-top: 2px; }
        .toast-close { background: none; border: none; cursor: pointer; color: #ccc; font-size: 14px; padding: 0; transition: var(--transition); }
        .toast-close:hover { color: #666; }
        @keyframes slideUp { from { opacity: 0; transform: translateY(16px); } to { opacity: 1; transform: translateY(0); } }
        @keyframes slideOut { to { opacity: 0; transform: translateY(16px); } }

        @media (max-width: 600px) {
            .toast-container {
                left: 16px;
                right: 16px;
                bottom: 16px;
                align-items: stretch;
            }
            .toast {
                min-width: 0;
                max-width: none;
            }
        }

        /* ---- MODAL ---- */
        .modal-overlay {
            position: fixed; inset: 0; background: rgba(0,0,0,.8);
            z-index: 1000; display: none; align-items: center; justify-content: center;
        }
        .modal-overlay.open { display: flex; }
        .modal-box {
            background: #1c1c1c; border-radius: 0;
            padding: 30px; max-width: 420px; width: 90%;
            box-shadow: var(--shadow-lg); animation: modalIn .2s ease;
            border: 1px solid var(--border-color);
        }
        @keyframes modalIn { from { opacity: 0; transform: scale(.95); } to { opacity: 1; transform: scale(1); } }
        .modal-icon { width: 56px; height: 56px; border-radius: 0; background: rgba(239,68,68,.1); margin: 0 auto 16px; display: flex; align-items: center; justify-content: center; }
        .modal-icon i { font-size: 24px; color: #dc2626; }
        .modal-title { font-size: 18px; font-weight: 800; text-align: center; margin-bottom: 8px; }
        .modal-desc { font-size: 13.5px; color: var(--text-light); text-align: center; margin-bottom: 22px; }
        .modal-actions { display: flex; gap: 10px; justify-content: center; }

        /* ---- MISC ---- */
        .text-muted { color: var(--text-light); }
        .text-green { color: var(--nvidia-green); font-weight: 700; }
        .text-bold { font-weight: 700; }
        .img-cover { width: 72px; height: 40px; object-fit: cover; border-radius: 0; border: 1px solid var(--border-color); }
        .img-avatar { width: 32px; height: 32px; border-radius: 0; object-fit: cover; }
        .avatar-initial {
            width: 32px; height: 32px; border-radius: 0;
            background: linear-gradient(135deg, var(--nvidia-green), var(--nvidia-green-d));
            display: inline-flex; align-items: center; justify-content: center;
            color: #000; font-weight: 800; font-size: 12px;
        }
        .rank-badge {
            width: 24px; height: 24px; border-radius: 0;
            display: inline-flex; align-items: center; justify-content: center;
            font-size: 11px; font-weight: 800; background: #f0f0f0; color: #555;
        }
        .rank-badge.gold { background: #fbbf24; color: #7c2d12; }
        .rank-badge.silver { background: #9ca3af; color: #fff; }
        .rank-badge.bronze { background: #b45309; color: #fff; }
        .chart-wrap { position: relative; height: 280px; }
        .empty-state { text-align: center; padding: 40px 20px; color: var(--text-light); display: flex; flex-direction: column; align-items: center; justify-content: center; }
        .empty-state i { font-size: 36px; color: #ddd; margin-bottom: 12px; }
        .empty-state p { font-size: 13.5px; margin: 0; }

        @media (max-width: 900px) {
            .admin-sidebar { transform: translateX(-100%); z-index: 2000; }
            .admin-sidebar.open { transform: translateX(0); }
            .admin-content { margin-left: 0; }
            .admin-topbar { padding: 0 16px; }
            .admin-main { padding: 16px; }
            .admin-card-header { padding: 16px; }
            .admin-card-body { padding: 16px; }
            .page-header { margin-bottom: 16px; flex-direction: column; align-items: stretch; gap: 12px; }
            .page-header .btn-primary { width: 100%; justify-content: center; padding: 12px; }
            .form-row.cols-2, .form-row.cols-3 { grid-template-columns: 1fr; }
            .form-layout-split { grid-template-columns: 1fr !important; }

            /* ---- GLOBAL: Hide desktop table, show mobile cards ---- */
            .desktop-table-wrap { display: none !important; }
            .mobile-cards-wrap  { display: block !important; }

            .topbar-title .page-name { display: none; }
            .topbar-title .breadcrumb-sep { display: none; }
        }
        @media (min-width: 901px) {
            .mobile-cards-wrap { display: none !important; }
        }
        
        .mobile-sidebar-overlay {
            position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(0,0,0,0.7); z-index: 1500;
            display: none; opacity: 0; transition: opacity 0.3s;
        }
        .mobile-sidebar-overlay.open {
            display: block; opacity: 1;
        }
        
        #btn-sidebar-toggle {
            display: none;
            background: none; border: none; color: var(--text-primary);
            font-size: 20px; cursor: pointer; padding: 5px; margin-right: 15px;
        }
        @media (max-width: 900px) {
            #btn-sidebar-toggle { display: block; }
        }
    </style>

    @stack('styles')
</head>
<body>
<div class="admin-layout">

    {{-- ============================================================
         SIDEBAR
    ============================================================ --}}
    <aside class="admin-sidebar">

        {{-- Brand --}}
        <a href="{{ route('admin.dashboard') }}" class="sidebar-brand">
            <div class="sidebar-brand-icon" style="display:flex;align-items:center;justify-content:center;width:40px;height:40px;">
                <img src="{{ asset('images/logo.png') }}" alt="GameVault Logo" style="height:32px; width:auto; filter: drop-shadow(0 0 10px rgba(118,185,0,0.6));">
            </div>
            <div class="sidebar-brand-text">
                <span style="color:#fff; font-size:1.2rem; font-weight:800; letter-spacing:0.5px;">Game<span style="color:var(--nvidia-green);">Vault</span></span>
            </div>
        </a>

        {{-- Main Nav --}}
        <div class="sidebar-section">
            <div class="sidebar-section-label">Main Menu</div>
            <ul class="sidebar-nav">

                <li>
                    <a href="{{ route('admin.dashboard') }}"
                       class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <i class="fa-solid fa-gauge-high nav-icon"></i>
                        Dashboard
                    </a>
                </li>

                <li>
                    <a href="{{ route('admin.games.index') }}"
                       class="{{ request()->routeIs('admin.games.*') ? 'active' : '' }}">
                        <i class="fa-solid fa-dice-d6 nav-icon"></i>
                        Games
                    </a>
                </li>

                <li>
                    <a href="{{ route('admin.transactions.index') }}"
                       class="{{ request()->routeIs('admin.transactions.*') ? 'active' : '' }}">
                        <i class="fa-solid fa-receipt nav-icon"></i>
                        Transaksi
                    </a>
                </li>

                <li>
                    <a href="{{ route('admin.users.index') }}"
                       class="{{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                        <i class="fa-solid fa-users nav-icon"></i>
                        Users
                    </a>
                </li>

                <li>
                    <a href="{{ route('admin.refunds.index') }}"
                       class="{{ request()->routeIs('admin.refunds.*') ? 'active' : '' }}">
                        <i class="fa-solid fa-rotate-left nav-icon"></i>
                        Refunds
                        @php $pendingRefundCount = session('pending_refund_count', 0); @endphp
                        @if($pendingRefundCount > 0)
                            <span class="nav-badge">{{ $pendingRefundCount }}</span>
                        @endif
                    </a>
                </li>

            </ul>
        </div>

        {{-- Bottom --}}
        <div class="sidebar-bottom">
            <a href="{{ route('home') }}" style="display: flex; align-items: center; gap: 10px; color: #a1a1aa; text-decoration: none; font-size: 14px; font-weight: 500; padding: 12px; border-radius: 0; transition: 0.2s;">
                <i class="fa-solid fa-house" style="font-size: 16px;"></i> Kembali ke Beranda
            </a>
        </div>

    </aside>

    <div class="mobile-sidebar-overlay" id="mobileSidebarOverlay" onclick="toggleSidebar()"></div>

    {{-- ============================================================
         MAIN CONTENT
    ============================================================ --}}
    <div class="admin-content">

        {{-- Top Bar --}}
        <header class="admin-topbar">
            <div class="topbar-title">
                <button id="btn-sidebar-toggle" onclick="toggleSidebar()">
                    <i class="fa-solid fa-bars"></i>
                </button>
                <i class="fa-solid fa-grip" style="color:var(--nvidia-green);font-size:16px;"></i>
                <span class="breadcrumb-sep">/</span>
                <span class="page-name">@yield('page_title', 'Dashboard')</span>
            </div>

            <div class="topbar-right">
                <a href="{{ route('admin.refunds.index') }}" class="topbar-icon-btn" title="Refunds">
                    <i class="fa-solid fa-rotate-left"></i>
                </a>

                <div class="topbar-admin">
                    <div class="topbar-avatar">
                        <img src="{{ auth()->user()->avatar_url }}" alt="Avatar" style="width:100%;height:100%;object-fit:cover;">
                    </div>
                    <div>
                        <div class="topbar-admin-name">{{ auth()->user()->name ?? 'Admin' }}</div>
                        <div class="topbar-admin-role">Administrator</div>
                    </div>
                </div>
            </div>
        </header>

        {{-- Page Content --}}
        <main class="admin-main">
            @yield('content')
        </main>

    </div>{{-- /.admin-content --}}

</div>{{-- /.admin-layout --}}

{{-- ============================================================
     TOAST CONTAINER
============================================================ --}}
<div class="toast-container" id="toastContainer"></div>

{{-- ============================================================
     SCRIPTS
============================================================ --}}
<script>
// ---- Toast System ----
function showToast(type, title, message, duration = 4000) {
    const icons = { success: 'fa-circle-check', error: 'fa-circle-xmark', warning: 'fa-triangle-exclamation' };
    const container = document.getElementById('toastContainer');
    const toast = document.createElement('div');
    toast.className = `toast toast-${type}`;
    toast.innerHTML = `
        <i class="fa-solid ${icons[type] || icons.success} toast-icon"></i>
        <div class="toast-body">
            <div class="toast-title">${title}</div>
            ${message ? `<div class="toast-msg">${message}</div>` : ''}
        </div>
        <button class="toast-close" onclick="dismissToast(this.parentElement)">
            <i class="fa-solid fa-xmark"></i>
        </button>`;
    container.appendChild(toast);
    if (duration > 0) setTimeout(() => dismissToast(toast), duration);
}

function dismissToast(el) {
    if (!el) return;
    el.style.animation = 'slideOut .3s ease forwards';
    setTimeout(() => el.remove(), 300);
}

// ---- Accordion ----
document.addEventListener('DOMContentLoaded', function () {

    // Accordion for acc-header / acc-body pattern
    document.querySelectorAll('.acc-header').forEach(header => {
        header.addEventListener('click', function () {
            const body = this.nextElementSibling;
            const isOpen = body && body.classList.contains('open');
            if (isOpen) {
                this.classList.remove('open');
                if (body) body.classList.remove('open');
            } else {
                this.classList.add('open');
                if (body) body.classList.add('open');
            }
        });
    });

    // Accordion for transaction rows
    document.querySelectorAll('.accordion-toggle').forEach(row => {
        row.addEventListener('click', function () {
            const targetId = this.dataset.target;
            if (!targetId) return;
            const target = document.getElementById(targetId);
            if (!target) return;
            const content = target.querySelector('.accordion-content');
            if (content) {
                content.classList.toggle('open');
                const icon = this.querySelector('.acc-row-icon');
                if (icon) icon.style.transform = content.classList.contains('open') ? 'rotate(180deg)' : '';
            }
        });
    });

    // Flash session toasts (converted to sweet alerts)
    @if(session('success'))
        showSweetAlert('Berhasil!', '{!! addslashes(session('success')) !!}', 'success');
    @endif
    @if(session('error'))
        showSweetAlert('Gagal!', '{!! addslashes(session('error')) !!}', 'error');
    @endif
    @if(session('warning'))
        showSweetAlert('Perhatian!', '{!! addslashes(session('warning')) !!}', 'warning');
    @endif
    @if($errors->any())
        showSweetAlert('Validasi Gagal!', '{!! addslashes($errors->first()) !!}', 'error');
    @endif

    // Check pending refund count
    @php
        $pendingCount = session('pending_refund_count', 0);
    @endphp
    @if($pendingCount > 0)
        setTimeout(() => showToast('warning', 'Refund Pending', '{{ $pendingCount }} permintaan refund menunggu persetujuan.', 6000), 1200);
    @endif
});

function closeSweetAlert() {
        const overlay = document.getElementById('sweet-alert-overlay');
        if (overlay) {
            overlay.classList.remove('open');
            const modal = overlay.querySelector('.modal');
            if(modal) modal.style.transform = 'scale(0.9)';
            setTimeout(() => overlay.remove(), 300);
        }
    }

    function showSweetAlert(title, message, type = 'success') {
        let overlay = document.getElementById('sweet-alert-overlay');
        if (!overlay) {
            overlay = document.createElement('div');
            overlay.id = 'sweet-alert-overlay';
            overlay.className = 'modal-overlay';
            document.body.appendChild(overlay);
        }
        
        let iconHtml = '';
        if (type === 'success') {
            iconHtml = `<svg class="svg-success-circle" viewBox="-2 -2 56 56" style="width:72px; height:72px; display:inline-block; border-radius: 0; fill:none;">
              <circle cx="26" cy="26" r="24" fill="none"/>
              <path class="svg-success-check" fill="none" d="M14.1 27.2l7.1 7.2 16.7-16.8"/>
            </svg>`;
        } else {
            let iconClass = 'fa-circle-check';
            let iconColor = 'var(--nvidia-green)';
            if(type === 'warning') {
                iconClass = 'fa-triangle-exclamation';
                iconColor = 'var(--c-warning)';
            } else if(type === 'error') {
                iconClass = 'fa-circle-xmark';
                iconColor = 'var(--c-error)';
            }
            iconHtml = `<i class="fa-solid ${iconClass}" style="color: ${iconColor};"></i>`;
        }
        
        overlay.innerHTML = `
          <div class="modal-box" style="text-align:center; padding: 40px 24px; max-width: 360px; margin: 0 auto;">
            <div style="font-size: 64px; margin-bottom: 24px;">
                ${iconHtml}
            </div>
            <h3 style="font-size: 24px; font-weight: 800; margin: 0 0 12px 0;">${title}</h3>
            <p style="color: var(--text-primary); margin: 0 0 32px 0; line-height: 1.5;">${message}</p>
            <button class="btn-primary w-full" style="width:100%; justify-content:center; padding: 12px;" onclick="closeSweetAlert()">OK</button>
          </div>
        `;
        
        void overlay.offsetWidth; // trigger reflow
        overlay.classList.add('open');
    }

    function showCenterPopup(message, isSuccess = true) {
        showSweetAlert(isSuccess ? 'Berhasil!' : 'Oops!', message, isSuccess ? 'success' : 'error');
    }

// ---- Delete Confirm Modal ----
function openDeleteModal(gameId, gameTitle) {
    const modal = document.getElementById('deleteModal');
    const form  = document.getElementById('deleteForm');
    const title = document.getElementById('deleteModalTitle');
    if (!modal || !form) return;
    if (title) title.textContent = `Hapus "${gameTitle}"?`;
    form.action = form.dataset.baseAction.replace('__ID__', gameId);
    modal.classList.add('open');
}
function closeModal(id) {
    const modal = document.getElementById(id);
    if(modal) {
        modal.classList.remove('open');
    }
}

function toggleSidebar() {
    document.querySelector('.admin-sidebar').classList.toggle('open');
    document.getElementById('mobileSidebarOverlay').classList.toggle('open');
}

// Close modal on overlay click
document.addEventListener('click', function(e) {
    if (e.target && e.target.id === 'deleteModal') closeDeleteModal();
});
</script>

@stack('scripts')
</body>
</html>
