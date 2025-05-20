<!DOCTYPE html>
<<<<<<< HEAD
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Classroom Scheduler')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body.dark-mode {
            background-color: #121212;
            color: #e0e0e0;
        }

        .dark-mode .card,
        .dark-mode .navbar {
            background-color: #1e1e1e;
            color: #e0e0e0;
        }

        .dark-mode .form-control {
            background-color: #2c2c2c;
            color: #e0e0e0;
            border: 1px solid #444;
        }

        .dark-mode .form-control::placeholder,
        .dark-mode input::placeholder,
        .dark-mode textarea::placeholder {
            color: #bfbfbf !important;
            opacity: 1 !important;
        }

        .dark-mode .btn-outline-light {
            color: #e0e0e0;
            border-color: #e0e0e0;
        }

        .dark-mode .btn-outline-light:hover {
            background-color: #e0e0e0;
            color: #121212;
        }

        .theme-toggle-btn {
            position: fixed;
            top: 1rem;
            right: 1rem;
            z-index: 1000;
            border: none;
            background-color: #f8f9fa;
            color: #000;
            padding: 6px 10px;
            border-radius: 6px;
            transition: all 0.3s ease;
        }

        body.dark-mode .theme-toggle-btn {
            background-color: #2c2c2c;
            color: #e0e0e0;
            border: 1px solid #888;
        }

        .theme-toggle-btn:hover {
            opacity: 0.85;
        }

        .dark-mode .table,
        .dark-mode .table th,
        .dark-mode .table td {
            background-color: #23272b !important;
            color: #e0e0e0 !important;
        }

        .dark-mode .table thead,
        .dark-mode .table-light th {
            background-color: #2c2c2c !important;
            color: #e0e0e0 !important;
        }

        /* ✅ Dark Mode Override for .text-muted and placeholder text */
        .dark-mode .text-muted {
            color: #aaa !important;
        }

        /* ✅ Force white text inside entry-value blocks in dark mode */
        body.dark-mode .entry-value,
        body.dark-mode .entry-value *,
        body.dark-mode .entry-value .text-muted {
            color: #ffffff !important;
        }
    </style>

    @yield('styles')
    @stack('styles')
</head>
<body>

<!-- Global Dark Mode Toggle Button -->
<button id="themeToggle" class="theme-toggle-btn">
    <span id="themeIcon">🌙</span>
</button>

<div class="container mt-4">
    @include('partials.flash')
    @yield('content')
</div>

@yield('scripts')
@stack('scripts')

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const body = document.body;
        const toggleBtn = document.getElementById('themeToggle');
        const iconSpan = document.getElementById('themeIcon');

        const isDark = localStorage.getItem('theme') === 'dark';
        if (isDark) {
            body.classList.add('dark-mode');
            iconSpan.textContent = '☀️';
        } else {
            iconSpan.textContent = '🌙';
        }

        toggleBtn.addEventListener('click', function () {
            body.classList.toggle('dark-mode');
            const isNowDark = body.classList.contains('dark-mode');
            localStorage.setItem('theme', isNowDark ? 'dark' : 'light');
            iconSpan.textContent = isNowDark ? '☀️' : '🌙';
        });
    });
</script>
</body>
=======
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white dark:bg-gray-800 shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>
    </body>
>>>>>>> d36a851 (Install Breeze)
</html>
