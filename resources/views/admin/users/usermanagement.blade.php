@extends('layouts.app')

@section('title', 'User Management')

@section('styles')
    <style>
        .group-header {
            background-color: #f8f9fa;
            color: #000;
        }

        body.dark-mode .group-header {
            background-color: #2c2c2c;
            color: #e0e0e0;
        }
    </style>
@endsection

@section('content')
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="mb-0">User Management</h2>
            <a href="{{ url('/admin/dashboard') }}" class="btn btn-outline-secondary">
                Back to Dashboard
            </a>
        </div>

        <div class="card shadow-sm">
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-4">
                        <input type="text" class="form-control" id="classFilter" placeholder="Filter by class code...">
                    </div>
                    <div class="col-md-4">
                        <select class="form-select" id="roleFilter">
                            <option value="">Filter by role</option>
                            <option value="is_admin">Admin</option>
                            <option value="is_facultyAdmin">Faculty Admin</option>
                            <option value="Lecturer">Lecturer</option>
                            <option value="Student">Student</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <input type="text" class="form-control" id="emailFilter" placeholder="Filter by email...">
                    </div>
                </div>

                <div class="mb-3 text-end">
                    <a href="{{ route('admin.users.create') }}" class="btn btn-outline-success">+ Create New User</a>
                </div>

                @foreach($groupedUsers as $classCode => $classUsers)
                    <div class="mb-4 class-group" data-class="{{ strtolower($classCode ?? 'unassigned') }}">
                        <div class="px-3 py-2 border rounded group-header d-flex justify-content-between align-items-center collapse-toggle"
                             data-bs-toggle="collapse"
                             data-bs-target="#group-{{ $loop->index }}"
                             style="cursor: pointer;">
                            <strong>Class Code: {{ $classCode ?? 'Unassigned' }}</strong>
                            <span class="badge bg-secondary">{{ $classUsers->count() }} Users</span>
                        </div>
                        <div id="group-{{ $loop->index }}" class="collapse show mt-2">
                            <div class="table-responsive">
                                <table class="table table-bordered align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Full Name</th>
                                            <th>Email</th>
                                            <th>Student ID</th>
                                            <th>Role</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($classUsers as $user)
                                            <tr>
                                                <td>{{ $user->full_name }}</td>
                                                <td>{{ $user->email }}</td>
                                                <td>{{ $user->student_id }}</td>
                                                <td>
                                                    @switch($user->role)
                                                        @case('is_admin') Admin @break
                                                        @case('is_facultyAdmin') Faculty Admin @break
                                                        @case('Lecturer') Lecturer @break
                                                        @default Student
                                                    @endswitch
                                                </td>
                                                <td>
                                                    <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                                                    <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure you want to delete this user?')">Delete</button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @endforeach

            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    const classInput = document.getElementById('classFilter');
    const emailInput = document.getElementById('emailFilter');
    const roleSelect = document.getElementById('roleFilter');

    function applyFilters() {
        const classFilter = classInput.value.toLowerCase();
        const emailFilter = emailInput.value.toLowerCase();
        const roleFilter = roleSelect.value.toLowerCase().trim();

        document.querySelectorAll('.class-group').forEach(group => {
            const classCode = group.getAttribute('data-class');
            const users = group.querySelectorAll('tbody tr');
            let hasVisibleUsers = false;

            users.forEach(user => {
                const email = user.cells[1].textContent.toLowerCase();
                const roleCell = user.cells[3].textContent.trim().toLowerCase();

                const matchesClass = !classFilter || classCode.includes(classFilter);
                const matchesEmail = !emailFilter || email.includes(emailFilter);
                const matchesRole = !roleFilter
                    || (roleFilter === 'is_admin' && roleCell === 'admin')
                    || (roleFilter === 'is_facultyadmin' && roleCell === 'faculty admin')
                    || (roleFilter === 'lecturer' && roleCell === 'lecturer')
                    || (roleFilter === 'student' && roleCell === 'student');

                const matchesAll = matchesClass && matchesEmail && matchesRole;
                user.style.display = matchesAll ? '' : 'none';

                if (matchesAll) hasVisibleUsers = true;
            });

            // Show or hide entire class group depending on matching users
            group.style.display = hasVisibleUsers ? '' : 'none';
        });
    }

    classInput.addEventListener('input', applyFilters);
    emailInput.addEventListener('input', applyFilters);
    roleSelect.addEventListener('change', applyFilters);
</script>
@endsection

