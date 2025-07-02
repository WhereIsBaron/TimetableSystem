@extends('layouts.app')

@section('title', 'Dev Tools')

@section('styles')
    <style>
        .dev-buttons {
            display: flex;
            flex-direction: column;
            gap: 1rem;
            max-width: 400px;
            margin: 0 auto;
        }

        .dark-mode .card {
            background-color: #1e1e1e;
            color: #e0e0e0;
        }

        .dark-mode .form-control {
            background-color: #2c2c2c;
            color: #e0e0e0;
        }

        .dark-mode .btn-outline-secondary {
            color: #e0e0e0;
            border-color: #6c757d;
        }

        .dark-mode .btn-outline-secondary:hover {
            color: #fff;
            background-color: #6c757d;
        }

        .dark-mode .btn-warning {
            background-color: #ffc107;
            border-color: #ffc107;
            color: #000;
        }

        .dark-mode .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
            color: #fff;
        }

        .btn {
            padding: 0.75rem 1.25rem;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
    </style>
@endsection

@section('content')
    <h1 class="mb-4">🛠️ Dev Tools</h1>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card p-4 shadow-sm">
        <div class="dev-buttons">
            <form action="{{ route('admin.devtools.autoGenerate') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-primary w-100">⚙️ Auto-Generate Timetable</button>
            </form>

            <a href="{{ route('admin.devtools.detectConflicts') }}" class="btn btn-warning w-100">🚨 Detect Schedule Conflicts</a>

            <div class="d-flex justify-content-between mt-3">
                <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">🏠 Back to Dashboard</a>
                <a href="{{ url()->previous() }}" class="btn btn-outline-secondary">🔙 Go Back</a>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const isDark = localStorage.getItem('theme') === 'dark';
            const body = document.body;
            const toggleBtn = document.getElementById('themeToggle');

            if (isDark) body.classList.add('dark-mode');

            const updateIcon = () => {
                if (toggleBtn) {
                    toggleBtn.textContent = body.classList.contains('dark-mode') ? '☀️' : '🌙';
                }
            };

            updateIcon();

            if (toggleBtn) {
                toggleBtn.addEventListener('click', function () {
                    body.classList.toggle('dark-mode');
                    const theme = body.classList.contains('dark-mode') ? 'dark' : 'light';
                    localStorage.setItem('theme', theme);
                    updateIcon();
                });
            }
        });
    </script>
@endsection
