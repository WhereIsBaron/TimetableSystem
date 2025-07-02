@extends('layouts.app')

@section('title', 'Conflict Detection Report')

@section('styles')
<style>
    .btn-edit:hover {
        background-color: #ffc107 !important;
        border-color: #ffc107 !important;
        color: #000 !important;
    }
</style>
@endsection

@section('content')
<div class="container py-4">
    {{-- Header and Top-Right Buttons --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">🚨 Timetable Conflict Report</h2>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-primary">🏠 Back to Dashboard</a>
            <a href="{{ route('admin.timetables.index') }}" class="btn btn-outline-primary">📅 View Timetable</a>
            <a href="{{ url()->previous() }}" class="btn btn-outline-secondary">🔙 Go Back</a>
        </div>
    </div>

    {{-- Conflict Section --}}
    <div class="mb-5">
        <h4>⛔ Clashes Detected</h4>
        @if($conflicts->isEmpty())
            <p class="text-success">No venue/time clashes found.</p>
        @else
            @foreach ($conflicts as $pair)
                <div class="alert alert-danger mb-3">
                    <strong>Conflict ({{ $pair['a']->day }} - {{ $pair['a']->venue }}):</strong><br>
                    <ul class="mb-0">
                        <li>
                            {{ $pair['a']->module_name }}:
                            {{ \Carbon\Carbon::parse($pair['a']->start_time)->format('H:i') }}–{{ \Carbon\Carbon::parse($pair['a']->end_time)->format('H:i') }}
                            <a href="{{ route('admin.timetables.edit', $pair['a']->id) }}" class="btn btn-sm btn-outline-secondary btn-edit ms-2">✏️ Edit</a>
                        </li>
                        <li>
                            {{ $pair['b']->module_name }}:
                            {{ \Carbon\Carbon::parse($pair['b']->start_time)->format('H:i') }}–{{ \Carbon\Carbon::parse($pair['b']->end_time)->format('H:i') }}
                            <a href="{{ route('admin.timetables.edit', $pair['b']->id) }}" class="btn btn-sm btn-outline-secondary btn-edit ms-2">✏️ Edit</a>
                        </li>
                    </ul>
                </div>
            @endforeach
        @endif
    </div>
</div>
@endsection
