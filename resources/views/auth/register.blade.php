@extends('layouts.app')

@section('title', 'Register')

@section('styles')
    <style>
        .dark-mode .card {
            background-color: #1e1e1e;
            color: #e0e0e0;
        }

        .dark-mode .form-control {
            background-color: #2c2c2c;
            color: #e0e0e0;
        }
    </style>
@endsection

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card p-4 shadow-sm">
                <h3 class="mb-3">Register</h3>

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ url('register') }}">
                    @csrf

                    <div class="mb-3">
                        <label for="full_name" class="form-label">Full Name</label>
                        <input type="text" class="form-control @error('full_name') is-invalid @enderror" id="full_name" name="full_name" value="{{ old('full_name') }}" required>
                        @error('full_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required>
                        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label for="student_id" class="form-label">Student ID</label>
                        <input type="text" class="form-control @error('student_id') is-invalid @enderror" id="student_id" name="student_id" value="{{ old('student_id') }}" required>
                        @error('student_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label for="class_code" class="form-label">Class Code</label>
                        <input type="text" class="form-control @error('class_code') is-invalid @enderror" id="class_code" name="class_code" value="{{ old('class_code') }}" required>
                        @error('class_code') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <div class="input-group">
                            <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required>
                            <button type="button" class="btn btn-outline-secondary" onclick="togglePassword()">👁️</button>
                        </div>
                        @error('password') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">Confirm Password</label>
                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">Register</button>
                    <a href="{{ url('/login') }}" class="btn btn-link w-100">Already have an account? Login</a>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    function togglePassword() {
        const passwordInput = document.getElementById('password');
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
        } else {
            passwordInput.type = 'password';
        }
    }

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
