@extends('layouts.app')

@section('title', 'Master Timetable Management')

@section('content')
<div class="container-fluid py-4 px-5">
    <style>
        .bg-entry {
            background-color: rgba(108, 117, 125, 0.1);
            padding: 0.5rem 0.75rem;
            font-size: 0.875rem;
        }

        .entry-value {
            font-weight: 500;
            color: #212529;
        }

        .day-cell {
            background-color: #f8f9fa;
            font-weight: bold;
        }

        .slot-header {
            font-size: 0.85rem;
            font-weight: bold;
            text-align: center;
            background-color: #f1f3f5;
        }

        table {
            width: 100%;
            table-layout: fixed;
        }

        th,
        td {
            vertical-align: top;
            word-wrap: break-word;
            white-space: normal;
        }

        [data-theme="dark"] .bg-entry {
            background-color: #2c2c2c;
        }

        [data-theme="dark"] .entry-value,
        [data-theme="dark"] .entry-value * {
            color: #fff !important;
        }

        [data-theme="dark"] .day-cell {
            background-color: #343a40;
            color: #f8f9fa !important;
        }

        [data-theme="dark"] .slot-header {
            background-color: #444;
            color: #fff;
        }
    </style>

    @php
        $isFaculty = auth()->user()->role === 'is_facultyAdmin';
        $baseRoute = $isFaculty ? 'faculty' : 'admin';
    @endphp

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">📅 Master Timetable</h1>
        <div class="d-flex gap-2">
            <a href="{{ url("/$baseRoute/dashboard") }}" class="btn btn-outline-secondary">
                Back to Dashboard
            </a>
            @if(!$isFaculty)
                <form action="{{ route('admin.devtools.detectConflicts') }}" method="GET">
                    <button type="submit" class="btn btn-outline-warning">
                        🚨 Detect Conflicts
                    </button>
                </form>
            @endif
        </div>
    </div>

    <div class="mb-4 text-end">
        <a href="{{ route("$baseRoute.timetables.create") }}" class="btn btn-outline-success">➕ Add New Slot</a>
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
    <table class="table table-bordered align-middle text-start w-100">
        <thead>
            <tr>
                <th style="width: 120px;">Day</th>
                @foreach($slotTimes as $slot => $label)
                    <th class="slot-header">
                        Slot {{ $slot }}<br><small>{{ $label }}</small>
                    </th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach ($weekDays as $day)
                <tr>
                    <th class="day-cell">{{ $day }}</th>
                    @foreach ($slotTimes as $slot => $label)
                        <td>
                            @php
                                $entries = $timetables->filter(fn($entry) => $entry->day === $day && $entry->slot_number == $slot);
                            @endphp

                            @if ($entries->isNotEmpty())
                                @foreach ($entries as $entry)
                                    <div class="mb-2 p-2 border rounded bg-entry border-surface">
                                        <div><strong>Module:</strong> <span class="entry-value">{{ $entry->module_name }}</span></div>
                                        <div><strong>Lecturer:</strong> <span class="entry-value">{{ optional($entry->lecturer)->full_name ?? 'N/A' }}</span></div>
                                        <div><strong>Class:</strong> 
                                            <span class="entry-value">
                                                {{ optional($entry->classCode)->code ?? 'N/A' }}
                                            </span>
                                        </div>
                                        <div><strong>Time:</strong> 
                                            <span class="entry-value">
                                                {{ \Carbon\Carbon::parse($entry->start_time)->format('H:i') }} - 
                                                {{ \Carbon\Carbon::parse($entry->end_time)->format('H:i') }}
                                            </span>
                                        </div>
                                        <div><strong>Venue:</strong> <span class="entry-value">{{ $entry->venue }}</span></div>
                                        <div class="mt-1">
                                            <a href="{{ route("$baseRoute.timetables.edit", $entry->id) }}" class="btn btn-sm btn-outline-warning">Edit</a>
                                            <form action="{{ route("$baseRoute.timetables.destroy", $entry->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure?')">Delete</button>
                                            </form>
                                        </div>
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
    @endif
</div>
@endsection
