<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Admin Panel') }}</title>

    <!-- Google Font (Inter) -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap 5.3 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: #f4f6f9;
        }

        /* Sidebar */
        .sidebar {
            width: 260px;
            min-height: 100vh;
            background: #111827; /* dark modern navy */
            position: fixed;
            top: 0;
            left: 0;
            padding-top: 20px;
        }

        .sidebar a {
            color: #cbd5e1;
            text-decoration: none;
            display: block;
            padding: 12px 20px;
            border-radius: 10px;
            margin: 4px 12px;
            font-size: 14px;
        }

        .sidebar a:hover {
            background: #1f2937;
            color: #fff;
        }

        .sidebar-brand {
            color: #fff;
            font-weight: 600;
            font-size: 18px;
            padding: 0 20px 20px;
        }

        /* Topbar */
        .topbar {
            height: 60px;
            background: #fff;
            border-bottom: 1px solid #e5e7eb;
            margin-left: 260px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 20px;
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        /* Content */
        .main-content {
            margin-left: 260px;
            padding: 20px;
        }
    </style>
</head>

<body>

<!-- Sidebar -->
<div class="sidebar">
    <div class="sidebar-brand">
        {{ config('app.name', 'Admin') }}
    </div>

    <a href="#">Dashboard</a>
    <a href="#">Users</a>
    <a href="#">Projects</a>
    <a href="#">Settings</a>
</div>

<!-- Topbar -->
<div class="topbar">
    <div>
        <strong>Set & Track Your Daily Activity</strong>
    </div>

    <div class="dropdown">
        <a class="d-flex align-items-center text-decoration-none dropdown-toggle"
           href="#"
           id="userDropdown"
           data-bs-toggle="dropdown"
           aria-expanded="false">

            <!-- Avatar -->
            <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=111827&color=fff"
                 alt="avatar"
                 width="36"
                 height="36"
                 class="rounded-circle me-2"
                 style="object-fit: cover;">

            <!-- Name -->
            <div class="d-flex flex-column text-start">
            <span class="fw-semibold text-dark" style="font-size: 14px;">
                {{ Auth::user()->name }}
            </span>
                <small class="text-muted" style="font-size: 12px;">
                    Account
                </small>
            </div>
        </a>

        <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2"
            aria-labelledby="userDropdown"
            style="min-width: 220px;">

            <li>
                <a class="dropdown-item py-2" href="#">
                    Profile
                </a>
            </li>

            <li>
                <a class="dropdown-item py-2" href="#">
                    Settings
                </a>
            </li>

            <li><hr class="dropdown-divider"></li>

            <li>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="dropdown-item text-danger py-2">
                        Logout
                    </button>
                </form>
            </li>
        </ul>
    </div>
</div>

<!-- Page Content -->
<main class="main-content">
    @isset($header)
        <div class="mb-3">
            {{ $header }}
        </div>
    @endisset

    @yield('content')
</main>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
