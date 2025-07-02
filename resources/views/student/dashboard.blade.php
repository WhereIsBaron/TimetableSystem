@extends('layouts.app')

@section('title', 'Student Dashboard')

@section('styles')
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
        border: 1px solid #555;
    }

    .bg-entry { background-color: rgba(108, 117, 125, 0.1); padding: 0.5rem 0.75rem; font-size: 0.875rem; }
    .entry-value { font-weight: 500; color: #212529; }
    .day-cell { background-color: #f8f9fa; font-weight: bold; }
    .slot-header { font-size: 0.85rem; font-weight: bold; text-align: center; background-color: #f1f3f5; }
    table { table-layout: auto !important; width: 100%; }
    th, td { white-space: nowrap; width: 1%; vertical-align: top; }
    [data-theme="dark"] .bg-entry { background-color: #2c2c2c; }
    [data-theme="dark"] .entry-value, [data-theme="dark"] .entry-value * { color: #fff !important; }
    [data-theme="dark"] .day-cell { background-color: #343a40; color: #f8f9fa !important; }
    [data-theme="dark"] .slot-header { background-color: #444; color: #fff; }
</style>
@endsection

@section('content')
<div class="container-fluid py-4 px-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="mb-1">📅 My Timetable</h1>
            <p class="lead mb-0">Welcome, {{ auth()->user()->full_name }}!</p>
            <p class="text-muted mb-0">This is your timetable based on your class code: <strong>{{ auth()->user()->class_code ?? 'N/A' }}</strong>.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('student.timetables.export') }}" target="_blank" class="btn btn-outline-success">
                🖨️ Export Timetable
            </a>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-outline-danger">Logout</button>
            </form>
        </div>
    </div>

    @php
        $slotTimes = [
            1 => 'Early Morning Lessons',
            2 => 'Late Morning Lessons',
            3 => 'Early Afternoon Lessons',
            4 => 'Late Afternoon Lessons',
        ];
        $weekDays = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];
    @endphp

    @if(isset($timetables))
    <div class="table-responsive">
        <table class="table table-bordered align-middle text-start">
            <thead>
                <tr>
                    <th style="width: 120px;">Day</th>
                    @foreach($slotTimes as $slot => $time)
                        <th class="slot-header">Slot {{ $slot }}<br><small>{{ $time }}</small></th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach ($weekDays as $day)
                    <tr class="align-top">
                        <th class="day-cell">{{ $day }}</th>
                        @foreach ($slotTimes as $slot => $time)
                            <td>
                                @php
                                    $entries = $timetables->filter(fn($entry) => $entry->day === $day && $entry->slot_number == $slot);
                                @endphp

                                @if ($entries->isNotEmpty())
                                    @foreach ($entries as $entry)
                                        <div class="mb-2 p-2 border rounded bg-entry border-surface">
                                            <div><strong>Module:</strong> <span class="entry-value">{{ $entry->module_name ?? 'N/A' }}</span></div>
                                            <div><strong>Lecturer:</strong> <span class="entry-value">{{ optional($entry->lecturer)->full_name ?? 'N/A' }}</span></div>
                                            <div><strong>Class:</strong> <span class="entry-value">{{ optional($entry->classCode)->code ?? 'N/A' }}</span></div>
                                            <div><strong>Time:</strong> <span class="entry-value">{{ $entry->start_time }} - {{ $entry->end_time }}</span></div>
                                            <div><strong>Venue:</strong> <span class="entry-value">{{ $entry->venue ?? 'N/A' }}</span></div>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="text-muted fst-italic">No entries</div>
                                @endif
                            </td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
</div>
@endsection
