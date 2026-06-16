<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Admin Panel') }}</title>

    <!-- Vite (custom CSS/JS) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @livewireStyles
</head>

<body>

<!-- Sidebar -->
<div class="sidebar">
    <div class="sidebar-brand">
        {{ config('app.name', 'Daily Planner') }}
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
                <a class="dropdown-item py-2" href="{{ route('profile') }}">
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

@livewireScripts
</body>
</html>
