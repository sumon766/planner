<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Admin Panel') }}</title>

    <!--Bootstrap icons-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Vite (custom CSS/JS) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @livewireStyles
</head>

<body>

<!-- Sidebar -->
<div class="sidebar">

    <div class="sidebar-brand">
        <a class="brand-name" href="{{ route('dashboard') }}">Daily Planner</a>
    </div>

    <!-- Dashboard -->
    <a href="{{ route('dashboard') }}"
       class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
        <i class="fa-solid fa-gauge me-2"></i>
        Dashboard
    </a>

    <!-- Routine Tasks -->
    <div class="sidebar-dropdown">

        <a href="#routineTasksMenu"
           data-bs-toggle="collapse"
           class="d-flex justify-content-between align-items-center sidebar-toggle
                  {{ request()->routeIs('routine-tasks.*') ? '' : 'collapsed' }}">

            <span>
                <i class="fa-solid fa-list-check me-2"></i>
                Routine Tasks
            </span>

            <i class="fa-solid fa-chevron-down arrow"></i>
        </a>

        <div class="collapse ps-3 {{ request()->routeIs('routine-tasks.*') ? 'show' : '' }}"
             id="routineTasksMenu">

            <a href="{{ route('routine-tasks.index') }}"
               class="sidebar-sublink {{ request()->routeIs('routine-tasks.index') ? 'active' : '' }}">
                <i class="fa-regular fa-circle me-2"></i>
                All Routine Tasks
            </a>

            <a href="{{ route('routine-tasks.create') }}"
               class="sidebar-sublink {{ request()->routeIs('routine-tasks.create') ? 'active' : '' }}">
                <i class="fa-solid fa-plus me-2"></i>
                Add New Task
            </a>

        </div>
    </div>

    <!-- Extra Tasks -->
    <div class="sidebar-dropdown">

        <a href="#extraTasksMenu"
           data-bs-toggle="collapse"
           class="d-flex justify-content-between align-items-center sidebar-toggle
                  {{ request()->routeIs('extra-tasks.*') ? '' : 'collapsed' }}">

            <span>
                <i class="fa-solid fa-bolt me-2"></i>
                Extra Tasks
            </span>

            <i class="fa-solid fa-chevron-down arrow"></i>
        </a>

        <div class="collapse ps-3 {{ request()->routeIs('extra-tasks.*') ? 'show' : '' }}"
             id="extraTasksMenu">

            <a href="#" class="sidebar-sublink {{ request()->routeIs('extra-tasks.index') ? 'active' : '' }}">
                <i class="fa-regular fa-circle me-2"></i>
                All Extra Tasks
            </a>

            <a href="#" class="sidebar-sublink {{ request()->routeIs('extra-tasks.create') ? 'active' : '' }}">
                <i class="fa-solid fa-plus me-2"></i>
                Add New Task
            </a>

        </div>
    </div>

    <!-- Accounts -->
    <div class="sidebar-dropdown">

        <a href="#accountsMenu"
           data-bs-toggle="collapse"
           class="d-flex justify-content-between align-items-center sidebar-toggle
                  {{ request()->routeIs('accounts.*') ? '' : 'collapsed' }}">

            <span>
                <i class="fa-solid fa-wallet me-2"></i>
                Accounts
            </span>

            <i class="fa-solid fa-chevron-down arrow"></i>
        </a>

        <div class="collapse ps-3 {{ request()->routeIs('accounts.*') ? 'show' : '' }}"
             id="accountsMenu">

            <!-- Add Expense -->
            <a href="#" class="sidebar-sublink {{ request()->routeIs('accounts.expense') ? 'active' : '' }}">
                <i class="fa-solid fa-money-bill-transfer me-2"></i>
                Add Expense
            </a>

            <!-- Balance / Report -->
            <a href="#" class="sidebar-sublink {{ request()->routeIs('accounts.report') ? 'active' : '' }}">
                <i class="fa-solid fa-chart-line me-2"></i>
                Balance / Report
            </a>

            <!-- Expense Category -->
            <a href="#" class="sidebar-sublink {{ request()->routeIs('accounts.categories') ? 'active' : '' }}">
                <i class="fa-solid fa-tags me-2"></i>
                Expense Categories
            </a>

            <!-- Add Balance -->
            <a href="#" class="sidebar-sublink {{ request()->routeIs('accounts.add-balance') ? 'active' : '' }}">
                <i class="fa-solid fa-circle-plus me-2"></i>
                Add Balance
            </a>

        </div>
    </div>

</div>

<!-- Topbar -->
<div class="topbar">
    <div>
        <strong>Set & Track Your Daily Activity</strong>
    </div>

    <div class="dropdown">
        <a class="d-flex align-items-center text-decoration-none"
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
            <div class="d-flex flex-column text-start me-2">
                <span class="fw-semibold text-dark" style="font-size: 14px;">
                    {{ Auth::user()->name }}
                </span>
                <small class="text-muted" style="font-size: 12px;">
                    Account
                </small>
            </div>

            <!-- Arrow -->
            <i class="fa-solid fa-chevron-down dropdown-arrow"></i>

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
