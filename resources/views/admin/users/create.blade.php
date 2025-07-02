@extends('layouts.app')

@section('title', 'Create New User')

@section('styles')
    <style>
        .dark-mode .card,
        .dark-mode .form-control,
        .dark-mode .form-select {
            background-color: #1e1e1e;
            color: #e0e0e0;
            border-color: #444;
        }

        .dark-mode .form-control::placeholder {
            color: #aaa;
        }

        .dark-mode .form-select option {
            background-color: #1e1e1e;
            color: #e0e0e0;
        }
    </style>
@endsection

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Create New User</h1>
    </div>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.users.store') }}">
        @csrf

        <div class="card p-4 shadow mx-auto" style="max-width: 450px;">
            <div class="mb-3">
                <label for="full_name" class="form-label">Full Name</label>
                <input type="text" class="form-control @error('full_name') is-invalid @enderror"
                       id="full_name" name="full_name" value="{{ old('full_name') }}" required>
                @error('full_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email Address</label>
                <input type="email" class="form-control @error('email') is-invalid @enderror"
                       id="email" name="email" value="{{ old('email') }}" required>
                @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label for="student_id" class="form-label">Student ID</label>
                <input type="text" class="form-control @error('student_id') is-invalid @enderror"
                       id="student_id" name="student_id" value="{{ old('student_id') }}">
                @error('student_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label for="class_code" class="form-label">Class Code</label>
                <input type="text" class="form-control @error('class_code') is-invalid @enderror"
                       id="class_code" name="class_code" value="{{ old('class_code') }}">
                @error('class_code') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label for="role" class="form-label">Role</label>
                <select name="role" id="role" class="form-select @error('role') is-invalid @enderror" required>
                    <option value="Student" {{ old('role') === 'Student' ? 'selected' : '' }}>Student</option>
                    <option value="is_facultyAdmin" {{ old('role') === 'is_facultyAdmin' ? 'selected' : '' }}>Faculty Admin</option>
                    <option value="Lecturer" {{ old('role') === 'Lecturer' ? 'selected' : '' }}>Lecturer</option>
                    <option value="is_admin" {{ old('role') === 'is_admin' ? 'selected' : '' }}>Admin</option>
                </select>
                @error('role') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <div class="input-group">
                    <input type="password" class="form-control @error('password') is-invalid @enderror"
                           id="password" name="password" required>
                    <button type="button" class="btn btn-outline-secondary" onclick="togglePassword()">👁️</button>
                </div>
                @error('password') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label for="password_confirmation" class="form-label">Confirm Password</label>
                <input type="password" class="form-control"
                       id="password_confirmation" name="password_confirmation" required>
            </div>

            <div class="d-flex justify-content-between">
                <button type="submit" class="btn btn-primary">Create User</button>
                <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </div>
    </form>
@endsection

@section('scripts')
    <script>
        function togglePassword() {
            const pass = document.getElementById('password');
            pass.type = pass.type === 'password' ? 'text' : 'password';
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
