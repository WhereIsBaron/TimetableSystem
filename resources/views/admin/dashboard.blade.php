@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="card shadow mb-4">
    <div class="card-body">
        <p class="mb-0 text-muted">You are logged in as <strong>Administrator</strong>.</p>
    </div>
</div>

<div class="card shadow mb-4">
    <div class="card-body">
        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('admin.users.index') }}" class="btn btn-primary">👥 Users</a>
            <a href="{{ route('admin.timetables.index') }}" class="btn btn-primary">📅 Schedules</a>
            <a href="{{ route('admin.devtools.index') }}" class="btn btn-primary">🧪 Dev Tools</a>
            <form method="POST" action="{{ route('logout') }}" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-danger">🚪 Logout</button>
            </form>
        </div>
    </div>
</div>

<div class="row g-3">
    <div class="col-md-6">
        <div class="card shadow-sm h-100">
            <div class="card-body">
                <h6 class="card-title text-muted fw-semibold">👥 User Summary</h6>
                <p class="mb-1">
                    <strong>Total Users:</strong> {{ \App\Models\User::count() }}
                </p>
                <p>
                    <strong>Admins:</strong> {{ \App\Models\User::where('role', 'is_admin')->count() }}
                </p>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card shadow-sm h-100">
            <div class="card-body">
                <h6 class="card-title text-muted fw-semibold">🕓 Recent Activity</h6>
                <p class="mb-1">📌 Feature rollout on {{ now()->subDays(1)->format('M d, Y') }}</p>
                <p class="mb-0">🗂️ Updated layout system</p>
            </div>
        </div>
    </div>
</div>
@endsection
