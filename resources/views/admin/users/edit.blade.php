@extends('layouts.app')

@section('title', 'Edit User')

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
        <h2>Edit User</h2>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.users.update', $user->id) }}">
        @csrf
        @method('PUT')

        <div class="card shadow p-4 mx-auto" style="max-width: 500px;">
            <div class="mb-3">
                <label for="full_name" class="form-label">Full Name</label>
                <input type="text" class="form-control @error('full_name') is-invalid @enderror"
                       id="full_name" name="full_name" value="{{ old('full_name', $user->full_name) }}" required>
                @error('full_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email Address</label>
                <input type="email" class="form-control @error('email') is-invalid @enderror"
                       id="email" name="email" value="{{ old('email', $user->email) }}" required>
                @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label for="student_id" class="form-label">Student ID</label>
                <input type="text" class="form-control @error('student_id') is-invalid @enderror"
                       id="student_id" name="student_id" value="{{ old('student_id', $user->student_id) }}">
                @error('student_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label for="class_code" class="form-label">Class Code</label>
                <input type="text" class="form-control @error('class_code') is-invalid @enderror"
                       id="class_code" name="class_code" value="{{ old('class_code', $user->class_code) }}">
                @error('class_code') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            @auth
                @if(Auth::user()->role === 'is_admin')
                    <div class="mb-3">
                        <label for="role" class="form-label">Role</label>
                        <select name="role" id="role" class="form-select @error('role') is-invalid @enderror" required>
                            <option value="Student" {{ $user->role === 'Student' ? 'selected' : '' }}>Student</option>
                            <option value="is_facultyAdmin" {{ $user->role === 'is_facultyAdmin' ? 'selected' : '' }}>Faculty Admin</option>
                            <option value="Lecturer" {{ $user->role === 'Lecturer' ? 'selected' : '' }}>Lecturer</option>
                            <option value="is_admin" {{ $user->role === 'is_admin' ? 'selected' : '' }}>Admin</option>
                        </select>
                        @error('role') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                @endif
            @endauth

            <div class="d-flex justify-content-between">
                <button type="submit" class="btn btn-primary">Update User</button>
                <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </div>
    </form>
@endsection

@section('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const body = document.body;
            const toggleBtn = document.getElementById('themeToggle');

            const isDark = localStorage.getItem('theme') === 'dark';
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
