@extends('layouts.app')

@section('title', 'Create Timetable')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Create Weekly Timetable</h2>
        @php
            $isFaculty = auth()->user()->role === 'is_facultyAdmin';
            $baseRoute = $isFaculty ? 'faculty' : 'admin';
        @endphp
        <div>
            <a href="{{ url("/$baseRoute/dashboard") }}" class="btn btn-outline-secondary me-2">Back to Dashboard</a>
            <a href="{{ route("$baseRoute.timetables.index") }}" class="btn btn-outline-secondary">Back to Timetables</a>
        </div>
    </div>

    <form action="{{ route("$baseRoute.timetables.store") }}" method="POST">
        @csrf

        <div class="table-responsive">
            <table class="table table-bordered align-middle text-center">
                <thead class="table-light">
                    <tr>
                        <th>Day / Slot</th>
                        @for($i = 1; $i <= 4; $i++)
                            <th>Slot {{ $i }}</th>
                        @endfor
                    </tr>
                </thead>
                <tbody>
                    @php
                        $weekdays = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];
                        $flatSlot = 1;
                    @endphp

                    @foreach($weekdays as $day)
                        <tr>
                            <th>{{ $day }}</th>
                            @for($slot = 1; $slot <= 4; $slot++)
                                <td>
                                    <input type="hidden" name="entries[{{ $day }}][{{ $slot }}][day]" value="{{ $day }}">
                                    <input type="hidden" name="entries[{{ $day }}][{{ $slot }}][slot_number]" value="{{ $flatSlot }}">

                                    <input type="text" class="form-control shadow-sm mb-1"
                                        name="entries[{{ $day }}][{{ $slot }}][module_name]"
                                        placeholder="Module Name"
                                        value="{{ old("entries.$day.$slot.module_name") }}"
                                    >

                                    <input type="text" class="form-control shadow-sm mb-1"
                                        name="entries[{{ $day }}][{{ $slot }}][venue]"
                                        placeholder="Venue"
                                        value="{{ old("entries.$day.$slot.venue") }}"
                                    >

                                    <div class="d-flex gap-1 mb-1">
                                        <input type="time" class="form-control shadow-sm"
                                            name="entries[{{ $day }}][{{ $slot }}][start_time]"
                                            value="{{ old("entries.$day.$slot.start_time") }}"
                                        >
                                        <input type="time" class="form-control shadow-sm"
                                            name="entries[{{ $day }}][{{ $slot }}][end_time]"
                                            value="{{ old("entries.$day.$slot.end_time") }}"
                                        >
                                    </div>

                                    {{-- Class Code + Datalist --}}
                                    <input type="text" class="form-control shadow-sm mb-1"
                                        name="entries[{{ $day }}][{{ $slot }}][class_code]"
                                        list="classCodes"
                                        placeholder="Class Code"
                                        value="{{ old("entries.$day.$slot.class_code") }}"
                                    >

                                    {{-- Lecturer Name + Datalist --}}
                                    <input type="text" class="form-control shadow-sm mb-1"
                                        name="entries[{{ $day }}][{{ $slot }}][lecturer_name]"
                                        list="lecturers"
                                        placeholder="Lecturer"
                                        value="{{ old("entries.$day.$slot.lecturer_name") }}"
                                    >
                                </td>
                                @php $flatSlot++; @endphp
                            @endfor
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- DATALISTS --}}
        <datalist id="classCodes">
            @foreach($classCodes as $classCode)
                <option value="{{ $classCode->code }}"></option>
            @endforeach
        </datalist>

        <datalist id="lecturers">
            @foreach($lecturers as $lecturer)
                <option value="{{ $lecturer->name }}"></option>
            @endforeach
        </datalist>

        <div class="mt-3 mb-5 text-end">
            <button type="submit" class="btn btn-outline-primary">Save Timetable</button>
        </div>
    </form>
</div>
@endsection
